<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\Matricula;
use App\Support\CursoAtual;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Atividades do traduca-APP. Mesma regra do site (aluno/AtividadeController),
 * adaptada pra não depender da matrícula "atual" da sessão: vale para todos
 * os cursos com matrícula ativa do aluno (como Agenda e Reagendamento).
 */
class AtividadeController extends Controller
{
    /** Status de resposta que contam a atividade como concluída. */
    const CONCLUI = ['ENVIADA', 'CORRIGIDA'];

    /**
     * Tela "Atividades": lista + resumo (concluídas / total).
     */
    public function index(Request $request): JsonResponse
    {
        $idAluno = (int) $request->user()->id_aluno;

        $atividades = $this->atividadesDoAluno($idAluno)
            ->map(fn (Atividade $a) => $this->resumo($a, $a->respostas->first()))
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'total'      => $atividades->count(),
                'concluidas' => $atividades->where('concluida', true)->count(),
                'pendentes'  => $atividades->where('concluida', false)->count(),
                'atividades' => $atividades,
            ],
        ]);
    }

    /**
     * Abrir uma atividade: questões (sem a resposta certa) e a resposta do aluno.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $idAluno = (int) $request->user()->id_aluno;
        $atividade = $this->atividadeDoAluno($idAluno, $id);

        if (!$atividade) {
            return $this->naoEncontrada();
        }

        $resposta = $atividade->respostas->first();
        $porQuestao = $resposta ? $resposta->respostasQuestoes->keyBy('id_questao') : collect();

        $questoes = $atividade->questoes->sortBy([['ordem', 'asc'], ['id_questao', 'asc']])->values()
            ->map(function ($q, int $i) use ($porQuestao) {
                $rq = $porQuestao[$q->id_questao] ?? null;

                return [
                    'id_questao' => $q->id_questao,
                    'numero'     => $i + 1,
                    'enunciado'  => $q->enunciado,
                    'tipo'       => $q->tipo_questao, // multipla_escolha | texto
                    'opcoes'     => $q->tipo_questao === 'multipla_escolha'
                        ? collect(['A' => $q->opcao_a, 'B' => $q->opcao_b, 'C' => $q->opcao_c, 'D' => $q->opcao_d])
                            ->filter(fn ($texto) => filled($texto))
                            ->map(fn ($texto, $letra) => ['letra' => $letra, 'texto' => $texto])
                            ->values()
                        : [],
                    'resposta_aluno' => $rq?->resposta_aluno,
                    // Igual ao site: só diz se acertou (null = questão de texto ou não enviada).
                    'correta' => $rq && $rq->correta !== null ? (bool) $rq->correta : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $this->resumo($atividade, $resposta) + [
                'feedback_professor' => $resposta?->feedback_professor,
                'data_envio'         => $resposta?->data_envio ? substr((string) $resposta->data_envio, 0, 16) : null,
                'pode_responder'     => $resposta?->status_resposta !== 'CORRIGIDA',
                'questoes'           => $questoes,
            ],
        ]);
    }

    /**
     * Áudio da atividade (precisa do token; a pasta é bloqueada para acesso direto).
     */
    public function audio(Request $request, int $id)
    {
        $atividade = $this->atividadeDoAluno((int) $request->user()->id_aluno, $id);
        $caminho = $atividade ? public_path((string) $atividade->arquivo_audio) : null;

        if (!$atividade || !$atividade->arquivo_audio || !is_file($caminho)) {
            return $this->naoEncontrada('Áudio não encontrado.');
        }

        return response()->file($caminho);
    }

    /**
     * Enviar respostas. Corpo: { "respostas": { "<id_questao>": "A" | "texto" } }.
     */
    public function responder(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'respostas'   => 'required|array',
            'respostas.*' => 'nullable|string|max:5000',
        ], [
            'respostas.required' => 'Responda as questões antes de enviar.',
        ]);

        $idAluno = (int) $request->user()->id_aluno;
        $atividade = $this->atividadeDoAluno($idAluno, $id);

        if (!$atividade) {
            return $this->naoEncontrada();
        }

        $resposta = AtividadeResposta::firstOrCreate(
            ['id_atividade' => $atividade->id_atividade, 'id_aluno' => $idAluno],
            ['status_resposta' => 'PENDENTE']
        );

        // Depois de corrigida, reenviar apagaria as respostas que o professor avaliou.
        if ($resposta->status_resposta === 'CORRIGIDA') {
            return response()->json([
                'success' => false,
                'message' => 'Esta atividade já foi corrigida e não pode ser reenviada.',
            ], Response::HTTP_CONFLICT);
        }

        $respostas = $request->input('respostas', []);

        $resposta->update(['status_resposta' => 'ENVIADA', 'data_envio' => now()]);
        $resposta->respostasQuestoes()->delete();

        foreach ($atividade->questoes as $questao) {
            $respostaAluno = $respostas[$questao->id_questao] ?? null;
            $correta = null;
            if ($questao->tipo_questao === 'multipla_escolha') {
                $correta = $respostaAluno === $questao->resposta_correta ? 1 : 0;
            }
            AtividadeRespostaQuestao::create([
                'id_resposta'    => $resposta->id_resposta,
                'id_questao'     => $questao->id_questao,
                'resposta_aluno' => $respostaAluno,
                'correta'        => $correta,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Atividade enviada com sucesso!',
        ]);
    }

    /**
     * Campos do card (lista) — também usados no topo da tela da atividade.
     */
    private function resumo(Atividade $a, ?AtividadeResposta $resposta): array
    {
        $status = $resposta?->status_resposta;
        $categoria = $a->categoriaInfo();

        return [
            'id_atividade' => $a->id_atividade,
            'titulo'       => $a->titulo_atividade,
            'descricao'    => $a->descricao_atividade,
            'categoria'    => $categoria ? ['codigo' => $a->categoria_atividade] + $categoria : null,
            'finalidade'   => $a->finalidadeLabel(),
            'id_curso'     => $a->id_curso,
            'curso'        => $a->curso?->nome_curso,
            'professor'    => $a->professor?->nome_professor,
            'data_entrega' => $a->data_entrega ? substr((string) $a->data_entrega, 0, 10) : null,
            'tem_audio'    => (bool) $a->arquivo_audio,
            'url_audio'    => $a->arquivo_audio ? route('api.aluno.atividades.audio', $a->id_atividade) : null,
            'extensao_audio' => $a->arquivo_audio ? strtolower(pathinfo($a->arquivo_audio, PATHINFO_EXTENSION)) : null,
            'total_questoes' => $a->questoes->count(),
            // pendente | enviada | corrigida
            'status'       => in_array($status, self::CONCLUI, true) ? strtolower($status) : 'pendente',
            'concluida'    => in_array($status, self::CONCLUI, true),
            'nota'         => $status === 'CORRIGIDA' && $resposta->nota !== null ? (float) $resposta->nota : null,
        ];
    }

    /**
     * Atividades ativas de todos os cursos com matrícula ativa, por data de entrega.
     */
    private function atividadesDoAluno(int $idAluno): Collection
    {
        return Matricula::where('id_aluno', $idAluno)
            ->where('status_matricula', 'ATIVO')
            ->get()
            ->flatMap(fn (Matricula $m) => $this->consulta($idAluno, $m)->get())
            ->unique('id_atividade')
            ->sortBy(fn (Atividade $a) => [$a->data_entrega === null ? 1 : 0, (string) $a->data_entrega, $a->id_atividade])
            ->values();
    }

    /**
     * A atividade, se for de algum curso com matrícula ativa do aluno.
     */
    private function atividadeDoAluno(int $idAluno, int $id): ?Atividade
    {
        $matriculas = Matricula::where('id_aluno', $idAluno)
            ->where('status_matricula', 'ATIVO')
            ->get();

        foreach ($matriculas as $matricula) {
            $atividade = $this->consulta($idAluno, $matricula)->find($id);
            if ($atividade) {
                return $atividade;
            }
        }

        return null;
    }

    private function consulta(int $idAluno, Matricula $matricula)
    {
        return CursoAtual::filtrar(Atividade::with([
            'curso', 'professor', 'questoes',
            'respostas' => fn ($q) => $q->where('id_aluno', $idAluno)->with('respostasQuestoes'),
        ]), $matricula)->where('status_atividade', 'ATIVA');
    }

    private function naoEncontrada(string $mensagem = 'Atividade não encontrada.'): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $mensagem], Response::HTTP_NOT_FOUND);
    }
}
