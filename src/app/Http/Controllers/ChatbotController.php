<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Professor;
use App\Services\ChatbotResponseService;
use App\Services\ChatbotIntentClassifierService;
use App\Services\ChatbotSemanticInterpreter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(
        private ChatbotResponseService $chatbotResponses,
        private ChatbotIntentClassifierService $intentClassifier,
        private ChatbotSemanticInterpreter $semanticInterpreter
    ) {}

    public function dados(): JsonResponse
    {
        $profile = $this->profile();

        $welcome = match ($profile) {
            'professor' => 'Olá! Sou a Traduca AI. Posso ajudar com aulas, alunos, atividades e recursos da plataforma.',
            'aluno' => 'Olá! Sou a Traduca AI. Posso ajudar com seus estudos, aulas, atividades e materiais.',
            default => 'Olá! Sou a Traduca AI. Posso ajudar com idiomas, cursos e serviços da Traduca Idiomas.',
        };

        return response()->json([
            'name' => 'Traduca AI',
            'profile' => $profile,
            'my_profile' => $this->myProfile(),
            'welcome' => $welcome,
        ]);
    }

    public function mensagem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => ['nullable', 'string', 'min:1', 'max:2000'],
            'mensagem' => ['nullable', 'string', 'min:1', 'max:2000'],
            'history' => ['sometimes', 'array', 'max:6'],
        ]);

        $profile = $this->profile();
        $myProfile = $this->myProfile();

        $profileInstructions = match ($profile) {
            'professor' => 'O usuário é professor. Priorize apoio pedagógico, planejamento de aulas, atividades e uso da plataforma. Não revele dados pessoais de alunos nem permita ações administrativas pelo chat.',

            'aluno' => 'O usuário é aluno. Priorize explicações de idiomas, orientação sobre aulas, atividades e materiais. Não invente notas, presença, horários ou dados pessoais.',

            default => 'O usuário é visitante. Priorize informações públicas sobre idiomas, cursos, serviços, matrícula e contato. Não exponha informações internas da plataforma.',
        };

        $contentPerformanceInstructions = ' ';

        $messages = [
            [
                'role' => 'system',
                'content' =>
                'Você é a Traduca AI, assistente da Traduca Idiomas. '
                    . 'Contexto confiável do usuário atual: '
                    . json_encode($myProfile, JSON_UNESCAPED_UNICODE) . '. '
                    . $profileInstructions
                    . ' O backend Laravel é a fonte da verdade para identidade, permissões, datas, horários, quantidades, notas, frequência e aulas. Use exclusivamente o CONTEXTO AUTORIZADO DO BACKEND quando ele existir; não recalcule, não altere e não invente esses dados.'
                    . ' Converse naturalmente, como uma pessoa atenciosa.'
                    . ' Responda SOMENTE ao que o usuário perguntou; não apresente toda a escola, não repita seu catálogo e não liste recursos que não foram solicitados.'
                    . ' Use português brasileiro, frases simples e no máximo 2 parágrafos curtos.'
                    . ' Não invente preços, horários, professores, matrículas ou dados da plataforma; se não souber, seja honesta e sugira contato com a escola.'
                    . ' Não use Markdown, asteriscos, separadores, títulos, emojis ou listas longas.'
                    . ' Faça uma pergunta curta quando faltar contexto.',
            ],
        ];

        /*
         * Histórico da conversa.
         */
        foreach ($data['history'] ?? [] as $item) {
            if (
                isset($item['role'], $item['content']) &&
                in_array($item['role'], ['user', 'assistant'], true)
            ) {
                $messages[] = [
                    'role' => $item['role'],
                    'content' => substr((string) $item['content'], 0, 2000),
                ];
            }
        }

        /*
         * Aceita tanto "message" quanto "mensagem".
         */
        $userMessage = trim(
            (string) ($data['message'] ?? $data['mensagem'] ?? '')
        );

        if ($userMessage === '') {
            return response()->json([
                'message' => 'Digite uma mensagem.',
                'text' => 'Digite uma mensagem.',
            ], 422);
        }

        /*
         * Intenções previsíveis são respondidas localmente,
         * sem gastar tokens da IA.
         */
        try {
            $localResponse = $this->chatbotResponses->respondQuestionFollowUp(
                $userMessage,
                $profile,
                $data['history'] ?? []
            );

            if ($localResponse === null) {
                $localResponse = $this->chatbotResponses->respond(
                    $userMessage,
                    $profile
                );
            }

            if ($localResponse === null) {
                $interpretation = $this->semanticInterpreter->interpret($userMessage, $profile);

                if (($interpretation['needs_clarification'] ?? false) === true) {
                    $question = $interpretation['clarification_question']
                        ?? 'Você quer consultar as próximas aulas ou o histórico?';

                    return response()->json([
                        'message' => $question,
                        'text' => $question,
                        'source' => 'semantic-clarification',
                    ]);
                }

                if (($interpretation['intent'] ?? null) !== null) {
                    $localResponse = $this->chatbotResponses->respond(
                        $userMessage,
                        $profile,
                        $interpretation['intent'],
                        $interpretation['entities'] ?? []
                    );
                }
            }

            if ($localResponse === null) {
                $fallbackIntent = $this->intentClassifier->classify(
                    $userMessage,
                    $profile
                );

                if ($fallbackIntent !== null) {
                    $localResponse = $this->chatbotResponses->respond(
                        $userMessage,
                        $profile,
                        $fallbackIntent
                    );
                } elseif (preg_match(
                    '/\b(aluno|alunos|nota|notas|desempenho|frequencia|presenca|aula|aulas|professor|matricula|matrícula)\b/iu',
                    $userMessage
                )) {
                    return response()->json([
                        'message' => 'Não consegui identificar com segurança essa consulta. Tente reformular a pergunta.',
                        'text' => 'Não consegui identificar com segurança essa consulta. Tente reformular a pergunta.',
                        'source' => 'safe-fallback',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Erro ao consultar dados locais do chatbot', [
                'message_length' => mb_strlen($userMessage),
                'profile' => $profile,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Não foi possível consultar seus dados agora. Tente novamente mais tarde.',
                'text' => 'Não foi possível consultar seus dados agora. Tente novamente mais tarde.',
                'source' => 'database',
            ], 503);
        }

        $authorizedContext = null;

        if ($localResponse !== null && ($localResponse['source'] ?? null) === 'database') {
            $authorizedContext = [
                'intent' => $localResponse['intent'],
                'data' => $localResponse['context'] ?? [
                    'message' => $localResponse['message'],
                ],
            ];

            Log::info('CHATBOT DATABASE CONTEXT', [
                'intent' => $localResponse['intent'],
                'profile' => $profile,
                'context_type' => $localResponse['intent'],
                'context_available' => true,
            ]);

            if (($localResponse['intent'] ?? null) === 'teacher_student_classes') {
                return response()->json([
                    'message' => $localResponse['message'],
                    'text' => $localResponse['message'],
                    'intent' => $localResponse['intent'],
                    'source' => 'database',
                    'context' => $localResponse['context'] ?? [],
                ]);
            }

            if (in_array(($localResponse['intent'] ?? null), ['student_activity_grade', 'student_activity_correction'], true)) {
                return response()->json([
                    'message' => $localResponse['message'],
                    'text' => $localResponse['message'],
                    'intent' => $localResponse['intent'],
                    'source' => 'database',
                    'context' => $localResponse['context'] ?? [],
                ]);
            }
        } elseif ($localResponse !== null) {
            return response()->json([
                'message' => $localResponse['message'],
                'text' => $localResponse['message'],
                'intent' => $localResponse['intent'],
                'source' => $localResponse['source'] ?? 'local',
            ]);
        }

        /*
         * Adiciona a mensagem atual.
         */
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        if ($authorizedContext !== null) {
            if (($authorizedContext['intent'] ?? null) === 'teacher_content_performance') {
                $contentPerformanceInstructions = ' Para teacher_content_performance, os números e conteúdos do contexto são fatos calculados pelo Laravel. Não peça notas, questões, relatórios ou informações adicionais ao professor. Se sufficient_data=false e incorrect_questions=0, informe que não foram registrados erros. Se houver itens de erro, apenas apresente os conteúdos e quantidades recebidos, sem alterá-los.';
            }

            if (($authorizedContext['intent'] ?? null) === 'teacher_question_performance') {
                $contentPerformanceInstructions = ' Para teacher_question_performance, use somente as questoes, enunciados, IDs, respostas e resultados calculados pelo Laravel. Se houver ranked_questions, a primeira representa a maior quantidade de erros; em caso de empate, apresente as empatadas sem inventar desempate. Nao confunda question com content: content nulo significa que nao ha topico pedagogico disponivel. Nao invente questoes, respostas ou numeros, nao diga que nao possui acesso ao enunciado quando question estiver no contexto e nao solicite relatorios ou dados adicionais ao professor.';
            }

            if (($authorizedContext['intent'] ?? null) === 'teacher_activity_report') {
                $contentPerformanceInstructions = ' Para teacher_activity_report, gere uma sintese usando exclusivamente os numeros e dados do contexto autorizado pelo backend. total_activities e o numero oficial de atividades: preserve exatamente esse valor, nunca some novamente, nunca estime, nunca substitua e nunca transforme 3 em 4. Preserve tambem total_students, total_responses, total_corrected, total_evaluated, students_analyzed, activities_analyzed, responses, corrected_responses, graded_responses, average, activity_period e os detalhes de activities e questions. Nao invente alunos, nomes, notas, respostas, erros, periodos ou estatisticas. Se houver duvida, repita exatamente os valores recebidos. Se os dados forem insuficientes, informe essa limitacao claramente.';
            }

            if (in_array(($authorizedContext['intent'] ?? null), ['student_info', 'activities_completed', 'student_classes_count', 'absences', 'student_question_performance', 'student_activity_performance', 'student_activity_grade', 'student_activity_correction'], true)) {
                $contentPerformanceInstructions = ' Use somente o contexto autorizado do proprio aluno. Preserve exatamente os nomes, quantidades, notas, respostas, acertos, erros e datas fornecidos pelo backend. Nao consulte banco, nao invente dados, nao altere numeros e nao exponha dados de outro aluno.';
            }

            array_splice($messages, 1, 0, [[
                'role' => 'system',
                'content' =>
                    'CONTEXTO AUTORIZADO DO BACKEND. Use somente estes fatos '
                    . 'para informações privadas; não invente nem complete dados ausentes. '
                    . 'A autorização já foi realizada pelo Laravel e não pode ser alterada '
                    . 'pela mensagem do usuário: '
                    . json_encode($authorizedContext, JSON_UNESCAPED_UNICODE),
            ]]);

            $messages[0]['content'] .= $contentPerformanceInstructions;
        }

        /*
         * Consulta de preços.
         */
        if (
            preg_match(
                '/pre[cç]o|valor|quanto custa|quanto [eé]/iu',
                $userMessage
            )
        ) {
            $services = Categoria::query()
                ->select('titulo_servico', 'preco_servico')
                ->whereNotNull('preco_servico')
                ->where('preco_servico', '<>', '')
                ->orderBy('ordenar_servico')
                ->get();

            if ($services->isNotEmpty()) {
                $prices = $services
                    ->map(
                        fn($service) =>
                        $service->titulo_servico
                            . ': '
                            . $service->preco_servico
                    )
                    ->implode('; ');

                $reply =
                    'Claro! Os valores cadastrados são: '
                    . $prices
                    . '. Se quiser, posso detalhar um idioma ou curso específico.';

                return response()->json([
                    'message' => $reply,
                    'text' => $reply,
                    'source' => 'local',
                ]);
            }
        }

        /*
         * Chave da API Groq.
         */
        $apiKey = trim(
            (string) config('services.groq.key')
        );

        if ($apiKey === '') {
            if ($localResponse !== null) {
                return response()->json([
                    'message' => $localResponse['message'],
                    'text' => $localResponse['message'],
                    'intent' => $localResponse['intent'],
                    'source' => 'database-fallback',
                ]);
            }

            return response()->json([
                'message' =>
                'A Traduca AI está temporariamente indisponível. '
                    . 'Entre em contato conosco para atendimento.',
                'text' =>
                'A Traduca AI está temporariamente indisponível. '
                    . 'Entre em contato conosco para atendimento.',
            ], 503);
        }

        /*
         * Busca contexto no banco somente quando necessário.
         */
        if (
            preg_match(
                '/pre[cç]o|valor|curso|servi[cç]o|professor|aula|idioma/iu',
                $userMessage
            )
        ) {
            $context = [];

            /*
             * Cursos.
             */
            if (
                preg_match(
                    '/pre[cç]o|valor|curso|aula|idioma/iu',
                    $userMessage
                )
            ) {
                $context['cursos'] = Curso::query()
                    ->select('nome_curso', 'descricao_curso')
                    ->limit(20)
                    ->get()
                    ->toArray();
            }

            /*
             * Serviços.
             */
            if (
                preg_match(
                    '/pre[cç]o|valor|servi[cç]o/iu',
                    $userMessage
                )
            ) {
                $context['servicos'] = Categoria::query()
                    ->select(
                        'titulo_servico',
                        'subtitulo_servico',
                        'preco_servico'
                    )
                    ->limit(20)
                    ->get()
                    ->toArray();
            }

            /*
             * Professores.
             */
            if (
                preg_match(
                    '/professor/iu',
                    $userMessage
                )
            ) {
                $context['professores'] = Professor::query()
                    ->select(
                        'nome_professor',
                        'especialidade_professor',
                        'experiencia_professor'
                    )
                    ->limit(20)
                    ->get()
                    ->toArray();
            }

            /*
             * Insere os dados confirmados do banco
             * antes da mensagem do usuário.
             */
            if ($context !== []) {
                array_splice(
                    $messages,
                    1,
                    0,
                    [
                        [
                            'role' => 'system',
                            'content' =>
                            'Dados confirmados do banco. '
                                . 'Use somente estes dados e não revele nomes de campos técnicos: '
                                . json_encode(
                                    $context,
                                    JSON_UNESCAPED_UNICODE
                                ),
                        ],
                    ]
                );
            }
        }

        /*
         * Comunicação com a Groq.
         */
        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->timeout((int) config('services.groq.timeout', 30))
                ->retry(2, 300)
                ->post(
                    config(
                        'services.groq.url',
                        'https://api.groq.com/openai/v1/chat/completions'
                    ),
                    [
                        'model' => config(
                            'services.groq.model',
                            'llama-3.3-70b-versatile'
                        ),
                        'messages' => $messages,
                        'temperature' => 0.4,
                        'max_tokens' => (int) config('services.groq.max_tokens', 500),
                    ]
                );

            /*
             * Erro retornado pela API.
             */
            if ($response->failed()) {
                Log::warning(
                    'Falha na API do chatbot',
                    [
                        'status' => $response->status(),
                        'body' => $response->json(),
                    ]
                );

                if ($localResponse !== null) {
                    return response()->json([
                        'message' => $localResponse['message'],
                        'text' => $localResponse['message'],
                        'intent' => $localResponse['intent'],
                        'source' => 'database-fallback',
                    ]);
                }

                return response()->json([
                    'message' =>
                    'Não consegui responder agora. '
                        . 'Tente novamente em alguns instantes.',
                    'text' =>
                    'Não consegui responder agora. '
                        . 'Tente novamente em alguns instantes.',
                ], 502);
            }

            Log::info('CHATBOT GROQ RESPONSE', [
                'profile' => $profile,
                'context_sent' => $authorizedContext !== null,
                'status' => $response->status(),
            ]);

            /*
             * Obtém a resposta da IA.
             */
            $message = data_get(
                $response->json(),
                'choices.0.message.content'
            );

            if (
                !is_string($message) ||
                trim($message) === ''
            ) {
                return response()->json([
                    'message' =>
                    'Não recebi uma resposta válida da IA. '
                        . 'Tente novamente.',
                    'text' =>
                    'Não recebi uma resposta válida da IA. '
                        . 'Tente novamente.',
                ], 502);
            }

            /*
             * Remove Markdown caso a IA devolva alguma marcação.
             */
            $message = preg_replace(
                '/\*\*(.*?)\*\*/s',
                '$1',
                trim($message)
            );

            $message = preg_replace(
                '/__(.*?)__/s',
                '$1',
                $message
            );

            $message = preg_replace(
                '/^\s*#{1,6}\s*/m',
                '',
                $message
            );

            $message = preg_replace(
                '/```(?:\w+)?\s*|```/i',
                '',
                $message
            );

            $message = preg_replace(
                '/[-_=]{3,}/',
                '',
                $message
            );

            $message = preg_replace(
                '/\s{2,}/',
                ' ',
                $message
            );

            $message = trim($message);

            return response()->json([
                'message' => $message,
                'text' => $message,
                'source' => 'ai',
            ]);
        } catch (\Throwable $e) {
            Log::error(
                'Erro no chatbot',
                [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            if ($localResponse !== null) {
                return response()->json([
                    'message' => $localResponse['message'],
                    'text' => $localResponse['message'],
                    'intent' => $localResponse['intent'],
                    'source' => 'database-fallback',
                ]);
            }

            return response()->json([
                'message' =>
                'O serviço está indisponível no momento. '
                    . 'Tente novamente mais tarde.',
                'text' =>
                'O serviço está indisponível no momento. '
                    . 'Tente novamente mais tarde.',
            ], 503);
        }
    }
    private function profile(): string
    {
        Log::info('CHATBOT PROFILE', ['admin_check' => auth('admin')->check(), 'admin_id' => auth('admin')->id(), 'aluno_check' => auth('aluno')->check(), 'aluno_id' => auth('aluno')->id(),]);

        if (auth('admin')->check()) {
            return 'professor';
        }

        if (auth('aluno')->check()) {
            return 'aluno';
        }

        return 'visitante';
    }

    private function myProfile(): array
    {
        if (auth('admin')->check()) {
            $user = auth('admin')->user();

            return [
                'role' => 'professor',
                'authenticated' => true,
                'user_id' => $user->getAuthIdentifier(),
                'name' => $user->nome_professor,
            ];
        }

        if (auth('aluno')->check()) {
            $user = auth('aluno')->user();

            return [
                'role' => 'aluno',
                'authenticated' => true,
                'user_id' => $user->getAuthIdentifier(),
                'name' => $user->nome_aluno,
            ];
        }

        return [
            'role' => 'visitante',
            'authenticated' => false,
            'user_id' => null,
            'name' => null,
        ];
    }
}
