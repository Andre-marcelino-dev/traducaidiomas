<?php

namespace Tests\Feature\Chatbot;

use App\Models\Aluno;
use App\Models\Professor;
use App\Services\ChatbotResponseService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChatbotClassesTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_student_receives_class_scheduled_for_today(): void
    {
        [$student, $professor, $courseId] = $this->seedStudentContext();
        Carbon::setTestNow('2026-09-16 09:00:00');
        $this->seedClass($professor->id_professor, $courseId, '2026-09-16', '10:00:00', 'Aula de hoje');
        $this->actingAs($student, 'aluno');

        $result = app(ChatbotResponseService::class)->respond('aulas hoje', 'aluno');

        $this->assertStringContainsString('16/09/2026', $result['message']);
        $this->assertStringContainsString('Aula de hoje', $result['message']);
    }

    public function test_next_class_is_the_earliest_future_class_and_ignores_past_class(): void
    {
        [$student, $professor, $courseId] = $this->seedStudentContext();
        Carbon::setTestNow('2026-09-16 09:00:00');
        $this->seedClass($professor->id_professor, $courseId, '2026-09-15', '10:00:00', 'Aula passada');
        $this->seedClass($professor->id_professor, $courseId, '2026-09-18', '10:00:00', 'Aula mais distante');
        $this->seedClass($professor->id_professor, $courseId, '2026-09-17', '10:00:00', 'Próxima aula');
        $this->actingAs($student, 'aluno');

        $result = app(ChatbotResponseService::class)->respond('qual é minha próxima aula', 'aluno');

        $this->assertStringContainsString('Próxima aula', $result['message']);
        $this->assertStringNotContainsString('Aula mais distante', $result['message']);
        $this->assertStringNotContainsString('Aula passada', $result['message']);
    }

    public function test_today_without_class_returns_the_real_empty_schedule_message(): void
    {
        [$student] = $this->seedStudentContext();
        Carbon::setTestNow('2026-09-16 09:00:00');
        $this->actingAs($student, 'aluno');

        $result = app(ChatbotResponseService::class)->respond('aulas hoje', 'aluno');

        $this->assertStringContainsString('Não há aulas agendadas para hoje', $result['message']);
    }

    public function test_student_schedule_does_not_include_a_class_from_another_course(): void
    {
        [$student, $professor, $courseId] = $this->seedStudentContext();
        $otherCourseId = DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Espanhol', 'descricao_curso' => 'Outro']);
        Carbon::setTestNow('2026-09-16 09:00:00');
        $this->seedClass($professor->id_professor, $otherCourseId, '2026-09-17', '10:00:00', 'Aula de outro curso');
        $this->actingAs($student, 'aluno');

        $result = app(ChatbotResponseService::class)->respond('qual é minha próxima aula', 'aluno');

        $this->assertStringContainsString('Não encontrei próximas aulas cadastradas para você', $result['message']);
        $this->assertStringNotContainsString('Aula de outro curso', $result['message']);
    }

    public function test_teacher_schedule_is_limited_to_classes_owned_by_authenticated_teacher(): void
    {
        [, $professor, $courseId] = $this->seedStudentContext();
        $otherProfessorId = DB::table('tbl_professor')->insertGetId($this->professorData('outro'));
        Carbon::setTestNow('2026-09-16 09:00:00');
        $this->seedClass($otherProfessorId, $courseId, '2026-09-17', '10:00:00', 'Aula de outro professor');
        $this->actingAs($professor, 'admin');

        $result = app(ChatbotResponseService::class)->respond('qual é minha próxima aula', 'professor');

        $this->assertStringContainsString('Não encontrei próximas aulas cadastradas para você', $result['message']);
        $this->assertStringNotContainsString('Aula de outro professor', $result['message']);
    }

    private function seedStudentContext(): array
    {
        $professorId = DB::table('tbl_professor')->insertGetId($this->professorData('principal'));
        $courseId = DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Teste']);
        $levelId = DB::table('tbl_niveis')->insertGetId(['nome_nivel' => 'Básico']);
        $studentId = DB::table('tbl_alunos')->insertGetId([
            'nome_aluno' => 'Aluno Agenda', 'email_aluno' => uniqid('agenda') . '@example.test',
            'senha_aluno' => 'secret', 'telefone_aluno' => '11999999999', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Básico', 'foto_aluno' => 'foto.jpg',
            'status_aluno' => 'EM CURSO',
        ]);
        DB::table('tbl_matricula')->insert([
            'id_aluno' => $studentId, 'id_curso' => $courseId, 'id_nivel' => $levelId,
            'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        return [Aluno::find($studentId), Professor::find($professorId), $courseId];
    }

    private function seedClass(int $professorId, int $courseId, string $date, string $time, string $title): int
    {
        return DB::table('tbl_aulas')->insertGetId([
            'id_professor' => $professorId, 'id_curso' => $courseId, 'titulo_aulas' => $title,
            'descricao_aulas' => 'Teste', 'data_aulas' => $date, 'hora_aulas' => $time,
            'cursos_aulas' => 'Inglês', 'status_aulas' => 'ATIVO',
            'criado_em_aulas' => now(), 'atualizado_em_aulas' => now(),
        ]);
    }

    private function professorData(string $suffix): array
    {
        return [
            'nome_professor' => 'Professor ' . $suffix, 'especialidade_professor' => 'Inglês',
            'experiencia_professor' => '5 anos', 'bio_professor' => 'Teste', 'foto_professor' => 'foto.jpg',
            'email_professor' => uniqid($suffix) . '@example.test', 'curso_professor' => 'Inglês',
            'nivel_professor' => 'Avançado', 'telefone_professor' => '11999999999', 'senha_professor' => 'secret',
        ];
    }
}
