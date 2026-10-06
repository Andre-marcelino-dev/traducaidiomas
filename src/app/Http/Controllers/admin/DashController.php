<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\Aluno;
use App\Models\Presenca;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\AtividadeResposta;
use App\Models\Reagendamento;
use App\Models\JustificativaFalta;
use Illuminate\Support\Facades\DB;

class DashController extends Controller
{
    public function index()
    {
        $professor           = auth('admin')->user();
        $totalProfessores    = Professor::count();
        $professoresRecentes = Professor::orderBy('criado_em_professor', 'desc')->take(5)->get();
        $totalAlunos         = Aluno::count();
        $alunosAtivos        = Aluno::where('status_aluno', 'EM CURSO')->count();
        $totalAulas          = Aula::count();
        $matriculasAtivas    = Matricula::where('status_matricula', '!=', 'CONGELADO')->count();

        // Presença (a tela de presença grava "presente", "falta" ou "justificado")
        $presencaPresentes = Presenca::where('status_presenca', 'presente')->count();
        $presencaAusentes  = Presenca::where('status_presenca', 'falta')->count();
        $taxaPresenca      = ($presencaPresentes + $presencaAusentes) > 0
            ? round($presencaPresentes / ($presencaPresentes + $presencaAusentes) * 100)
            : 0;

        // Aulas de hoje
        $aulasHoje = Aula::whereDate('data_aulas', now()->toDateString())
            ->orderBy('hora_aulas')
            ->with('professor')
            ->get();

        // Aulas por mês (últimos 6 meses)
        $aulasPorMes = Aula::selectRaw("DATE_FORMAT(data_aulas, '%Y-%m') as mes, COUNT(*) as total")
            ->whereNotNull('data_aulas')
            ->whereRaw("data_aulas >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Distribuição de notas (a nota aceita meio ponto, ex.: 4,5 entra em "0–4")
        $notasFaixas = [
            '0–4'  => AtividadeResposta::where('nota', '>=', 0)->where('nota', '<', 5)->count(),
            '5–6'  => AtividadeResposta::where('nota', '>=', 5)->where('nota', '<', 7)->count(),
            '7–8'  => AtividadeResposta::where('nota', '>=', 7)->where('nota', '<', 9)->count(),
            '9–10' => AtividadeResposta::where('nota', '>=', 9)->where('nota', '<=', 10)->count(),
        ];

        // Alunos por nível
        $alunosPorNivel = Aluno::selectRaw('nivel_aluno, COUNT(*) as total')
            ->groupBy('nivel_aluno')
            ->pluck('total', 'nivel_aluno');

        // Alunos por curso (via matrículas)
        $alunosPorCurso = DB::table('tbl_matricula')
            ->join('tbl_cursos', 'tbl_matricula.id_curso', '=', 'tbl_cursos.id_curso')
            ->selectRaw('tbl_cursos.nome_curso, COUNT(*) as total')
            ->groupBy('tbl_cursos.id_curso', 'tbl_cursos.nome_curso')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // Alunos recentes
        $alunosRecentes = Aluno::orderBy('id_aluno', 'desc')->take(6)->get();

        // Próximas aulas (excluindo hoje)
        $proximasAulas = Aula::where('data_aulas', '>', now()->toDateString())
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->take(5)
            ->get();

        // Reagendamentos pendentes
        $reagendamentosPendentes      = Reagendamento::with(['aluno', 'aula'])
            ->where('status', 'pendente')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $totalReagendamentosPendentes = Reagendamento::where('status', 'pendente')->count();
        $totalJustificativasPendentes = JustificativaFalta::visivelPara($professor)->pendentes()->count();

        // Ranking de notas
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
            'alunosAtivos',
            'totalAulas',
            'matriculasAtivas',
            'presencaPresentes',
            'presencaAusentes',
            'taxaPresenca',
            'aulasHoje',
            'aulasPorMes',
            'notasFaixas',
            'alunosPorNivel',
            'alunosPorCurso',
            'alunosRecentes',
            'proximasAulas',
            'reagendamentosPendentes',
            'totalReagendamentosPendentes',
            'totalJustificativasPendentes',
            'topAlunos'
        ));
    }
}
