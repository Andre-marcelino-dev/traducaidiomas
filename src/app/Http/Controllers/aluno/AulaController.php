<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Materiais;
use App\Support\CursoAtual;

class AulaController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();
        $matriculaAtual = CursoAtual::matricula();

        $aulas = CursoAtual::filtrar(Aula::with(['professor', 'modulo']), $matriculaAtual)
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->get();

        $agora = now();

        $proximaAula = $aulas->filter(function ($aula) use ($agora) {
            if (!$aula->data_aulas || !$aula->hora_aulas) {
                return false;
            }
            $dataHora = \Carbon\Carbon::parse($aula->data_aulas . ' ' . $aula->hora_aulas);
            return $dataHora->greaterThanOrEqualTo($agora);
        })->sortBy(function ($aula) {
            return \Carbon\Carbon::parse($aula->data_aulas . ' ' . $aula->hora_aulas);
        })->first();

        $materiais = CursoAtual::filtrar(Materiais::with('modulo'), $matriculaAtual)
            ->orderByDesc('id_materiais')
            ->get();
        return view('aluno.aulas.index', compact('aulas', 'proximaAula', 'materiais', 'aluno'));
    }
}


