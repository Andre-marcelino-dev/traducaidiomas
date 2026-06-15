<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Presenca;
use Illuminate\Http\Request;

class ReagendamentoController extends Controller
{
    public function index()
    {
        $aulas = Aula::with('professor')->orderBy('data_aulas', 'desc')->get();
        return view('admin.reagendamentos.index', compact('aulas'));
    }

    public function alunos($id_aulas)
    {
        $aula = Aula::findOrFail($id_aulas);

        $alunos = Aluno::where('curso_aluno', $aula->cursos_aulas)
            ->where('status_aluno', 'EM CURSO')
            ->get();

        $presencas = Presenca::where('id_aulas', $id_aulas)
            ->where('data_registro_presenca', date('Y-m-d'))
            ->get()
            ->keyBy('id_aluno');

        return view('admin.reagendamentos.alunos', compact('aula', 'alunos', 'presencas'));
    }

    public function salvar(Request $request)
    {
        foreach ($request->presencas as $id_aluno => $status) {
            Presenca::updateOrCreate(
                [
                    'id_aulas' => $request->id_aulas,
                    'id_aluno' => $id_aluno,
                    'data_registro_presenca' => date('Y-m-d'),
                ],
                ['status_presenca' => $status]
            );
        }

        return redirect()
            ->route('admin.reagendamentos.index')
            ->with('success', 'Presença registrada com sucesso!');
    }
}
