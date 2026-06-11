<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Matricula;

class DashController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();

        // Busca os cursos do aluno
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)
            ->pluck('id_curso');

        // Busca aulas dos cursos do aluno, ou todas se não houver correspondência
        $aulas = Aula::whereIn('id_curso', $idCursos)->get();

        if ($aulas->isEmpty()) {
            $aulas = Aula::all();
        }

        $totalAulas = $aulas->count();

        return view('aluno.dash.index', compact('aluno', 'aulas', 'totalAulas'));
    }
}