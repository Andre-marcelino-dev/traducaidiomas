<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JustificativaFalta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JustificativaController extends Controller
{
    const STATUS = ['pendente', 'aceita', 'recusada'];

    public function index(Request $request)
    {
        $professor = auth('admin')->user();
        $filtro = in_array($request->get('status'), self::STATUS, true) ? $request->get('status') : 'pendente';

        $justificativas = JustificativaFalta::visivelPara($professor)
            ->with('presenca.aluno', 'presenca.aula')
            ->where('status_justificativa', $filtro)
            ->latest('id_justificativa')
            ->paginate(15)
            ->withQueryString();

        $totais = JustificativaFalta::visivelPara($professor)
            ->selectRaw('status_justificativa, COUNT(*) as total')
            ->groupBy('status_justificativa')
            ->pluck('total', 'status_justificativa');

        return view('admin.justificativas.index', compact('justificativas', 'totais', 'filtro'));
    }

    public function aceitar($id)
    {
        $justificativa = $this->pendenteDoProfessor($id);

        // O professor pode ter mudado a chamada depois que o aluno justificou.
        if (!$justificativa->presenca->ehFalta()) {
            return back()->with('error', 'Essa aula não está mais marcada como falta para o aluno.');
        }

        DB::transaction(function () use ($justificativa) {
            $justificativa->update(['status_justificativa' => 'aceita', 'respondido_em' => now()]);
            $justificativa->presenca->update(['status_presenca' => 'justificado']);
        });

        return back()->with('success', 'Justificativa aceita. A falta passou para "justificado".');
    }

    public function recusar(Request $request, $id)
    {
        $justificativa = $this->pendenteDoProfessor($id);

        $request->validate(['resposta_professor' => 'nullable|string|max:500']);

        $justificativa->update([
            'status_justificativa' => 'recusada',
            'resposta_professor'   => $request->resposta_professor,
            'respondido_em'        => now(),
        ]);

        return back()->with('success', 'Justificativa recusada. A falta foi mantida.');
    }

    private function pendenteDoProfessor($id): JustificativaFalta
    {
        return JustificativaFalta::visivelPara(auth('admin')->user())
            ->pendentes()
            ->with('presenca')
            ->findOrFail($id);
    }
}
