<?php
namespace App\Http\Controllers\aluno;
use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\AtividadeQuestao;
use App\Support\CursoAtual;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
class AtividadeController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();
        $matriculaAtual = CursoAtual::matricula();

        $atividades = CursoAtual::filtrar(Atividade::with(['curso', 'professor', 'respostas' => function($q) use ($aluno) {
            $q->where('id_aluno', $aluno->id_aluno);
        }]), $matriculaAtual)->where('status_atividade', 'ATIVA')->orderBy('data_entrega')->get();

        $pendentes  = $atividades->filter(fn($a) => $a->respostas->isEmpty() || $a->respostas->first()->status_resposta == 'PENDENTE')->count();
        $concluidas = $atividades->filter(fn($a) => $a->respostas->isNotEmpty() && in_array($a->respostas->first()->status_resposta, ['ENVIADA','CORRIGIDA']))->count();

        return view('aluno.atividades.index', compact('atividades', 'aluno', 'pendentes', 'concluidas'));
    }

    public function show($id)
    {
        $aluno = auth('aluno')->user();
        $atividade = $this->atividadeDoAluno($id);
        $resposta = AtividadeResposta::with('respostasQuestoes')
            ->where('id_atividade', $id)
            ->where('id_aluno', $aluno->id_aluno)
            ->first();
        return view('aluno.atividades.show', compact('atividade', 'aluno', 'resposta'));
    }

    public function audio($id)
    {
        $atividade = $this->atividadeDoAluno($id);
        $caminho = public_path((string) $atividade->arquivo_audio);
        abort_unless($atividade->arquivo_audio && is_file($caminho), 404);

        return response()->file($caminho);
    }

    /**
     * Devolve a gravação que o próprio aluno enviou (pra ele ouvir de novo).
     */
    public function audioResposta($id, $idQuestao)
    {
        $atividade = $this->atividadeDoAluno($id);
        $resposta = AtividadeResposta::where('id_atividade', $atividade->id_atividade)
            ->where('id_aluno', auth('aluno')->id())
            ->first();
        $rq = $resposta?->respostasQuestoes()->where('id_questao', $idQuestao)->first();
        $caminho = $rq?->caminhoGravacao();
        abort_unless($caminho, 404);

        return response()->file($caminho);
    }

    public function responder(Request $request, $id)
    {
        $aluno = auth('aluno')->user();
        $atividade = $this->atividadeDoAluno($id);
        $questoesAudio = $atividade->questoes->where('tipo_questao', 'audio');

        // Mesmas regras do app (Api\V1\Aluno\AtividadeController::responderAudio).
        $request->validate(
            $questoesAudio->mapWithKeys(fn ($q) => [
                'audio_' . $q->id_questao => 'nullable|file|mimes:' . implode(',', Upload::AUDIOS) . '|max:20480',
            ])->all(),
            [
                'mimes'    => 'Áudio inválido. Tente gravar de novo.',
                'max'      => 'O áudio pode ter no máximo 20 MB.',
                'uploaded' => 'Não foi possível enviar o áudio. Tente gravar de novo.',
            ]
        );

        $resposta = AtividadeResposta::firstOrCreate(
            ['id_atividade' => $atividade->id_atividade, 'id_aluno' => $aluno->id_aluno],
            ['status_resposta' => 'PENDENTE']
        );

        // Depois de corrigida, reenviar apagaria as respostas que o professor avaliou.
        if ($resposta->status_resposta === 'CORRIGIDA') {
            return redirect()->route('aluno.atividades.show', $atividade->id_atividade)
                ->with('error', 'Esta atividade já foi corrigida e não pode ser reenviada.');
        }

        // Toda questão de áudio precisa de gravação: enviada agora ou antes (pelo app).
        $jaGravadas = $resposta->respostasQuestoes()
            ->whereIn('id_questao', $questoesAudio->pluck('id_questao'))
            ->whereNotNull('resposta_aluno')
            ->pluck('id_questao');

        foreach ($questoesAudio as $questao) {
            if (!$request->hasFile('audio_' . $questao->id_questao) && !$jaGravadas->contains($questao->id_questao)) {
                $numero = $atividade->questoes->values()->search(fn ($q) => $q->id_questao === $questao->id_questao) + 1;

                return back()->withInput()->with('error', "Grave sua resposta da questão {$numero} antes de enviar.");
            }
        }

        foreach ($questoesAudio as $questao) {
            if ($request->hasFile('audio_' . $questao->id_questao)) {
                $this->salvarGravacao($resposta, $questao->id_questao, $request->file('audio_' . $questao->id_questao));
            }
        }

        $resposta->update(['status_resposta' => 'ENVIADA', 'data_envio' => now()]);

        // Gravações ficam de fora: as que já existem (inclusive feitas pelo app) são preservadas.
        $resposta->respostasQuestoes()->whereNotIn('id_questao', $questoesAudio->pluck('id_questao'))->delete();

        foreach ($atividade->questoes as $questao) {
            if ($questao->tipo_questao === 'audio') {
                continue;
            }
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

    /**
     * Salva (ou substitui) a gravação de uma questão e apaga o arquivo anterior.
     */
    private function salvarGravacao(AtividadeResposta $resposta, int $idQuestao, UploadedFile $arquivo): void
    {
        $rq = AtividadeRespostaQuestao::firstOrNew([
            'id_resposta' => $resposta->id_resposta,
            'id_questao'  => $idQuestao,
        ]);

        $antigo = $rq->exists ? $rq->caminhoGravacao() : null;

        $rq->resposta_aluno = AtividadeRespostaQuestao::PASTA_GRAVACOES
            . Upload::salvar($arquivo, 'atividades-respostas', Upload::AUDIOS, 'resposta');
        $rq->save();

        if ($antigo) {
            @unlink($antigo);
        }
    }

    /**
     * Só atividades ativas do curso/nível escolhido; senão 404.
     */
    private function atividadeDoAluno($id): Atividade
    {
        return CursoAtual::filtrar(Atividade::with('questoes'), CursoAtual::matricula())
            ->where('status_atividade', 'ATIVA')
            ->findOrFail($id);
    }
}
