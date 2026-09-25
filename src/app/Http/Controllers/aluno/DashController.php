<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Feedback;
use App\Models\Notificacao;
use App\Models\Reagendamento;
use App\Support\CursoAtual;

class DashController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();
        $matriculaAtual = CursoAtual::matricula();

        // Aulas do curso/nível escolhido
        $aulas = CursoAtual::filtrar(Aula::query(), $matriculaAtual)->get();

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

        $matriculas = collect([$matriculaAtual]);

        foreach ($matriculas as $mat) {
            $aulaProf = Aula::where('id_curso', $mat->id_curso)->whereNotNull('id_professor')->first();
            $mat->professor_id = $aulaProf?->id_professor;
            $mat->professor_nome = $aulaProf?->professor?->nome_professor ?? 'Professor';
        }

        $feedbacks = Feedback::where('id_aluno', $aluno->id_aluno)
            ->get()
            ->keyBy('id_curso');

        return view('aluno.dash.index', compact('aluno', 'aulas', 'totalAulas', 'notificacoes', 'reagendamentos', 'matriculas', 'feedbacks'));
    }
}
