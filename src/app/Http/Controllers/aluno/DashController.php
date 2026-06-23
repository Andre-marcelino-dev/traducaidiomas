<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
<<<<<<< HEAD
=======
use App\Models\Feedback;
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
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

<<<<<<< HEAD
        return view('aluno.dash.index', compact('aluno', 'aulas', 'totalAulas', 'notificacoes', 'reagendamentos'));
=======
        $matriculas = Matricula::with('curso')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('status_matricula', 'ATIVO')
            ->get();

        foreach ($matriculas as $mat) {
            $aulaProf = Aula::where('id_curso', $mat->id_curso)->whereNotNull('id_professor')->first();
            $mat->professor_id = $aulaProf?->id_professor;
            $mat->professor_nome = $aulaProf?->professor?->nome_professor ?? 'Professor';
        }

        $feedbacks = Feedback::where('id_aluno', $aluno->id_aluno)
            ->get()
            ->keyBy('id_curso');

        return view('aluno.dash.index', compact('aluno', 'aulas', 'totalAulas', 'notificacoes', 'reagendamentos', 'matriculas', 'feedbacks'));
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
    }
}
