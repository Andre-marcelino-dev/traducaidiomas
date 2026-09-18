<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatbotSemanticInterpreter
{
    private const INTENTS = [
        'greeting', 'password', 'teacher', 'student_profile', 'course',
        'enrollment', 'my_classes', 'schedule', 'past_classes', 'frequency',
        'notes', 'performance', 'activities', 'activities_pending',
        'activities_completed', 'materials', 'teacher_students',
        'teacher_students_count', 'teacher_frequency', 'teacher_performance',
        'teacher_ranking', 'teacher_classes_count', 'teacher_students_today',
        'teacher_content_performance', 'teacher_question_performance',
        'teacher_next_class_students', 'teacher_student_classes', 'price',
    ];

    public function interpret(string $message, string $profile): ?array
    {
        $key = trim((string) config('services.groq.key'));
        if ($key === '') {
            return null;
        }

        try {
            $response = Http::withToken($key)
            ->acceptJson()
            ->timeout((int) config('services.groq.timeout', 30))
            ->post(config('services.groq.url'), [
                'model' => config('services.groq.model'),
                'temperature' => 0,
                'max_tokens' => 180,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->instructions($profile),
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);
        } catch (\Throwable) {
            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        $content = trim((string) data_get($response->json(), 'choices.0.message.content'));
        $start = strpos($content, '{');
        $end = strrpos($content, '}');
        if ($start === false || $end === false || $end <= $start) {
            return null;
        }

        $decoded = json_decode(substr($content, $start, $end - $start + 1), true);
        if (!is_array($decoded)) {
            return null;
        }

        $intent = $decoded['intent'] ?? null;
        $confidence = $decoded['confidence'] ?? null;
        $entities = $decoded['entities'] ?? [];
        $needsClarification = $decoded['needs_clarification'] ?? false;
        $timeScope = $entities['time_scope'] ?? null;

        if (
            ($intent !== null && !is_string($intent)) ||
            (!is_numeric($confidence) || (float) $confidence < 0 || (float) $confidence > 1) ||
            !is_array($entities) ||
            !is_bool($needsClarification) ||
            ($timeScope !== null && !in_array($timeScope, ['today', 'upcoming', 'this_week', 'next_week', 'history'], true)) ||
            ($intent !== null && !in_array($intent, self::INTENTS, true)) ||
            (float) $confidence < 0.65
        ) {
            return null;
        }

        return [
            'intent' => $intent,
            'confidence' => (float) $confidence,
            'entities' => array_intersect_key($entities, array_flip([
                'student_name', 'teacher_name', 'course_name', 'time_scope',
            ])),
            'needs_clarification' => $needsClarification,
            'clarification_question' => is_string($decoded['clarification_question'] ?? null)
                ? $decoded['clarification_question']
                : null,
        ];
    }

    private function instructions(string $profile): string
    {
        return 'Você interpreta intenção, não responde e não consulta banco. '
            . 'Retorne somente JSON no formato '
            . '{"intent":string|null,"confidence":number,"entities":{"student_name":string|null,"teacher_name":string|null,"course_name":string|null,"time_scope":"today"|"upcoming"|"this_week"|"next_week"|"history"|null},"needs_clarification":boolean,"clarification_question":string|null}. '
            . 'Perfil: ' . $profile . '. Intenções permitidas: ' . implode(', ', self::INTENTS) . '. '
            . 'Nunca gere SQL, não escolha tabelas, não decida autorização. '
            . 'Use teacher_student_classes para aulas, agenda ou horários de um aluno nomeado. '
            . 'As expressões "quando o aluno tem aula", "me mostra a agenda", "me fala das aulas" e "quais aulas o aluno tem" pertencem a teacher_student_classes. '
            . 'Use time_scope upcoming para próximas aulas, today para hoje, this_week para esta semana, next_week para próxima semana e history para aulas já realizadas. '
            . 'Para uma pergunta geral sem período explícito, use teacher_student_classes com time_scope upcoming. '
            . 'Use teacher_students para listas de alunos. '
            . 'Se houver ambiguidade, use intent null, needs_clarification true e faça uma pergunta curta.';
    }
}
