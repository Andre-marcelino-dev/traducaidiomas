<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\Notificacao;
use App\Models\Reagendamento;

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

        // Busca notificações não lidas do aluno
        $notificacoes = Notificacao::where('id_aluno', $aluno->id_aluno)
            ->where('lida_notificacoes', 0)
            ->orderByDesc('data_criacao_notificacoes')
            ->get();

        // Busca reagendamentos pendentes e confirmados do aluno
        $reagendamentos = Reagendamento::with('aula')
            ->where('aluno_id', $aluno->id_aluno)
            ->whereIn('status', ['pendente', 'confirmado'])
            ->orderByDesc('created_at')
            ->get();

        return view('aluno.dash.index', compact('aluno', 'aulas', 'totalAulas', 'notificacoes', 'reagendamentos'));
    }
}
