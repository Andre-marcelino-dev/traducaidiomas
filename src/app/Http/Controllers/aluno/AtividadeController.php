<?php
namespace App\Http\Controllers\aluno;
use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\AtividadeQuestao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AtividadeController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();
        $matricula = DB::table('tbl_matricula')->where('id_aluno', $aluno->id_aluno)->first();
        $idCurso = $matricula ? $matricula->id_curso : null;

        $atividades = Atividade::with(['respostas' => function($q) use ($aluno) {
            $q->where('id_aluno', $aluno->id_aluno);
        }])->where('id_curso', $idCurso)->where('status_atividade', 'ATIVA')->orderBy('data_entrega')->get();

        $pendentes  = $atividades->filter(fn($a) => $a->respostas->isEmpty() || $a->respostas->first()->status_resposta == 'PENDENTE')->count();
        $concluidas = $atividades->filter(fn($a) => $a->respostas->isNotEmpty() && in_array($a->respostas->first()->status_resposta, ['ENVIADA','CORRIGIDA']))->count();

        return view('aluno.atividades.index', compact('atividades', 'aluno', 'pendentes', 'concluidas'));
    }

    public function show($id)
    {
        $aluno = auth('aluno')->user();
        $atividade = Atividade::with('questoes')->findOrFail($id);
        $resposta = AtividadeResposta::with('respostasQuestoes')
            ->where('id_atividade', $id)
            ->where('id_aluno', $aluno->id_aluno)
            ->first();
        return view('aluno.atividades.show', compact('atividade', 'aluno', 'resposta'));
    }

    public function responder(Request $request, $id)
    {
        $aluno = auth('aluno')->user();
        $atividade = Atividade::with('questoes')->findOrFail($id);

        $resposta = AtividadeResposta::firstOrCreate(
            ['id_atividade' => $id, 'id_aluno' => $aluno->id_aluno],
            ['status_resposta' => 'PENDENTE']
        );

        $resposta->update(['status_resposta' => 'ENVIADA', 'data_envio' => now()]);
        $resposta->respostasQuestoes()->delete();

        foreach ($atividade->questoes as $questao) {
            $respostaAluno = $request->input('questao_' . $questao->id_questao);
            $correta = null;
            if ($questao->tipo_questao === 'multipla_escolha') {
                $correta = $respostaAluno === $questao->resposta_correta ? 1 : 0;
            }
            AtividadeRespostaQuestao::create([
                'id_resposta'   => $resposta->id_resposta,
                'id_questao'    => $questao->id_questao,
                'resposta_aluno'=> $respostaAluno,
                'correta'       => $correta,
            ]);
        }

        return redirect()->route('aluno.atividades.index')->with('success', 'Atividade enviada com sucesso!');
    }
}
