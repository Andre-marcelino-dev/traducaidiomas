<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Aula;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\Categoria;
use App\Models\Material;
use App\Models\Matricula;
use App\Models\Presenca;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotResponseService
{
    public function __construct(
        private ChatbotIntentService $intents
    ) {}

    public function respond(string $message, string $profile, ?string $forcedIntent = null): ?array
    {
        $intent = $forcedIntent ?? $this->intents->detect($message);

        if (
            $intent === null &&
            $profile === 'professor' &&
            auth('admin')->check() &&
            preg_match('/^[[:alpha:]]{3,}$/u', trim($message)) === 1
        ) {
            $intent = 'private_student_data';
        }

        Log::info('CHATBOT INTENT', [
            'message' => $message,
            'intent' => $intent,
            'profile' => $profile,
        ]);

        if ($intent === null) {
            return null;
        }

        if ($intent === 'private_student_data') {
            return $this->privateStudentData($profile, $message);
        }

        if ($intent === 'student_profile') {
            return $this->studentProfile($profile);
        }

        /*
        |--------------------------------------------------------------------------
        | Respostas estáticas
        |--------------------------------------------------------------------------
        */

        $static = [
            'greeting' => 'Olá! Sou a Traduca AI. Como posso ajudar?',

            'current_date' => 'Hoje é ' . now()->format('d/m/Y') . '.',

            'password' => 'Para alterar sua senha, acesse seu perfil e escolha a opção de segurança. Se não conseguir entrar, use a recuperação de senha na tela de login.',

            'teacher' => 'Você pode falar com o professor pela área de contato ou pelo fórum da turma. Se precisar, procure a coordenação da Traduca Idiomas.',
        ];

        if (isset($static[$intent])) {
            return $this->localResponse(
                $static[$intent],
                $intent
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Aulas
        |--------------------------------------------------------------------------
        */

        if ($intent === 'my_classes') {
            return $this->schedule($profile, 'my_classes', $message);
        }

        if ($intent === 'teacher_classes_count') {
            return $this->teacherClassesCount($profile, $message);
        }

        if ($intent === 'schedule') {
            return $this->schedule($profile, 'schedule', $message);
        }

        if ($intent === 'past_classes') {
            return $this->classes($profile);
        }

        /*
        |--------------------------------------------------------------------------
        | Aluno
        |--------------------------------------------------------------------------
        */

        if ($intent === 'frequency') {
            return $this->frequency($profile);
        }

        if ($intent === 'notes') {
            return $this->notes($profile);
        }

        if ($intent === 'performance') {
            return $this->performance($profile);
        }

        if ($intent === 'course') {
            return $this->course($profile);
        }

        if ($intent === 'enrollment') {
            return $this->enrollment($profile);
        }

        /*
        |--------------------------------------------------------------------------
        | Atividades
        |--------------------------------------------------------------------------
        */

        if ($intent === 'activities') {
            return $this->activities($profile, 'all');
        }

        if ($intent === 'activities_pending') {
            return $this->activities($profile, 'pending');
        }

        if ($intent === 'activities_completed') {
            return $this->activities($profile, 'completed');
        }

        /*
        |--------------------------------------------------------------------------
        | Materiais
        |--------------------------------------------------------------------------
        */

        if ($intent === 'materials') {
            return $this->materials($profile);
        }

        /*
        |--------------------------------------------------------------------------
        | Professor
        |--------------------------------------------------------------------------
        */

        if ($intent === 'teacher_students') {
            return $this->teacherStudents($profile);
        }

        if ($intent === 'teacher_students_count') {
            return $this->teacherStudentsCount($profile);
        }

        if ($intent === 'teacher_students_today') {
            return $this->teacherStudentsToday($profile);
        }

        if ($intent === 'teacher_next_class_students') {
            return $this->teacherNextClassStudents($profile);
        }

        if ($intent === 'teacher_frequency') {
            return $this->teacherFrequency($profile);
        }

        if ($intent === 'teacher_performance') {
            return $this->teacherPerformance($profile, $message);
        }

        if ($intent === 'teacher_content_performance') {
            return $this->teacherContentPerformance($profile);
        }

        if ($intent === 'teacher_question_performance') {
            return $this->teacherQuestionPerformance($profile, $message);
        }

        if ($intent === 'teacher_ranking') {
            return $this->teacherRanking($profile, $message);
        }

        /*
        |--------------------------------------------------------------------------
        | Preços
        |--------------------------------------------------------------------------
        */

        if ($intent === 'price') {
            return $this->prices();
        }

        /*
        |--------------------------------------------------------------------------
        | Nenhum tratamento local
        |--------------------------------------------------------------------------
        */

        return null;
    }

    public function respondQuestionFollowUp(string $message, string $profile, array $history): ?array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return null;
        }

        $previousQuestion = collect($history)
            ->filter(fn ($item) => ($item['role'] ?? null) === 'user')
            ->pluck('content')
            ->reverse()
            ->first(fn ($item) => $this->isQuestionPerformanceRequest((string) $item));

        $normalizedMessage = \Illuminate\Support\Str::of($message)->lower()->ascii()->value;
        $questionId = preg_match('/\bquestao\s+(\d+)\b/', $normalizedMessage, $matches)
            ? (int) $matches[1]
            : null;

        if (!$this->isQuestionFollowUp($message) && $questionId === null) {
            return null;
        }

        if ($previousQuestion === null && $questionId === null) {
            return [
                'message' => 'Qual questão você quer consultar?',
                'intent' => 'teacher_question_performance',
                'source' => 'database-follow-up',
            ];
        }

        $items = $this->authorizedQuestionItems();
        $previous = \Illuminate\Support\Str::of($previousQuestion ?: $message)->lower()->ascii()->value;
        $wantCorrect = preg_match('/\b(?:acertaram|certas?|corretas?)\b/', $previous) === 1;
        $candidates = $questionId !== null
            ? $items->where('id_questao', $questionId)
            : $items->filter(fn ($item) => (int) $item->correta === ($wantCorrect ? 1 : 0));

        if ($candidates->isEmpty()) {
            return [
                'message' => $questionId !== null
                    ? 'Não encontrei essa questão no escopo autorizado do professor autenticado.'
                    : ($wantCorrect
                    ? 'Não encontrei questões acertadas nos dados autorizados.'
                    : 'Não encontrei questões erradas nos dados autorizados.'),
                'intent' => 'teacher_question_performance',
                'source' => 'database-follow-up',
            ];
        }

        $ranking = $candidates->groupBy('id_questao')
            ->map(fn ($rows) => $rows->sortByDesc('id')->first())
            ->sortByDesc(fn ($item) => $candidates->where('id_questao', $item->id_questao)->count())
            ->values();

        $question = $ranking->first();
        $questionRows = $candidates->where('id_questao', $question->id_questao);
        $context = [
            'analysis_type' => 'teacher_question_performance',
            'question_id' => $question->id_questao,
            'question' => $question->questao?->enunciado,
            'content' => null,
            'correct_answer' => $question->questao?->resposta_correta,
            'incorrect_count' => $candidates->where('id_questao', $question->id_questao)
                ->where('correta', 0)->count(),
            'responses' => $questionRows->map(fn ($item) => [
                'student_answer' => $item->resposta_aluno,
                'result' => (int) $item->correta === 1 ? 'correct' : 'incorrect',
            ])->values()->all(),
        ];

        $normalized = \Illuminate\Support\Str::of($message)->lower()->ascii()->value;
        $answer = match (true) {
            str_contains($normalized, 'resposta correta') => $context['correct_answer']
                ? '**Questão ' . $context['question_id'] . "**\n\n**Resposta correta:** " . $context['correct_answer']
                : '**Questão ' . $context['question_id'] . "**\n\nA resposta correta não está disponível nos dados desta questão.",
            str_contains($normalized, 'responderam') => '**Questão ' . $context['question_id'] . "**\n\n**Respostas registradas:**\n" . collect($context['responses'])->pluck('student_answer')->filter()->map(fn ($answer) => '- ' . $answer)->implode("\n"),
            str_contains($normalized, 'quantos erraram') => '**Questão ' . $context['question_id'] . "**\n\n**Erros:** " . $context['incorrect_count'],
            default => '**Questão ' . $context['question_id'] . "**\n\n**Enunciado:**\n" . ($context['question'] ?: 'O enunciado não está disponível nos dados desta consulta.'),
        };

        return [
            'message' => $answer,
            'intent' => 'teacher_question_performance',
            'source' => 'database-follow-up',
            'context' => $context,
        ];
    }

    private function isQuestionPerformanceRequest(string $message): bool
    {
        $normalized = \Illuminate\Support\Str::of($message)->lower()->ascii()->value;

        return preg_match('/\b(?:questao|questoes|pergunta|perguntas)\b.*\b(?:erraram|erradas?|acertaram|acertadas?|mais erros?|mais erraram)\b/', $normalized) === 1
            || str_contains($normalized, 'foram os erros dos meus alunos');
    }

    private function isQuestionFollowUp(string $message): bool
    {
        $normalized = \Illuminate\Support\Str::of($message)->lower()->ascii()->value;

        return preg_match('/^(?:qual|qual questao|qual foi a questao|qual numero|oq diz na questao|o que diz nessa questao(?: em especifico)?|o que fala nessa questao|o que esta escrito nela|o que estava escrito|o que perguntava|qual e o enunciado|qual era a questao|qual era a resposta correta|o que os alunos responderam|quantos erraram|me mostra a questao|mostra a questao)[?!. ]*$/', $normalized) === 1;
    }

    private function authorizedQuestionItems()
    {
        $studentIds = $this->authorizedTeacherStudents(auth('admin')->id())->pluck('id_aluno');

        return AtividadeRespostaQuestao::query()
            ->with(['questao.atividade', 'resposta'])
            ->whereHas('questao.atividade', fn ($query) =>
                $query->where('id_professor', auth('admin')->id())
            )
            ->whereHas('resposta', fn ($query) =>
                $query->whereIn('id_aluno', $studentIds)
            )
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper para respostas locais
    |--------------------------------------------------------------------------
    */

    private function localResponse(
        string $message,
        string $intent
    ): array {
        return [
            'message' => $message,
            'intent' => $intent,
            'source' => 'local',
        ];
    }

    private function privateStudentData(string $profile, string $message): array
    {
        $denied = 'Por segurança, não posso consultar ou exibir dados privados de outro aluno ou professor.';

        if ($profile !== 'professor' || !auth('admin')->check()) {
            return $this->localResponse($denied, 'private_student_data');
        }

        $students = $this->authorizedTeacherStudents(auth('admin')->id());
        $normalizedMessage = Str::of($message)->lower()->ascii()->value;
        $student = $students->first(function ($candidate) use ($normalizedMessage) {
            $name = Str::of($candidate->nome_aluno)->lower()->ascii()->value;
            $tokens = collect(preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY));

            return str_contains($normalizedMessage, $name)
                || $tokens->contains(fn ($token) => strlen($token) >= 3 && str_contains($normalizedMessage, $token));
        });

        if (!$student) {
            return $this->localResponse($denied, 'private_student_data');
        }

        $courseIds = Aula::query()
            ->where('id_professor', auth('admin')->id())
            ->pluck('id_curso')
            ->merge(Atividade::query()->where('id_professor', auth('admin')->id())->pluck('id_curso'))
            ->filter()
            ->unique()
            ->values();

        $courses = Matricula::query()
            ->active()
            ->where('id_aluno', $student->id_aluno)
            ->whereIn('id_curso', $courseIds)
            ->whereHas('curso')
            ->with('curso:id_curso,nome_curso')
            ->get()
            ->map(fn ($enrollment) => $enrollment->curso?->nome_curso)
            ->filter()
            ->unique()
            ->values();

        return [
            'message' => '**Informações autorizadas do aluno**\n\n'
                . '**Nome:** ' . $student->nome_aluno . '\n'
                . '**Situação:** ' . ($student->status_aluno ?: 'não informado') . '\n'
                . ($courses->isNotEmpty() ? '**Cursos ativos:** ' . $courses->implode(', ') : ''),
            'intent' => 'private_student_data',
            'source' => 'database',
            'context' => [
                'student' => [
                    'name' => $student->nome_aluno,
                    'status' => $student->status_aluno,
                    'active_courses' => $courses->values()->all(),
                ],
            ],
        ];
    }

    private function studentProfile(string $profile): array
    {
        if ($profile === 'professor' && auth('admin')->check()) {
            $teacher = auth('admin')->user();

            return [
                'message' => "**Seu perfil**\n\n**Nome:** " . ($teacher->nome_professor ?: 'Não informado') . "\n**Perfil:** Professor",
                'intent' => 'student_profile',
                'source' => 'database',
            ];
        }

        if ($profile !== 'aluno' || !auth('aluno')->check()) {
            return [
                'message' => 'Essa consulta está disponível para alunos autenticados.',
                'intent' => 'student_profile',
                'source' => 'database',
            ];
        }

        $student = auth('aluno')->user();

        return [
            'message' => 'Você está autenticado como ' . ($student->nome_aluno ?: 'aluno') . '.',
            'intent' => 'student_profile',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Aulas futuras
    |--------------------------------------------------------------------------
    */

    private function schedule(
        string $profile,
        string $intent,
        string $message = ''
    ): array {
        $dateColumn = 'data_evento_agenda';
        $timeColumn = 'hora_inicio_agenda';

        if (
            $profile === 'aluno' &&
            auth('aluno')->check()
        ) {
            $courseIds = Matricula::query()
                ->where('id_aluno', auth('aluno')->id())
                ->pluck('id_curso');

            $dateColumn = 'data_aulas';
            $timeColumn = 'hora_aulas';

            $query = Aula::query()
                ->with('professor')
                ->whereIn('id_curso', $courseIds)
                ;
        } elseif (
            $profile === 'professor' &&
            auth('admin')->check()
        ) {
            $dateColumn = 'data_aulas';
            $timeColumn = 'hora_aulas';

            $query = Aula::query()
                ->with('professor')
                ->where('id_professor', auth('admin')->id())
                ;
        } else {
            return [
                'message' => 'Para consultar suas aulas e horários, entre na sua conta.',
                'intent' => $intent,
                'source' => 'database',
            ];
        }

        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $normalizedMessage = \Illuminate\Support\Str::of($message)->lower()->ascii()->value();
        $normalizedMessage = str_replace([' hj ', ' hj?', ' hj.'], ' hoje ', ' ' . $normalizedMessage . ' ');

        $mode = 'next_class';
        if (str_contains($normalizedMessage, 'ultima aula')) {
            $mode = 'last_class';
        } elseif (str_contains($normalizedMessage, 'ontem')) {
            $mode = 'yesterday';
        } elseif (str_contains($normalizedMessage, 'proxima semana')) {
            $mode = 'next_week';
        } elseif (
            str_contains($normalizedMessage, 'esta semana') ||
            str_contains($normalizedMessage, 'essa semana') ||
            str_contains($normalizedMessage, 'desta semana')
        ) {
            $mode = 'this_week';
        } elseif (str_contains($normalizedMessage, 'depois de amanha')) {
            $mode = 'day_after_tomorrow';
        } elseif (str_contains($normalizedMessage, 'amanha')) {
            $mode = 'tomorrow';
        } elseif (str_contains($normalizedMessage, 'hoje')) {
            $mode = 'today';
        }

        if ($mode === 'yesterday') {
            $query->whereDate($dateColumn, $now->copy()->subDay()->toDateString());
        } elseif ($mode === 'this_week' || $mode === 'next_week') {
            $start = $now->copy()->startOfWeek();
            if ($mode === 'next_week') $start->addWeek();
            $query->whereBetween($dateColumn, [$start->toDateString(), $start->copy()->endOfWeek()->toDateString()]);
        } elseif ($mode === 'last_class') {
            $query->where(function ($q) use ($now, $dateColumn, $timeColumn) {
                $q->where($dateColumn, '<', $now->toDateString())
                    ->orWhere(fn ($todayQuery) => $todayQuery->whereDate($dateColumn, $now->toDateString())->where($timeColumn, '<', $now->format('H:i:s')));
            })->orderByDesc($dateColumn)->orderByDesc($timeColumn)->limit(1);
        } elseif ($mode === 'next_class') {
            $query->where(function ($q) use ($now, $dateColumn, $timeColumn) {
                $q->where($dateColumn, '>', $now->toDateString())
                    ->orWhere(fn ($todayQuery) => $todayQuery->whereDate($dateColumn, $now->toDateString())->where($timeColumn, '>=', $now->format('H:i:s')));
            });
        }

        if ($mode === 'tomorrow') {
            $query->whereDate($dateColumn, $now->copy()->addDay()->toDateString());
        } elseif ($mode === 'today') {
            $query->whereDate($dateColumn, $now->toDateString());
        } elseif ($mode === 'day_after_tomorrow') {
            $query->whereDate($dateColumn, $now->copy()->addDays(2)->toDateString());
        }

        $isCompleteList = str_contains($normalizedMessage, 'todas as minhas aulas');

        if ($mode === 'next_class') {
            $query->limit(1);
        } elseif (!$isCompleteList && $mode !== 'last_class') {
            $query->limit(5);
        }

        if (preg_match('/depois das?\s+(\d{1,2})h/', $normalizedMessage, $match)) {
            $query->where($timeColumn, '>', sprintf('%02d:00:00', (int) $match[1]));
        }

        if (str_contains($normalizedMessage, 'a tarde')) {
            $query->whereBetween($timeColumn, ['12:00:00', '17:59:59']);
        } elseif (str_contains($normalizedMessage, 'a noite')) {
            $query->where($timeColumn, '>=', '18:00:00');
        }

        $query->reorder($mode === 'last_class' ? $dateColumn : $dateColumn, $mode === 'last_class' ? 'desc' : 'asc')
            ->orderBy($timeColumn, $mode === 'last_class' ? 'desc' : 'asc');

        Log::info('CHATBOT TEMPORAL QUERY', ['intent' => $intent, 'profile' => $profile, 'now' => $now->toDateTimeString(), 'timezone' => config('app.timezone'), 'mode' => $mode]);

        $items = $query->get();

        if ($items->isEmpty()) {
            return [
                'message' => match ($mode) {
                    'today' => 'Não há aulas agendadas para hoje.',
                    'tomorrow' => 'Você não tem aulas agendadas para amanhã.',
                    'day_after_tomorrow' => 'Você não tem aulas agendadas para depois de amanhã.',
                    default => 'Não encontrei próximas aulas cadastradas para você.',
                },
                'intent' => $intent,
                'source' => 'database',
            ];
        }

        $lines = $items->map(function ($item) {
            $dateValue = $item->data_evento_agenda ?? $item->data_aulas;
            $date = $dateValue
                ? \Carbon\Carbon::parse($dateValue)->format('d/m/Y')
                : 'Data não informada';

            $time = substr(
                (string) ($item->hora_inicio_agenda ?? $item->hora_aulas),
                0,
                5
            );

            $title = $item->titulo_agenda
                ?? $item->titulo_aulas
                ?? 'Aula';

            $teacher = $item->professor?->nome_professor;

            if ($teacher) {
                $title .= ' com ' . $teacher;
            }

            return $date . ' às ' . $time . ' — ' . $title;
        })->implode('; ');

        /*
        $prefix = $mode === 'last_class'
            ? 'Sua última aula encontrada foi: '
            : ($intent === 'schedule'
            ? 'Seus próximos horários são: '
            : 'Encontrei estas próximas aulas: ';

        );
        */
        $prefix = $mode === 'last_class'
            ? 'Sua última aula encontrada foi: '
            : 'Encontrei estas próximas aulas: ';

        $formattedLines = collect(explode('; ', $lines))
            ->filter()
            ->map(fn ($line) => '- ' . $line)
            ->implode("\n");

        return [
            'message' => ($mode === 'last_class' ? '**Última aula**' : '**Próximas aulas**') . "\n\n" . $formattedLines,
            'intent' => $intent,
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Histórico de aulas
    |--------------------------------------------------------------------------
    */

    private function teacherClassesCount(string $profile, string $message): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta estÃ¡ disponÃ­vel apenas para professores autenticados.',
                'intent' => 'teacher_classes_count',
                'source' => 'database',
            ];
        }

        $query = Aula::query()->where('id_professor', auth('admin')->id());
        $normalized = \Illuminate\Support\Str::of($message)->lower()->ascii()->value();

        if (str_contains($normalized, 'a tarde')) {
            $query->whereBetween('hora_inicio_agenda', ['12:00:00', '17:59:59']);
        } elseif (str_contains($normalized, 'a noite')) {
            $query->where('hora_inicio_agenda', '>=', '18:00:00');
        }

        $total = $query->count();

        Log::info('CHATBOT TEACHER CLASSES COUNT', [
            'professor_id' => auth('admin')->id(),
            'total' => $total,
            'timezone' => config('app.timezone'),
        ]);

        return [
            'message' => 'VocÃª possui ' . $total . ' aula(s) cadastrada(s) na agenda.',
            'intent' => 'teacher_classes_count',
            'source' => 'database',
        ];
    }

    private function classes(string $profile): array
    {
        if (
            $profile !== 'aluno' ||
            !auth('aluno')->check()
        ) {
            return [
                'message' => 'Entre na sua conta de aluno para consultar suas aulas.',
                'intent' => 'past_classes',
                'source' => 'database',
            ];
        }

        $courseIds = Matricula::query()
            ->where('id_aluno', auth('aluno')->id())
            ->active()
            ->pluck('id_curso');

        $items = Aula::query()
            ->whereIn('id_curso', $courseIds)
            ->where(function ($query) {
                $query->where('data_aulas', '<', now()->toDateString())
                    ->orWhere(function ($today) {
                        $today->whereDate('data_aulas', now()->toDateString())
                            ->where('hora_aulas', '<', now()->format('H:i:s'));
                    });
            })
            ->orderByDesc('data_aulas')
            ->orderByDesc('hora_aulas')
            ->limit(5)
            ->get();

        $lines = $items->map(function ($item) {
            $date = $item->data_aulas
                ? \Carbon\Carbon::parse($item->data_aulas)->format('d/m/Y')
                : 'Data não informada';

            return $date . ' — ' . (
                $item->titulo_aulas ?: 'Aula'
            );
        })->implode('; ');

        return [
            'message' => $lines
                ? 'Estas são algumas aulas realizadas: ' . $lines . '.'
                : 'Não encontrei aulas realizadas para você.',
            'intent' => 'past_classes',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Curso
    |--------------------------------------------------------------------------
    */

    private function course(string $profile): array
    {
        if (
            $profile !== 'aluno' ||
            !auth('aluno')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível para alunos autenticados.',
                'intent' => 'course',
                'source' => 'database',
            ];
        }

        $enrollments = Matricula::query()
            ->with('curso')
            ->where(
                'id_aluno',
                auth('aluno')->id()
            )
            ->latest('data_matricula')
            ->get();

        $courses = $enrollments
            ->map(fn($enrollment) => $enrollment->curso?->nome_curso)
            ->filter()
            ->unique()
            ->values();

        return [
            'message' => $courses->isNotEmpty()
                ? '**Seus cursos**\n\n' . $courses->map(fn ($course) => '- ' . $course)->implode("\n")
                : 'Não encontrei cursos vinculados às suas matrículas.',
            'intent' => 'course',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Matrícula
    |--------------------------------------------------------------------------
    */

    private function enrollment(string $profile): array
    {
        if (
            $profile !== 'aluno' ||
            !auth('aluno')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível para alunos autenticados.',
                'intent' => 'enrollment',
                'source' => 'database',
            ];
        }

        $enrollment = Matricula::query()
            ->with('curso')
            ->where(
                'id_aluno',
                auth('aluno')->id()
            )
            ->latest('data_matricula')
            ->first();

        if (!$enrollment) {
            return [
                'message' => 'Não encontrei uma matrícula cadastrada para você.',
                'intent' => 'enrollment',
                'source' => 'database',
            ];
        }

        $status = $enrollment->status_matricula ?: 'não informado';

        $course = $enrollment->curso?->nome_curso;

        $message = 'Sua matrícula está com status ' . $status . '.';

        if ($course) {
            $message .= ' Curso: ' . $course . '.';
        }

        if ($enrollment->data_matricula) {
            $message .= ' Data da matrícula: ' .
                \Carbon\Carbon::parse($enrollment->data_matricula)->format('d/m/Y') .
                '.';
        }

        return [
            'message' => $message,
            'intent' => 'enrollment',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Atividades
    |--------------------------------------------------------------------------
    */

    private function activities(
        string $profile,
        string $filter = 'all'
    ): array {
        $query = Atividade::query()
            ->where(
                'status_atividade',
                'ATIVA'
            )
            ->with('curso')
            ->orderBy('data_entrega')
            ->limit(8);

        if (
            $profile === 'professor' &&
            auth('admin')->check()
        ) {
            $query->where(
                'id_professor',
                auth('admin')->id()
            );
        } elseif (
            $profile === 'aluno' &&
            auth('aluno')->check()
        ) {
            $ids = Matricula::query()
                ->where(
                    'id_aluno',
                    auth('aluno')->id()
                )
                ->where(
                    'status_matricula',
                    'ATIVO'
                )
                ->pluck('id_curso');

            $query->whereIn('id_curso', $ids);

            if ($filter === 'pending') {
                $query->whereDoesntHave('respostas', function ($responseQuery) {
                    $responseQuery
                        ->where('id_aluno', auth('aluno')->id())
                        ->whereIn('status_resposta', ['ENVIADA', 'CORRIGIDA']);
                });
            } elseif ($filter === 'completed') {
                $query->whereHas('respostas', function ($responseQuery) {
                    $responseQuery
                        ->where('id_aluno', auth('aluno')->id())
                        ->whereIn('status_resposta', ['ENVIADA', 'CORRIGIDA']);
                });
            }
        } else {
            return [
                'message' => 'Entre na sua conta para consultar atividades.',
                'intent' => 'activities',
                'source' => 'database',
            ];
        }

        $items = $query->get();

        if ($items->isEmpty()) {
            return [
                'message' => 'Não encontrei atividades disponíveis.',
                'intent' => 'activities',
                'source' => 'database',
            ];
        }

        $lines = $items->map(function ($item) {
            $line = $item->titulo_atividade;

            if ($item->data_entrega) {
                $line .= ' (entrega ' .
                    $item->data_entrega->format('d/m/Y') .
                    ')';
            }

            return $line;
        })->implode('; ');

        $title = match ($filter) {
            'pending' => '**Atividades pendentes**',
            'completed' => '**Atividades concluídas**',
            default => '**Atividades**',
        };

        return [
            'message' => $title . "\n\n" . $items->map(function ($item) {
                $line = '- ' . $item->titulo_atividade;
                return $item->data_entrega
                    ? $line . ' (entrega ' . $item->data_entrega->format('d/m/Y') . ')'
                    : $line;
            })->implode("\n") . "\n\n**Total:** " . $items->count(),
            'intent' => 'activities',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Materiais
    |--------------------------------------------------------------------------
    */

    private function materials(string $profile): array
    {
        if (
            $profile === 'professor' &&
            auth('admin')->check()
        ) {
            $items = Material::query()
                ->where(
                    'id_professor',
                    auth('admin')->id()
                )
                ->latest('criado_em_materiais')
                ->limit(8)
                ->get();
        } elseif (
            $profile === 'aluno' &&
            auth('aluno')->check()
        ) {
            $ids = Matricula::query()
                ->where(
                    'id_aluno',
                    auth('aluno')->id()
                )
                ->where(
                    'status_matricula',
                    'ATIVO'
                )
                ->pluck('id_curso');

            $items = Material::query()
                ->whereIn('id_curso', $ids)
                ->latest('criado_em_materiais')
                ->limit(8)
                ->get();
        } else {
            return [
                'message' => 'Entre na sua conta para consultar materiais.',
                'intent' => 'materials',
                'source' => 'database',
            ];
        }

        $lines = $items
            ->pluck('titulo_materiais')
            ->filter()
            ->implode('; ');

        return [
            'message' => $lines
                ? '**Materiais disponíveis**\n\n' . $items->pluck('titulo_materiais')->filter()->map(fn ($title) => '- ' . $title)->implode("\n")
                : 'Não encontrei materiais disponíveis.',
            'intent' => 'materials',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Alunos do professor
    |--------------------------------------------------------------------------
    */

    private function authorizedTeacherStudents(int $professorId)
    {
        $courseIds = Aula::query()
            ->where('id_professor', $professorId)
            ->pluck('id_curso')
            ->merge(Atividade::query()->where('id_professor', $professorId)->pluck('id_curso'))
            ->filter()
            ->unique()
            ->values();

        return Matricula::query()
            ->with('aluno:id_aluno,nome_aluno,status_aluno')
            ->whereIn('id_curso', $courseIds)
            ->active()
            ->whereHas('aluno', function ($query) {
                $query->where('status_aluno', 'EM CURSO');
            })
            ->get()
            ->pluck('aluno')
            ->filter()
            ->unique('id_aluno')
            ->sortBy('nome_aluno')
            ->values();
    }

    private function teacherStudents(string $profile): array
    {
        if (
            $profile !== 'professor' ||
            !auth('admin')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_students',
                'source' => 'database',
            ];
        }

        $items = $this->authorizedTeacherStudents(auth('admin')->id());

        $names = $items
            ->pluck('nome_aluno')
            ->filter()
            ->implode('; ');

        return [
            'message' => $names
                ? '**Seus alunos**\n\n' . $items->pluck('nome_aluno')->map(fn ($name) => '- ' . $name)->implode("\n") . "\n\n**Total:** " . $items->count()
                : 'Não encontrei alunos vinculados às suas aulas.',
            'intent' => 'teacher_students',
            'source' => 'database',
        ];
    }

    private function teacherStudentsCount(string $profile): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_students_count',
                'source' => 'database',
            ];
        }

        $total = $this->authorizedTeacherStudents(auth('admin')->id())->count();

        return [
            'message' => '**Total:** ' . $total . ' aluno(s)',
            'intent' => 'teacher_students_count',
            'source' => 'database',
        ];
    }

    private function teacherStudentsToday(string $profile): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_students_today',
                'source' => 'database',
            ];
        }

        $courseIds = Aula::query()
            ->where('id_professor', auth('admin')->id())
            ->whereDate('data_aulas', now()->toDateString())
            ->pluck('id_curso')
            ->unique();

        $students = Matricula::query()
            ->with('aluno:id_aluno,nome_aluno')
            ->whereIn('id_curso', $courseIds)
            ->active()
            ->whereHas('aluno', fn ($query) => $query->where('status_aluno', 'EM CURSO'))
            ->get()
            ->pluck('aluno')
            ->filter()
            ->unique('id_aluno')
            ->sortBy('nome_aluno')
            ->values();

        $names = $students->pluck('nome_aluno')->implode('; ');

        return [
            'message' => $names
                ? '**Alunos de hoje**\n\n' . $students->pluck('nome_aluno')->map(fn ($name) => '- ' . $name)->implode("\n") . "\n\n**Total:** " . $students->count()
                : 'Não encontrei alunos associados às suas aulas de hoje.',
            'intent' => 'teacher_students_today',
            'source' => 'database',
        ];
    }

    private function teacherNextClassStudents(string $profile): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_next_class_students',
                'source' => 'database',
            ];
        }

        $now = now();
        $nextClass = Aula::query()
            ->where('id_professor', auth('admin')->id())
            ->where(function ($query) use ($now) {
                $query->where('data_aulas', '>', $now->toDateString())
                    ->orWhere(function ($today) use ($now) {
                        $today->whereDate('data_aulas', $now->toDateString())
                            ->where('hora_aulas', '>=', $now->format('H:i:s'));
                    });
            })
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->first();

        if (!$nextClass) {
            return [
                'message' => 'Não encontrei uma próxima aula para consultar.',
                'intent' => 'teacher_next_class_students',
                'source' => 'database',
            ];
        }

        $students = Matricula::query()
            ->with('aluno:id_aluno,nome_aluno')
            ->where('id_curso', $nextClass->id_curso)
            ->active()
            ->whereHas('aluno', fn ($query) => $query->where('status_aluno', 'EM CURSO'))
            ->get()
            ->pluck('aluno')
            ->filter()
            ->unique('id_aluno')
            ->sortBy('nome_aluno')
            ->values();

        $names = $students->pluck('nome_aluno')->implode('; ');

        return [
            'message' => $names
                ? '**Alunos da próxima aula**\n\n' . $students->pluck('nome_aluno')->map(fn ($name) => '- ' . $name)->implode("\n") . "\n\n**Total:** " . $students->count()
                : 'A próxima aula não possui alunos associados.',
            'intent' => 'teacher_next_class_students',
            'source' => 'database',
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Frequência do aluno
    |--------------------------------------------------------------------------
    */

    private function frequency(string $profile): array
    {
        if (
            $profile !== 'aluno' ||
            !auth('aluno')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível para alunos autenticados.',
                'intent' => 'frequency',
                'source' => 'database',
            ];
        }

        $records = Presenca::query()
            ->where(
                'id_aluno',
                auth('aluno')->id()
            )
            ->get([
                'status_presenca',
            ]);

        if ($records->isEmpty()) {
            return [
                'message' => 'Não encontrei registros de frequência para você no sistema.',
                'intent' => 'frequency',
                'source' => 'database',
            ];
        }

        $present = $records->filter(
            fn($item) => in_array(
                strtoupper((string) $item->status_presenca),
                ['PRESENTE', 'PRESENCA'],
                true
            )
        )->count();

        $absent = $records->filter(
            fn($item) => in_array(
                strtoupper((string) $item->status_presenca),
                ['FALTA', 'AUSENTE'],
                true
            )
        )->count();

        $percent = round(
            ($present / $records->count()) * 100
        );

        return [
            'message' =>
            'Você possui ' .
                $records->count() .
                ' registros: ' .
                $present .
                ' presença(s) e ' .
                $absent .
                ' falta(s). Frequência registrada: ' .
                $percent .
                '%.',

            'intent' => 'frequency',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Frequência dos alunos do professor
    |--------------------------------------------------------------------------
    */

    private function teacherFrequency(string $profile): array
    {
        if (
            $profile !== 'professor' ||
            !auth('admin')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_frequency',
                'source' => 'database',
            ];
        }

        $records = Presenca::query()
            ->join(
                'tbl_aulas',
                'tbl_aulas.id_aulas',
                '=',
                'presenca.id_aulas'
            )
            ->where(
                'tbl_aulas.id_professor',
                auth('admin')->id()
            )
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('tbl_matricula')
                    ->join('tbl_alunos', 'tbl_alunos.id_aluno', '=', 'tbl_matricula.id_aluno')
                    ->whereColumn('tbl_matricula.id_aluno', 'presenca.id_aluno')
                    ->whereColumn('tbl_matricula.id_curso', 'tbl_aulas.id_curso')
                    ->where('tbl_matricula.status_matricula', 'ATIVO')
                    ->where('tbl_alunos.status_aluno', 'EM CURSO');
            })
            ->get([
                'presenca.status_presenca',
            ]);

        if ($records->isEmpty()) {
            return [
                'message' => 'Não encontrei registros de frequência nas suas aulas.',
                'intent' => 'teacher_frequency',
                'source' => 'database',
            ];
        }

        $present = $records->filter(
            fn($item) => in_array(
                strtoupper((string) $item->status_presenca),
                ['PRESENTE', 'PRESENCA'],
                true
            )
        )->count();

        $absent = $records->filter(
            fn($item) => in_array(
                strtoupper((string) $item->status_presenca),
                ['FALTA', 'AUSENTE'],
                true
            )
        )->count();

        $percent = round(
            ($present / $records->count()) * 100
        );

        return [
            'message' =>
            'Nas suas aulas há ' .
                $records->count() .
                ' registros: ' .
                $present .
                ' presença(s) e ' .
                $absent .
                ' falta(s). Frequência registrada: ' .
                $percent .
                '%.',

            'intent' => 'teacher_frequency',
            'source' => 'database',
        ];
    }

    private function individualTeacherPerformance($student, int $professorId): array
    {
        $activities = Atividade::query()
            ->where('id_professor', $professorId)
            ->where('status_atividade', 'ATIVA')
            ->get(['id_atividade']);

        $responses = AtividadeResposta::query()
            ->where('id_aluno', $student->id_aluno)
            ->whereIn('id_atividade', $activities->pluck('id_atividade'))
            ->get(['id_atividade', 'status_resposta', 'nota']);

        $graded = $responses->whereNotNull('nota');
        $average = $graded->isNotEmpty()
            ? round($graded->avg(fn ($item) => (float) $item->nota), 1)
            : null;

        $context = [
            'student' => $student->nome_aluno,
            'has_performance_data' => $graded->isNotEmpty(),
            'activities' => $activities->count(),
            'responses' => $responses->count(),
            'graded_responses' => $graded->count(),
            'average' => $average,
        ];

        return [
            'message' => $average === null
                ? $student->nome_aluno . ' ainda não possui notas registradas suficientes para calcular um desempenho.'
                : $student->nome_aluno . ' possui ' . $graded->count() . ' nota(s) registrada(s), com média atual de ' . number_format($average, 1, ',', '.') . '. Essa média considera somente os dados disponíveis.',
            'intent' => 'teacher_performance',
            'source' => 'database',
            'context' => $context,
        ];
    }

    private function teacherContentPerformance(string $profile): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_content_performance',
                'source' => 'database',
            ];
        }

        $studentIds = $this->authorizedTeacherStudents(auth('admin')->id())->pluck('id_aluno');

        $items = AtividadeRespostaQuestao::query()
            ->with('questao.atividade')
            ->whereHas('questao.atividade', fn ($query) =>
                $query->where('id_professor', auth('admin')->id())
            )
            ->whereHas('resposta', fn ($query) =>
                $query->whereIn('id_aluno', $studentIds)
            )
            ->get();

        $incorrect = $items->filter(fn ($item) => (int) $item->correta === 0);

        $context = [
            'questions_answered' => $items->count(),
            'correct_questions' => $items->filter(fn ($item) => (int) $item->correta === 1)->count(),
            'incorrect_questions' => $incorrect->count(),
            'content_available' => false,
            'sufficient_data' => $items->isNotEmpty(),
        ];

        if ($items->isEmpty() || $incorrect->isEmpty()) {
            return [
                'message' => $items->isEmpty()
                    ? 'Ainda não há respostas por questão suficientes para identificar conteúdos com mais erros.'
                    : 'Não identifiquei conteúdos pedagógicos com mais erros nos dados registrados, porque o banco não associa um conteúdo às questões. Até o momento, as questões respondidas foram acertadas.',
                'intent' => 'teacher_content_performance',
                'source' => 'database',
                'context' => $context,
            ];
        }

        return [
            'message' => 'Encontrei ' . $incorrect->count() . ' questão(ões) incorreta(s), mas não há conteúdo pedagógico associado no banco para agrupá-las por assunto.',
            'intent' => 'teacher_content_performance',
            'source' => 'database',
            'context' => $context,
        ];
    }

    private function teacherQuestionPerformance(string $profile, string $message): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_question_performance',
                'source' => 'database',
            ];
        }

        $studentIds = $this->authorizedTeacherStudents(auth('admin')->id())->pluck('id_aluno');
        $wantCorrect = preg_match('/\b(?:acertaram|certas?|corretas?)\b/u', $message) === 1;

        $items = AtividadeRespostaQuestao::query()
            ->with(['questao.atividade', 'resposta'])
            ->whereHas('questao.atividade', fn ($query) =>
                $query->where('id_professor', auth('admin')->id())
            )
            ->whereHas('resposta', fn ($query) =>
                $query->whereIn('id_aluno', $studentIds)
            )
            ->where('correta', $wantCorrect ? 1 : 0)
            ->get();

        $questions = $items->map(fn ($item) => [
            'question_id' => $item->id_questao,
            'question' => $item->questao?->enunciado,
            'student_answer' => $item->resposta_aluno,
            'correct_answer' => $item->questao?->resposta_correta,
            'result' => $wantCorrect ? 'correct' : 'incorrect',
        ])->values()->all();

        $rankedQuestions = $items->groupBy('id_questao')
            ->map(function ($rows) {
                $first = $rows->first();
                return [
                    'question_id' => $first->id_questao,
                    'question' => $first->questao?->enunciado,
                    'content' => null,
                    'incorrect_count' => $rows->where('correta', 0)->count(),
                ];
            })
            ->sortByDesc('incorrect_count')
            ->values()
            ->all();

        $context = [
            'analysis_type' => 'teacher_question_performance',
            'result_filter' => $wantCorrect ? 'correct' : 'incorrect',
            'questions_answered' => $items->count(),
            'questions' => $questions,
            'ranked_questions' => $rankedQuestions,
        ];

        $title = $wantCorrect ? '**Questões acertadas**' : '**Questões com erros**';

        return [
            'message' => $items->isEmpty()
                ? ($wantCorrect ? 'Não encontrei questões acertadas nos dados autorizados.' : 'Não encontrei questões erradas nos dados autorizados.')
                : $title . "\n\n" . collect($rankedQuestions)->map(fn ($item) => '**Questão ' . $item['question_id'] . "**\n" . ($item['question'] ?: 'Enunciado não disponível.') . ($wantCorrect ? '' : "\n**Erros:** " . $item['incorrect_count']))->implode("\n\n"),
            'intent' => 'teacher_question_performance',
            'source' => 'database',
            'context' => $context,
        ];
    }

    private function teacherPerformance(string $profile, string $message): array
    {
        if ($profile !== 'professor' || !auth('admin')->check()) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_performance',
                'source' => 'database',
            ];
        }

        $professorId = auth('admin')->id();
        $students = $this->authorizedTeacherStudents($professorId);
        $normalizedMessage = \Illuminate\Support\Str::of($message)->lower()->ascii()->value();
        $student = $students->first(function ($candidate) use ($normalizedMessage) {
            $fullName = \Illuminate\Support\Str::of($candidate->nome_aluno)->lower()->ascii()->value();
            $firstName = strtok($fullName, ' ');
            return str_contains($normalizedMessage, $fullName)
                || ($firstName !== false && str_contains($normalizedMessage, ' ' . $firstName));
        });

        if ($student && !str_contains($normalizedMessage, 'melhor aluno') && !str_contains($normalizedMessage, 'melhor desempenho')) {
            return $this->individualTeacherPerformance($student, $professorId);
        }

        $courseIds = Aula::query()
            ->where('id_professor', $professorId)
            ->pluck('id_curso')
            ->merge(
                Atividade::query()
                    ->where('id_professor', $professorId)
                    ->pluck('id_curso')
            )
            ->filter()
            ->unique()
            ->values();

        $activities = Atividade::query()
            ->where('id_professor', $professorId)
            ->whereIn('id_curso', $courseIds)
            ->where('status_atividade', 'ATIVA')
            ->get(['id_atividade', 'titulo_atividade']);

        $responses = AtividadeResposta::query()
            ->whereIn('id_atividade', $activities->pluck('id_atividade'))
            ->whereIn('id_aluno', $students->pluck('id_aluno'))
            ->get(['id_atividade', 'id_aluno', 'status_resposta', 'nota']);

        $hasGrades = $responses->whereNotNull('nota')->isNotEmpty();
        $rows = $students->map(function ($student) use ($responses, $activities) {
            $studentResponses = $responses->where('id_aluno', $student->id_aluno);
            $submitted = $studentResponses->whereIn('status_resposta', ['ENVIADA', 'CORRIGIDA']);
            $grades = $studentResponses->whereNotNull('nota')->pluck('nota')->map(fn ($value) => (float) $value);

            return [
                'nome' => $student->nome_aluno,
                'atividades_respondidas' => $submitted->count(),
                'atividades_pendentes' => max(0, $activities->count() - $submitted->pluck('id_atividade')->unique()->count()),
                'media' => $grades->isNotEmpty() ? round($grades->avg(), 1) : null,
            ];
        });

        Log::info('CHATBOT TEACHER PERFORMANCE', [
            'intent' => 'teacher_performance',
            'profile' => $profile,
            'professor_id' => $professorId,
            'students' => $students->count(),
            'activities' => $activities->count(),
            'responses' => $responses->count(),
            'graded_responses' => $responses->whereNotNull('nota')->count(),
        ]);

        $gradedStudents = $rows->filter(fn ($row) => $row['media'] !== null);

        if (!$hasGrades || $gradedStudents->count() < 2) {
            $insufficientContext = [
                'sufficient_data' => false,
                'students_analyzed' => $students->count(),
                'graded_students' => $gradedStudents->count(),
                'activities' => $activities->count(),
                'responses' => $responses->count(),
            ];

            return [
                'message' => '**Dados insuficientes**\n\nHá apenas **' . $gradedStudents->count() . ' aluno(s)** com nota registrada.\n\nAinda não há dados suficientes para comparar os alunos.',
                'intent' => 'teacher_performance',
                'source' => 'database',
                'context' => $insufficientContext,
            ];
        }

        $withGrades = $gradedStudents->sortBy('media')->values();
        $summary = $withGrades->map(function ($row) {
            return $row['nome'] . ' (média ' . number_format($row['media'], 1, ',', '.') . ')';
        })->implode('; ');

        return [
            'message' => '**Desempenho dos alunos**\n\n' . $withGrades->map(fn ($row) => '- **' . $row['nome'] . '** — média ' . number_format($row['media'], 1, ',', '.'))->implode("\n") . "\n\n**Alunos analisados:** " . $students->count() . "\n**Alunos com nota:** " . $gradedStudents->count() . "\n**Atividades:** " . $activities->count() . "\n**Respostas registradas:** " . $responses->count() . "\n\nAlunos sem nota não foram classificados automaticamente como baixo desempenho.",
            'intent' => 'teacher_performance',
            'source' => 'database',
            'context' => [
                'sufficient_data' => true,
                'students' => $withGrades->values()->all(),
                'students_analyzed' => $students->count(),
                'activities' => $activities->count(),
                'responses' => $responses->count(),
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Notas
    |--------------------------------------------------------------------------
    */

    private function notes(string $profile): array
    {
        if (
            $profile === 'aluno' &&
            auth('aluno')->check()
        ) {
            $items = AtividadeResposta::query()
                ->where(
                    'id_aluno',
                    auth('aluno')->id()
                )
                ->whereNotNull('nota')
                ->with('atividade')
                ->get();

            if ($items->isEmpty()) {
                return [
                    'message' => 'Ainda não existem notas cadastradas para você.',
                    'intent' => 'notes',
                    'source' => 'database',
                ];
            }

            $values = $items
                ->pluck('nota')
                ->map(fn($value) => (float) $value);

            return [
                'message' =>
                'Você possui ' .
                    $values->count() .
                    ' nota(s). Média: ' .
                    number_format($values->avg(), 1, ',', '.') .
                    '. Maior nota: ' .
                    number_format($values->max(), 1, ',', '.') .
                    '.',

                'intent' => 'notes',
                'source' => 'database',
            ];
        }

        if (
            $profile === 'professor' &&
            auth('admin')->check()
        ) {
            $items = AtividadeResposta::query()
                ->whereNotNull('nota')
                ->whereHas(
                    'atividade',
                    fn($query) =>
                    $query->where(
                        'id_professor',
                        auth('admin')->id()
                    )
                )
                ->get();

            if ($items->isEmpty()) {
                return [
                    'message' => 'Ainda não existem notas cadastradas nas suas atividades.',
                    'intent' => 'notes',
                    'source' => 'database',
                ];
            }

            $values = $items
                ->pluck('nota')
                ->map(fn($value) => (float) $value);

            return [
                'message' =>
                'Suas atividades possuem ' .
                    $values->count() .
                    ' nota(s) registrada(s). Média simples: ' .
                    number_format($values->avg(), 1, ',', '.') .
                    ', maior nota: ' .
                    number_format($values->max(), 1, ',', '.') .
                    ' e menor nota: ' .
                    number_format($values->min(), 1, ',', '.') .
                    '.',

                'intent' => 'notes',
                'source' => 'database',
            ];
        }

        return [
            'message' => 'Essa consulta está disponível para usuários autenticados.',
            'intent' => 'notes',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Desempenho
    |--------------------------------------------------------------------------
    */

    private function performance(string $profile): array
    {
        if (
            $profile !== 'aluno' ||
            !auth('aluno')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível para alunos autenticados.',
                'intent' => 'performance',
                'source' => 'database',
            ];
        }

        $presence = Presenca::query()
            ->where(
                'id_aluno',
                auth('aluno')->id()
            )
            ->get([
                'status_presenca',
            ]);

        $grades = AtividadeResposta::query()
            ->where(
                'id_aluno',
                auth('aluno')->id()
            )
            ->whereNotNull('nota')
            ->get([
                'nota',
            ]);

        $parts = [];

        if ($presence->isNotEmpty()) {
            $present = $presence->filter(
                fn($item) => in_array(
                    strtoupper((string) $item->status_presenca),
                    ['PRESENTE', 'PRESENCA'],
                    true
                )
            )->count();

            $frequency = round(
                ($present / $presence->count()) * 100
            );

            $parts[] = 'frequência registrada de ' .
                $frequency .
                '%';
        }

        if ($grades->isNotEmpty()) {
            $average = $grades
                ->pluck('nota')
                ->map(fn($value) => (float) $value)
                ->avg();

            $parts[] = 'média das notas de ' .
                number_format($average, 1, ',', '.');
        }

        if (empty($parts)) {
            return [
                'message' => 'Ainda não encontrei dados suficientes para calcular seu desempenho.',
                'intent' => 'performance',
                'source' => 'database',
            ];
        }

        return [
            'message' => 'Com base nos dados registrados, você possui ' .
                implode(' e ', $parts) .
                '.',

            'intent' => 'performance',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ranking de faltas
    |--------------------------------------------------------------------------
    */

    private function teacherRanking(
        string $profile,
        string $message
    ): array {
        if (
            $profile !== 'professor' ||
            !auth('admin')->check()
        ) {
            return [
                'message' => 'Essa consulta está disponível apenas para professores autenticados.',
                'intent' => 'teacher_ranking',
                'source' => 'database',
            ];
        }

        $query = Presenca::query()
            ->with('aluno:id_aluno,nome_aluno')
            ->join(
                'tbl_aulas',
                'tbl_aulas.id_aulas',
                '=',
                'presenca.id_aulas'
            )
            ->where(
                'tbl_aulas.id_professor',
                auth('admin')->id()
            )
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('tbl_matricula')
                    ->join('tbl_alunos', 'tbl_alunos.id_aluno', '=', 'tbl_matricula.id_aluno')
                    ->whereColumn('tbl_matricula.id_aluno', 'presenca.id_aluno')
                    ->whereColumn('tbl_matricula.id_curso', 'tbl_aulas.id_curso')
                    ->where('tbl_matricula.status_matricula', 'ATIVO')
                    ->where('tbl_alunos.status_aluno', 'EM CURSO');
            });

        if (
            str_contains(
                mb_strtolower($message),
                'hoje'
            )
        ) {
            $query->whereDate(
                'presenca.data_registro_presenca',
                now()->toDateString()
            );
        }

        $records = $query->get([
            'presenca.*',
        ]);

        if ($records->isEmpty()) {
            return [
                'message' => 'Não encontrei registros de frequência dos seus alunos.',
                'intent' => 'teacher_ranking',
                'source' => 'database',
            ];
        }

        $ranking = $records
            ->groupBy('id_aluno')
            ->map(function ($studentRecords) {
                $faltas = $studentRecords
                    ->filter(
                        fn($item) => in_array(
                            strtoupper((string) $item->status_presenca),
                            ['FALTA', 'AUSENTE'],
                            true
                        )
                    )
                    ->count();

                return [
                    'nome' => optional(
                        $studentRecords->first()->aluno
                    )->nome_aluno,

                    'faltas' => $faltas,
                ];
            })
            ->filter(
                fn($item) => !empty($item['nome'])
            )
            ->sortByDesc('faltas')
            ->values();

        $top = $ranking->first();

        return [
            'message' =>
            $top && $top['faltas'] > 0
                ? $top['nome'] .
                ' possui o maior número de faltas registradas: ' .
                $top['faltas'] .
                '.'
                : 'Não encontrei faltas registradas entre os seus alunos.',

            'intent' => 'teacher_ranking',
            'source' => 'database',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Preços
    |--------------------------------------------------------------------------
    */

    private function prices(): array
    {
        $services = Categoria::query()
            ->select(
                'titulo_servico',
                'preco_servico'
            )
            ->whereNotNull('preco_servico')
            ->where(
                'preco_servico',
                '<>',
                ''
            )
            ->orderBy('ordenar_servico')
            ->get();

        if ($services->isEmpty()) {
            return [
                'message' => 'Não encontrei valores cadastrados no sistema.',
                'intent' => 'price',
                'source' => 'database',
            ];
        }

        $prices = $services
            ->map(
                fn($service) =>
                $service->titulo_servico .
                    ': ' .
                    $service->preco_servico
            )
            ->implode('; ');

        return [
            'message' =>
            'Os valores cadastrados são: ' .
                $prices .
                '.',

            'intent' => 'price',
            'source' => 'database',
        ];
    }
}
