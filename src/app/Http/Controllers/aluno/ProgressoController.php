<?php
namespace App\Http\Controllers\aluno;
use App\Http\Controllers\Controller;
use App\Models\Presenca;
use App\Models\Materiais;
use App\Models\Aula;
use Illuminate\Support\Facades\DB;
class ProgressoController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();

        // Presença
        $totalAulas     = Presenca::where('id_aluno', $aluno->id_aluno)->count();
        $totalPresente  = Presenca::where('id_aluno', $aluno->id_aluno)->where('status_presenca', 'PRESENTE')->count();
        $totalFalta     = Presenca::where('id_aluno', $aluno->id_aluno)->where('status_presenca', 'FALTA')->count();
        $percPresenca   = $totalAulas > 0 ? round(($totalPresente / $totalAulas) * 100) : 0;

        // Materiais
        $matricula = DB::table('tbl_matricula')->where('id_aluno', $aluno->id_aluno)->first();
        $idCurso = $matricula ? $matricula->id_curso : null;
        $totalMateriais = $idCurso ? Materiais::where('id_curso', $idCurso)->count() : 0;
        $materiaisVistos = DB::table('tbl_progresso_materiais')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('status_progresso', 'CONCLUIDO')
            ->count();
        $percMateriais = $totalMateriais > 0 ? round(($materiaisVistos / $totalMateriais) * 100) : 0;

        // Últimas presenças
        $ultimasPresencas = Presenca::with('aula')
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
