<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Materiais;
use App\Models\Matricula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private function obterDadosDoAluno(): array
    {
        $aluno = auth('aluno')->user();

        if (!$aluno) {
            return ['aulas' => [], 'materiais' => []];
        }

        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');

        $aulas = Aula::with('professor')
            ->whereIn('id_curso', $idCursos)
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->take(5)
            ->get();

        if ($aulas->isEmpty()) {
            $aulas = Aula::with('professor')
                ->orderBy('data_aulas')
                ->orderBy('hora_aulas')
                ->take(5)
                ->get();
        }

        $materiais = Materiais::with('curso')
            ->whereIn('id_curso', $idCursos)
            ->orderByDesc('criado_em_materiais')
            ->take(5)
            ->get();

        return [
            'aulas' => $aulas->map(function ($aula) {
                return [
                    'id' => $aula->id_aulas,
                    'subject' => $aula->titulo_aulas,
                    'teacher' => $aula->professor->nome_professor ?? 'Professor',
                    'date' => $aula->data_aulas,
                    'time' => $aula->hora_aulas,
                    'level' => $aula->curso_aulas ?? $aula->descricao_aulas ?? '',
                ];
            })->values()->all(),
            'materiais' => $materiais->map(function ($material) {
                return [
                    'id' => $material->id_materiais,
                    'title' => $material->titulo_materiais,
                    'description' => $material->descricao_materiais,
                    'course' => $material->curso_materiais ?? ($material->curso->nome_curso ?? ''),
                    'level' => $material->nivel_material,
                    'link' => route('aluno.materiais.show', $material->id_materiais),
                ];
            })->values()->all(),
        ];
    }

    public function dados(): JsonResponse
    {
        return response()->json($this->obterDadosDoAluno());
    }

    public function mensagem(Request $request): JsonResponse
    {
        $mensagemUsuario = trim((string) $request->input('mensagemDoUsuario', ''));

        if ($mensagemUsuario === '') {
            return response()->json([
                'text' => 'Nao consegui compreender sua mensagem. Poderia digitar novamente?',
                'card' => null,
            ]);
        }

        $contexto = $this->obterDadosDoAluno();
        $resposta = $this->consultarGroq($mensagemUsuario, $contexto)
            ?? $this->respostaLocal($mensagemUsuario, $contexto);

        return response()->json([
            'text' => $resposta['text'],
            'card' => $resposta['card'],
        ]);
    }

    private function consultarGroq(string $mensagemUsuario, array $contexto): ?array
    {
        $apiKey = config('services.groq.key');
        $model = config('services.groq.model', 'llama-3.1-8b-instant');

        if (!$apiKey) {
            Log::warning('Groq API key nao configurada.');
            return null;
        }

        $promptSistema = implode("\n", [
            'Voce e a Traduca AI, assistente virtual inteligente de uma escola de idiomas.',
            'Responda sempre em portugues, de forma simpatica, prestativa e direta.',
            'Nunca invente informacoes. Use rigorosamente os dados reais do aluno abaixo.',
            'Se nao houver dados suficientes, diga isso com clareza e oriente o aluno a procurar a secretaria.',
            '',
            'CRONOGRAMA DE PROXIMAS AULAS DO ALUNO:',
            json_encode($contexto['aulas'], JSON_UNESCAPED_UNICODE),
            '',
            'MATERIAIS DE ESTUDO DISPONIVEIS:',
            json_encode($contexto['materiais'], JSON_UNESCAPED_UNICODE),
            '',
            'Instrucoes especificas:',
            '- Se o aluno perguntar sobre aulas, horarios ou agenda, cite resumidamente as aulas disponiveis.',
            '- Se o aluno perguntar sobre materiais, cite os titulos disponiveis.',
            '- Nao prometa executar acoes que o sistema ainda nao executa automaticamente.',
        ]);

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->asJson()
                ->timeout(15)
                ->retry(1, 300)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $promptSistema],
                        ['role' => 'user', 'content' => $mensagemUsuario],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 400,
                ]);

            if (!$response->successful()) {
                Log::warning('Falha na resposta da Groq.', [
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ]);

                return null;
            }

            $textoResposta = data_get($response->json(), 'choices.0.message.content');

            if (!is_string($textoResposta) || trim($textoResposta) === '') {
                Log::warning('Groq retornou resposta sem texto util.', [
                    'body' => $response->json(),
                ]);

                return null;
            }

            return [
                'text' => trim($textoResposta),
                'card' => $this->deveExibirCardAgenda($mensagemUsuario) ? 'schedule' : null,
            ];
        } catch (\Throwable $exception) {
            Log::warning('Erro ao consultar Groq.', [
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function respostaLocal(string $mensagemUsuario, array $contexto): array
    {
        $textoNormalizado = $this->normalizarTexto($mensagemUsuario);

        if ($this->deveExibirCardAgenda($mensagemUsuario)) {
            $totalAulas = count($contexto['aulas']);
            $texto = $totalAulas > 0
                ? "Encontrei {$totalAulas} aula(s) proximas no seu cronograma. Confira os detalhes no card abaixo."
                : 'Nao encontrei aulas vinculadas ao seu cadastro no momento.';

            return ['text' => $texto, 'card' => 'schedule'];
        }

        if (str_contains($textoNormalizado, 'material')) {
            $titulos = collect($contexto['materiais'])->pluck('title')->filter()->take(5)->values();

            if ($titulos->isEmpty()) {
                return [
                    'text' => 'Nao encontrei materiais vinculados ao seu cadastro no momento.',
                    'card' => null,
                ];
            }

            return [
                'text' => "Encontrei estes materiais para voce:\n\n- " . $titulos->implode("\n- "),
                'card' => null,
            ];
        }

        if (str_contains($textoNormalizado, 'agendar')) {
            return [
                'text' => 'Para agendar uma aula, envie o idioma, professor desejado e melhor horario. A secretaria confirma a disponibilidade.',
                'card' => null,
            ];
        }

        if (str_contains($textoNormalizado, 'professor')) {
            return [
                'text' => 'Posso ajudar com informacoes sobre professores vinculados as suas aulas. Pergunte pelo nome ou pela aula desejada.',
                'card' => null,
            ];
        }

        return [
            'text' => 'Entendido. Posso ajudar com aulas, horarios, materiais, agendamentos e contato com professores.',
            'card' => null,
        ];
    }

    private function deveExibirCardAgenda(string $texto): bool
    {
        $textoNormalizado = $this->normalizarTexto($texto);

        return str_contains($textoNormalizado, 'aula')
            || str_contains($textoNormalizado, 'horario')
            || str_contains($textoNormalizado, 'agenda');
    }

    private function normalizarTexto(mixed $texto): string
    {
        $texto = mb_strtolower((string) $texto);
        $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);

        return $normalizado !== false ? $normalizado : $texto;
    }
}
        