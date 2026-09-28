<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Presenca;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PresencaController extends Controller
{
    const STATUS = ['presente', 'falta', 'justificado'];

    public function index()
    {
        $aulas = Aula::with('professor')->orderBy('data_aulas', 'desc')->get();

        // Uma consulta só para todas as aulas (antes era uma por aula).
        $contagem = Presenca::selectRaw('id_aulas, LOWER(status_presenca) as status, COUNT(*) as total')
            ->groupBy('id_aulas', 'status')
            ->get()
            ->groupBy('id_aulas');

        foreach ($aulas as $aula) {
            $daAula = ($contagem[$aula->id_aulas] ?? collect())->pluck('total', 'status');

            $aula->presentes    = (int) ($daAula['presente'] ?? 0);
            $aula->faltas       = (int) ($daAula['falta'] ?? 0);
            $aula->justificados = (int) ($daAula['justificado'] ?? 0);
        }

        // Presenças das aulas de hoje.
        $registrosHoje = Presenca::with('aluno', 'aula')
            ->where('data_registro_presenca', date('Y-m-d'))
            ->get();

        return view('admin.presenca.index', compact('aulas', 'registrosHoje'));
    }

    public function alunos($id_aulas)
    {
        $aula = Aula::with('modulo')->findOrFail($id_aulas);

        $alunos = $this->alunosDaAula($aula);

        $presencas = Presenca::where('id_aulas', $aula->id_aulas)
            ->get()
            ->keyBy('id_aluno');

        return view('admin.presenca.alunos', compact('aula', 'alunos', 'presencas'));
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'id_aulas'    => 'required|exists:tbl_aulas,id_aulas',
            'presencas'   => 'required|array',
            'presencas.*' => ['required', Rule::in(self::STATUS)],
        ]);

        $aula = Aula::with('modulo')->findOrFail($request->id_aulas);
        $idsPermitidos = $this->alunosDaAula($aula)->pluck('id_aluno')->map(fn ($id) => (int) $id);

        foreach ($request->presencas as $id_aluno => $status) {
            if (!$idsPermitidos->contains((int) $id_aluno)) {
                continue; // aluno que não é desta aula
            }

            // Um registro por aluno em cada aula, sempre com a data da aula
            // (antes usava a data do dia em que o professor lançou).
            Presenca::updateOrCreate(
                ['id_aulas' => $aula->id_aulas, 'id_aluno' => $id_aluno],
                ['status_presenca' => $status, 'data_registro_presenca' => $aula->data_aulas]
            );
        }

        return redirect()
            ->route('admin.presenca.index')
            ->with('success', 'Presença registrada com sucesso!');
    }

    /**
     * Alunos em curso com matrícula ATIVA no curso da aula
     * (e no nível do módulo, quando a aula tem módulo).
     */
    private function alunosDaAula(Aula $aula)
    {
        return Aluno::whereHas('matriculas', function ($q) use ($aula) {
                $q->where('id_curso', $aula->id_curso)
                  ->where('status_matricula', 'ATIVO');

                if ($aula->modulo) {
                    $q->where('id_nivel', $aula->modulo->id_nivel);
                }
            })
            ->where('status_aluno', 'EM CURSO')
            ->orderBy('nome_aluno')
            ->get();
    }
}
