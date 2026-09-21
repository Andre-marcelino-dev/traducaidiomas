<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotIntentClassifierService
{
    private const INTENTS = [
        'greeting', 'password', 'teacher', 'student_profile', 'student_info', 'course',
        'enrollment', 'my_classes', 'schedule', 'past_classes', 'frequency',
        'notes', 'performance', 'activities', 'activities_pending',
        'activities_completed', 'student_activity_grade', 'student_activity_correction', 'student_classes_count', 'absences', 'student_question_performance', 'student_activity_performance', 'materials', 'teacher_students',
        'teacher_students_count', 'teacher_frequency', 'teacher_performance',
        'teacher_ranking', 'teacher_classes_count', 'teacher_students_today',
        'teacher_content_performance',
        'teacher_activity_report',
        'teacher_question_performance',
        'teacher_next_class_students',
        'teacher_student_classes',
        'price',
    ];

    public function classify(string $message, string $profile): ?string
    {
        $key = trim((string) config('services.groq.key'));
        if ($key === '') {
            return null;
        }

        $system = 'Você é um classificador de intenção. Não responda à pergunta. '
            . 'Retorne somente JSON válido no formato {"intent":"nome"}. '
            . 'Use "NONE" quando não houver segurança suficiente. '
            . 'Ignore erros ortográficos, acentos ausentes, abreviações e linguagem informal. '
            . 'Não consulte banco, não determine permissões, identidade ou dados privados. '
            . 'Perfil atual: ' . $profile . '. Lista permitida: '
            . implode(', ', self::INTENTS)
            . '. Exemplos: "hj" significa hoje; "proxima" significa próxima; '
            . 'erros como desenpenho/desepenho indicam desempenho; "ta" significa está. '
            . 'Perguntas sobre desempenho nominal ou melhor aluno usam teacher_performance; '
            . 'Perguntas sobre aulas de um aluno nomeado usam teacher_student_classes; '
            . 'Perguntas sobre questoes ou perguntas erradas, certas ou acertadas usam teacher_question_performance; '
            . 'Perguntas sobre conteudos, assuntos ou materias em que os alunos erram usam teacher_content_performance; '
            . 'Relatorios ou analises do desempenho da turma nas atividades usam teacher_activity_report; '
            . 'No perfil aluno, informacoes proprias usam student_info, atividades respondidas/concluidas usam activities_completed, nota de atividade especifica usa student_activity_grade, correcao de atividade especifica usa student_activity_correction, aulas passadas usam student_classes_count ou past_classes, faltas usam absences, questoes certas/erradas usam student_question_performance e desempenho nas atividades usa student_activity_performance; '
            . 'aulas hoje, semana, próxima ou última usam my_classes. '
            . 'Exemplos obrigatórios: "Qual o desempenho do Caio?", '
            . '"Como o Caio está indo?", "Como o Caio ta indo?", '
            . '"Como o Caio tá indo?", "Como o Caio esta indo?" e '
            . '"Como o Caio vem se saindo?" => teacher_performance. '
            . '"Como estão meus alunos?", "Como está minha turma?" e '
            . '"Quem precisa de mais atenção?" => teacher_performance. '
            . 'Não use teacher_performance apenas porque existe um nome: '
            . '"Quem é o Caio?" e "Qual curso o Caio faz?" devem ser NONE.';

        try {
            $response = Http::withToken($key)
                ->acceptJson()
                ->timeout((int) config('services.groq.timeout', 30))
                ->post(config('services.groq.url'), [
                    'model' => config('services.groq.model'),
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $message],
                    ],
                    'temperature' => 0,
                    'max_tokens' => 100,
                ]);

            $content = trim((string) data_get(
                $response->json(),
                'choices.0.message.content'
            ));
            $jsonStart = strpos($content, '{');
            $jsonEnd = strrpos($content, '}');
            if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                $content = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
            }
            $decoded = json_decode($content, true);
            $value = is_array($decoded) ? ($decoded['intent'] ?? 'NONE') : 'NONE';
            $value = strtolower(trim((string) $value));

            if ($profile === 'professor' && $value === 'performance') {
                $value = 'teacher_performance';
            }

            if (!$response->successful() || !in_array($value, self::INTENTS, true)) {
                Log::warning('Classificação de intent inválida', [
                    'status' => $response->status(),
                    'content_length' => strlen($content),
                ]);
            }

            if ($response->successful() && in_array($value, self::INTENTS, true)) {
                Log::info('CHATBOT INTENT FALLBACK', [
                    'profile' => $profile,
                    'intent' => $value,
                ]);

                return $value;
            }
        } catch (\Throwable $exception) {
            Log::warning('Falha no classificador de intent do chatbot', [
                'profile' => $profile,
                'error' => $exception->getMessage(),
            ]);
        }

        return null;
    }
}
