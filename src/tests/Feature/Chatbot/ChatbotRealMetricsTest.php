<?php

namespace Tests\Feature\Chatbot;

use App\Models\Professor;
use App\Services\ChatbotResponseService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChatbotRealMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_frequency_uses_real_presence_records_and_active_enrollment(): void
    {
        [$professor, $studentId, $courseId] = $this->seedClass();
        DB::table('presenca')->insert([
            ['id_aulas' => $this->classId, 'id_aluno' => $studentId, 'status_presenca' => 'PRESENTE', 'data_registro_presenca' => '2026-09-16'],
            ['id_aulas' => $this->classId, 'id_aluno' => $studentId, 'status_presenca' => 'FALTA', 'data_registro_presenca' => '2026-09-17'],
        ]);
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)->respond('frequência dos alunos', 'professor');

        $this->assertSame('teacher_frequency', $response['intent']);
        $this->assertStringContainsString('50%', $response['message']);
        $this->assertStringContainsString('1 presença', $response['message']);
        $this->assertStringContainsString('1 falta', $response['message']);
    }

    public function test_teacher_performance_uses_real_answer_and_note_records(): void
    {
        [$professor, $studentId, $courseId] = $this->seedClass();
        $activityId = DB::table('tbl_atividades')->insertGetId([
            'id_professor' => $professor->id_professor,
            'id_curso' => $courseId,
            'titulo_atividade' => 'Atividade de teste',
            'descricao_atividade' => 'Teste',
            'tipo_atividade' => 'texto',
            'status_atividade' => 'ATIVA',
        ]);
        DB::table('tbl_atividade_respostas')->insert([
            'id_atividade' => $activityId,
            'id_aluno' => $studentId,
            'status_resposta' => 'CORRIGIDA',
            'nota' => 8.5,
        ]);
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)
            ->respond('qual o desempenho do Caio', 'professor');

        $this->assertSame('teacher_performance', $response['intent']);
        $this->assertStringContainsString('8,5', $response['message']);
        $this->assertSame(8.5, $response['context']['average']);
    }

    public function test_teacher_performance_accepts_average_of_named_student_formulation(): void
    {
        [$professor, $studentId, $courseId] = $this->seedClass();
        $activityId = DB::table('tbl_atividades')->insertGetId([
            'id_professor' => $professor->id_professor,
            'id_curso' => $courseId,
            'titulo_atividade' => 'Atividade de teste',
            'descricao_atividade' => 'Teste',
            'tipo_atividade' => 'texto',
            'status_atividade' => 'ATIVA',
        ]);
        DB::table('tbl_atividade_respostas')->insert([
            'id_atividade' => $activityId,
            'id_aluno' => $studentId,
            'status_resposta' => 'CORRIGIDA',
            'nota' => 8.5,
        ]);
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)
            ->respond('Qual é a média do aluno Caio Ferreira?', 'professor');

        $this->assertSame('teacher_performance', $response['intent']);
        $this->assertSame('Caio Ferreira', $response['context']['student']);
        $this->assertSame(8.5, $response['context']['average']);
    }

    public function test_teacher_student_classes_applies_time_scope_and_formats_dates(): void
    {
        Carbon::setTestNow('2026-09-16 09:00:00');
        [$professor, , $courseId] = $this->seedClass();
        DB::table('tbl_aulas')->insert([
            'id_professor' => $professor->id_professor,
            'id_curso' => $courseId,
            'titulo_aulas' => 'Aula próxima semana',
            'descricao_aulas' => 'Teste',
            'data_aulas' => '2026-09-21',
            'hora_aulas' => '10:00:00',
            'cursos_aulas' => 'Inglês',
            'status_aulas' => 'ATIVO',
            'criado_em_aulas' => now(),
            'atualizado_em_aulas' => now(),
        ]);
        $this->actingAs($professor, 'admin');

        $today = app(ChatbotResponseService::class)->respond(
            'Quais aulas o Caio tem hoje?',
            'professor',
            'teacher_student_classes',
            ['student_name' => 'Caio Ferreira', 'time_scope' => 'today']
        );
        $nextWeek = app(ChatbotResponseService::class)->respond(
            'Quais aulas o Caio terá na próxima semana?',
            'professor',
            'teacher_student_classes',
            ['student_name' => 'Caio Ferreira', 'time_scope' => 'next_week']
        );

        $this->assertSame(1, $today['context']['classes']);
        $this->assertSame('today', $today['context']['time_scope']);
        $this->assertStringContainsString('16/09/2026', $today['message']);
        $this->assertSame(1, $nextWeek['context']['classes']);
        $this->assertSame('next_week', $nextWeek['context']['time_scope']);
        $this->assertStringContainsString('21/09/2026', $nextWeek['message']);
        Carbon::setTestNow();
    }

    public function test_inactive_enrollment_is_excluded_from_teacher_frequency(): void
    {
        [$professor] = $this->seedClass();
        DB::table('tbl_matricula')->update(['status_matricula' => 'CANCELADO']);
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)->respond('frequência dos alunos', 'professor');

        $this->assertStringContainsString('Não encontrei registros', $response['message']);
    }

    public function test_student_enrolled_in_another_course_is_excluded(): void
    {
        [$professor, $studentId] = $this->seedClass();
        $otherCourseId = DB::table('tbl_cursos')->insertGetId([
            'nome_curso' => 'Espanhol',
            'descricao_curso' => 'Outro curso',
        ]);
        DB::table('tbl_matricula')->where('id_aluno', $studentId)->update([
            'id_curso' => $otherCourseId,
        ]);
        DB::table('presenca')->insert([
            'id_aulas' => $this->classId,
            'id_aluno' => $studentId,
            'status_presenca' => 'PRESENTE',
            'data_registro_presenca' => '2026-09-16',
        ]);
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)->respond('frequência dos alunos', 'professor');

        $this->assertStringContainsString('Não encontrei registros', $response['message']);
    }

    public function test_student_without_presence_records_returns_no_frequency_data(): void
    {
        [$professor] = $this->seedClass();
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)->respond('frequência dos alunos', 'professor');

        $this->assertStringContainsString('Não encontrei registros', $response['message']);
    }

    public function test_student_without_grades_returns_insufficient_performance_data(): void
    {
        [$professor] = $this->seedClass();
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)
            ->respond('qual o desempenho do Caio', 'professor');

        $this->assertSame('teacher_performance', $response['intent']);
        $this->assertStringContainsString('ainda não possui notas registradas suficientes', $response['message']);
        $this->assertFalse($response['context']['has_performance_data']);
    }

    public function test_activity_report_exposes_official_totals_and_latest_activity_details(): void
    {
        [$professor, $studentId, $courseId] = $this->seedClass();
        foreach ([
            ['Antiga', '2026-09-01 12:00:00'],
            ['Atividade 2', '2026-09-21 12:06:00'],
            ['Atividade 3', '2026-09-21 12:17:00'],
            ['Atividade 4', '2026-09-21 12:27:00'],
        ] as [$title, $createdAt]) {
            $activityId = DB::table('tbl_atividades')->insertGetId([
                'id_professor' => $professor->id_professor,
                'id_curso' => $courseId,
                'titulo_atividade' => $title,
                'descricao_atividade' => 'Descrição ' . $title,
                'tipo_atividade' => 'texto',
                'status_atividade' => 'ATIVA',
                'criado_em' => $createdAt,
            ]);
            if ($title !== 'Antiga') {
                DB::table('tbl_atividade_respostas')->insert([
                    'id_atividade' => $activityId,
                    'id_aluno' => $studentId,
                    'status_resposta' => 'ENVIADA',
                    'nota' => null,
                ]);
            }
        }

        $this->actingAs($professor, 'admin');
        $response = app(ChatbotResponseService::class)->respond(
            'Analise as ultimas atividades',
            'professor',
            'teacher_activity_report',
            ['time_scope' => 'last_activities']
        );

        $this->assertSame(1, $response['context']['total_students']);
        $this->assertSame(3, $response['context']['total_activities']);
        $this->assertSame(3, $response['context']['total_responses']);
        $this->assertSame(0, $response['context']['total_corrected']);
        $this->assertSame(0, $response['context']['total_evaluated']);
        $this->assertSame(['Atividade 4', 'Atividade 3', 'Atividade 2'], array_column($response['context']['activities'], 'title'));
        $this->assertStringNotContainsString('Antiga', json_encode($response['context'], JSON_UNESCAPED_UNICODE));
    }

    public function test_student_own_information_and_absences_use_authenticated_student(): void
    {
        [, $studentId] = $this->seedClass();
        DB::table('presenca')->insert([
            'id_aulas' => $this->classId,
            'id_aluno' => $studentId,
            'status_presenca' => 'PRESENTE',
            'data_registro_presenca' => '2026-09-16',
        ]);
        $this->actingAs(\App\Models\Aluno::find($studentId), 'aluno');

        $info = app(ChatbotResponseService::class)->respond('Quais são minhas informações?', 'aluno');
        $absences = app(ChatbotResponseService::class)->respond('Quantas aulas eu perdi?', 'aluno');

        $this->assertSame('Caio Ferreira', $info['context']['student']['name']);
        $this->assertSame(0, $absences['context']['absences']);
    }

    public function test_student_activity_questions_and_performance_are_calculated_by_backend(): void
    {
        [$professor, $studentId, $courseId] = $this->seedClass();
        $activityId = DB::table('tbl_atividades')->insertGetId([
            'id_professor' => $professor->id_professor,
            'id_curso' => $courseId,
            'titulo_atividade' => 'Atividade aluno',
            'descricao_atividade' => 'Teste',
            'tipo_atividade' => 'texto',
            'status_atividade' => 'ATIVA',
        ]);
        $questionId = DB::table('tbl_atividade_questoes')->insertGetId([
            'id_atividade' => $activityId,
            'enunciado' => 'Questão teste',
            'tipo_questao' => 'texto',
            'resposta_correta' => 'A',
            'ordem' => 1,
        ], 'id_questao');
        $responseId = DB::table('tbl_atividade_respostas')->insertGetId([
            'id_atividade' => $activityId,
            'id_aluno' => $studentId,
            'status_resposta' => 'CORRIGIDA',
            'nota' => 9,
        ]);
        DB::table('tbl_atividade_resposta_questoes')->insert([
            'id_resposta' => $responseId,
            'id_questao' => $questionId,
            'resposta_aluno' => 'B',
            'correta' => 0,
        ]);
        $pendingResponseId = DB::table('tbl_atividade_respostas')->insertGetId([
            'id_atividade' => $activityId,
            'id_aluno' => $studentId,
            'status_resposta' => 'ENVIADA',
            'nota' => null,
        ]);
        DB::table('tbl_atividade_resposta_questoes')->insert([
            'id_resposta' => $pendingResponseId,
            'id_questao' => $questionId,
            'resposta_aluno' => 'C',
            'correta' => 0,
        ]);
        $this->actingAs(\App\Models\Aluno::find($studentId), 'aluno');

        $questions = app(ChatbotResponseService::class)->respond('Quais questões eu errei?', 'aluno');
        $performance = app(ChatbotResponseService::class)->respond('Como fui nas atividades?', 'aluno');
        $completed = app(ChatbotResponseService::class)->respond('Quais atividades eu respondi?', 'aluno');

        $this->assertSame(1, $questions['context']['questions_answered']);
        $this->assertSame('Atividade aluno', $questions['context']['questions'][0]['activity']);
        $this->assertSame(1, $performance['context']['activities_answered']);
        $this->assertSame(1, $performance['context']['incorrect_answers']);
        $this->assertCount(2, $completed['context']['activities']);
        $this->assertNull($completed['context']['activities'][1]['grade']);
    }

    private int $classId;

    private function seedClass(): array
    {
        $professorId = DB::table('tbl_professor')->insertGetId([
            'nome_professor' => 'Professor Métricas',
            'especialidade_professor' => 'Inglês',
            'experiencia_professor' => '5 anos',
            'bio_professor' => 'Teste',
            'foto_professor' => 'foto.jpg',
            'email_professor' => uniqid('prof') . '@example.test',
            'curso_professor' => 'Inglês',
            'nivel_professor' => 'Avançado',
            'telefone_professor' => '11999999999',
            'senha_professor' => 'secret',
        ]);
        $courseId = DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Teste']);
        $levelId = DB::table('tbl_niveis')->insertGetId(['nome_nivel' => 'Básico']);
        $studentId = DB::table('tbl_alunos')->insertGetId([
            'nome_aluno' => 'Caio Ferreira',
            'email_aluno' => uniqid('caio') . '@example.test',
            'senha_aluno' => 'secret',
            'telefone_aluno' => '11999999999',
            'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01',
            'nivel_aluno' => 'Básico',
            'foto_aluno' => 'foto.jpg',
            'status_aluno' => 'EM CURSO',
        ]);
        $this->classId = DB::table('tbl_aulas')->insertGetId([
            'id_professor' => $professorId, 'id_curso' => $courseId,
            'titulo_aulas' => 'Aula teste', 'descricao_aulas' => 'Teste',
            'data_aulas' => '2026-09-16', 'hora_aulas' => '10:00:00',
            'cursos_aulas' => 'Inglês', 'status_aulas' => 'ATIVO',
            'criado_em_aulas' => now(), 'atualizado_em_aulas' => now(),
        ]);
        DB::table('tbl_matricula')->insert([
            'id_aluno' => $studentId, 'id_curso' => $courseId, 'id_nivel' => $levelId,
            'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        return [Professor::find($professorId), $studentId, $courseId];
    }
}
