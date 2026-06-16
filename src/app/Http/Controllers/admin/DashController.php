<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\Aluno;
use App\Models\Presenca;
use App\Models\Aula;
use App\Models\AtividadeResposta;
use App\Models\Reagendamento;

class DashController extends Controller
{
    public function index()
    {
        $professor           = auth('admin')->user();
        $totalProfessores    = Professor::count();
        $professoresRecentes = Professor::orderBy('criado_em_professor', 'desc')->take(5)->get();
        $totalAlunos         = Aluno::count();
        $totalAulas          = Aula::count();

        // Gráfico 1 – Presença geral (presentes vs ausentes)
        $presencaPresentes = Presenca::where('status_presenca', 'presente')->count();
        $presencaAusentes  = Presenca::where('status_presenca', 'ausente')->count();

        // Gráfico 2 – Aulas por mês (últimos 6 meses)
        $aulasPorMes = Aula::selectRaw("DATE_FORMAT(data_aulas, '%Y-%m') as mes, COUNT(*) as total")
            ->whereNotNull('data_aulas')
            ->whereRaw("data_aulas >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Gráfico 3 – Distribuição de notas (faixas 0-4, 5-6, 7-8, 9-10)
        $notasFaixas = [
            '0–4'   => AtividadeResposta::whereBetween('nota', [0, 4])->count(),
            '5–6'   => AtividadeResposta::whereBetween('nota', [5, 6])->count(),
            '7–8'   => AtividadeResposta::whereBetween('nota', [7, 8])->count(),
            '9–10'  => AtividadeResposta::whereBetween('nota', [9, 10])->count(),
        ];

        // Gráfico 4 – Alunos por nível
        $alunosPorNivel = Aluno::selectRaw('nivel_aluno, COUNT(*) as total')
            ->groupBy('nivel_aluno')
            ->pluck('total', 'nivel_aluno');

        // Tabela – Alunos recentes
        $alunosRecentes = Aluno::orderBy('id_aluno', 'desc')->take(6)->get();

        // Lista – Próximas aulas agendadas
        $proximasAulas = Aula::where('data_aulas', '>=', now()->toDateString())
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->take(5)
            ->get();

        // Reagendamentos pendentes (mais recentes primeiro)
        $reagendamentosPendentes = Reagendamento::with(['aluno', 'aula'])
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top 5 alunos por média de notas
        $topAlunos = AtividadeResposta::selectRaw('id_aluno, AVG(nota) as media, COUNT(*) as total_atividades')
            ->whereNotNull('nota')
            ->groupBy('id_aluno')
            ->orderByDesc('media')
            ->take(5)
            ->with('aluno')
            ->get();

        return view('admin.dash.dashboard', compact(
            'professor',
            'totalProfessores',
            'professoresRecentes',
            'totalAlunos',
            'totalAulas',
            'presencaPresentes',
            'presencaAusentes',
            'aulasPorMes',
            'notasFaixas',
            'alunosPorNivel',
            'alunosRecentes',
            'proximasAulas',
            'reagendamentosPendentes',
            'topAlunos'
        ));
    }
}