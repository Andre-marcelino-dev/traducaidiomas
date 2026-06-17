<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Presenca;
use Illuminate\Http\Request;

class PresencaController extends Controller
{
public function index()
{
    $aulas = Aula::with('professor')->orderBy('data_aulas', 'desc')->get();

    foreach ($aulas as $aula) {
        $registros = Presenca::where('id_aulas', $aula->id_aulas)
            ->where('data_registro_presenca', date('Y-m-d'))
            ->get();

        $aula->presentes = $registros->where('status_presenca', 'presente')->count();
        $aula->faltas = $registros->where('status_presenca', 'falta')->count();
        $aula->justificados = $registros->where('status_presenca', 'justificado')->count();
    }

    $registrosHoje = Presenca::with('aluno', 'aula')
        ->where('data_registro_presenca', date('Y-m-d'))
        ->get();

    return view('admin.presenca.index', compact('aulas', 'registrosHoje'));
}

    public function alunos($id_aulas)
    {
        $aula = Aula::findOrFail($id_aulas);

        $alunos = Aluno::whereHas('matriculas', function ($q) use ($aula) {
                $q->where('id_curso', $aula->id_curso)
                  ->where('status_matricula', '!=', 'CONGELADO');
            })
            ->where('status_aluno', 'EM CURSO')
            ->get();

        $presencas = Presenca::where('id_aulas', $id_aulas)
            ->where('data_registro_presenca', date('Y-m-d'))
            ->get()
            ->keyBy('id_aluno');

        return view('admin.presenca.alunos', compact('aula', 'alunos', 'presencas'));
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
            ->route('admin.presenca.index')
            ->with('success', 'Presença registrada com sucesso!');
    }
}
