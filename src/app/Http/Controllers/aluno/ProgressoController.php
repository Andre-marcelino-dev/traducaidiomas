<?php
namespace App\Http\Controllers\aluno;
use App\Http\Controllers\Controller;
use App\Models\Presenca;
use App\Models\Materiais;
use App\Models\Aula;
use App\Support\CursoAtual;
use Illuminate\Support\Facades\DB;
class ProgressoController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();

        // Presença (a tela de presença grava "presente", "falta" ou "justificado")
        $totalAulas     = Presenca::where('id_aluno', $aluno->id_aluno)->count();
        $totalPresente  = Presenca::where('id_aluno', $aluno->id_aluno)->where('status_presenca', 'presente')->count();
        $totalFalta     = Presenca::where('id_aluno', $aluno->id_aluno)->where('status_presenca', 'falta')->count();
        $percPresenca   = $totalAulas > 0 ? round(($totalPresente / $totalAulas) * 100) : 0;

        // Materiais do curso/nível escolhido
        $idsMateriais = CursoAtual::filtrar(Materiais::query(), CursoAtual::matricula())->pluck('id_materiais');

        $totalMateriais = $idsMateriais->count();
        $materiaisVistos = DB::table('tbl_progresso_materiais')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('status_progresso', 'CONCLUIDO')
            ->whereIn('id_materiais', $idsMateriais)
            ->count();
        $percMateriais = $totalMateriais > 0 ? round(($materiaisVistos / $totalMateriais) * 100) : 0;

        // Últimas presenças
        $ultimasPresencas = Presenca::with('aula', 'ultimaJustificativa')
            ->where('id_aluno', $aluno->id_aluno)
            ->orderBy('data_registro_presenca', 'desc')
            ->limit(5)
            ->get();

        return view('aluno.dash.progresso', compact(
            'aluno', 'totalAulas', 'totalPresente', 'totalFalta',
            'percPresenca', 'totalMateriais', 'materiaisVistos',
            'percMateriais', 'ultimasPresencas'
        ));
    }
}
