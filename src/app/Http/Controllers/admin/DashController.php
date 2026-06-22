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

        // Presença
        $presencaPresentes = Presenca::where('status_presenca', 'presente')->count();
        $presencaAusentes  = Presenca::where('status_presenca', 'ausente')->count();
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

        // Distribuição de notas
        $notasFaixas = [
            '0–4'  => AtividadeResposta::whereBetween('nota', [0, 4])->count(),
            '5–6'  => AtividadeResposta::whereBetween('nota', [5, 6])->count(),
            '7–8'  => AtividadeResposta::whereBetween('nota', [7, 8])->count(),
            '9–10' => AtividadeResposta::whereBetween('nota', [9, 10])->count(),
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
            'topAlunos'
        ));
    }
}
