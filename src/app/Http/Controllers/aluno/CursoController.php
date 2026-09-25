<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Support\CursoAtual;
use App\Support\ModuloProgresso;

class CursoController extends Controller
{
    public function index()
    {
        $matriculaAtual = CursoAtual::matricula();

        $modulos = ModuloProgresso::paraMatricula($matriculaAtual);
        $percentualGeral = ModuloProgresso::percentualGeral($modulos);
        $totalMinutos = $modulos->sum('carga_horaria_minutos');
        $totalAulas = $modulos->sum('aulas_count');

        return view('aluno.curso.index', compact(
            'matriculaAtual', 'modulos', 'totalMinutos', 'totalAulas', 'percentualGeral'
        ));
    }
}
