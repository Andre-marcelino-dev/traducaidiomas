<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\Materiais;

class AulaController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();

        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)
            ->pluck('id_curso');

        $aulas = Aula::with('professor')
            ->whereIn('id_curso', $idCursos)
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->get();

        if ($aulas->isEmpty()) {
            $aulas = Aula::with('professor')
                ->orderBy('data_aulas')
                ->orderBy('hora_aulas')
                ->get();
        }

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

        $materiais = Materiais::whereIn('id_curso', $idCursos)
            ->orderByDesc('id_materiais')
            ->get();
        return view('aluno.aulas.index', compact('aulas', 'proximaAula', 'materiais', 'aluno'));
    }
}


