<?php

namespace Tests\Feature\Chatbot;

use App\Models\Professor;
use App\Services\ChatbotResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_professor_receives_only_authorized_student_data(): void
    {
        Http::fake();
        [$professor, $student] = $this->seedAuthorizedStudent();
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)
            ->respond('me dê informações do aluno Caio', 'professor');

        $this->assertSame('database', $response['source']);
        $this->assertSame('private_student_data', $response['intent']);
        $this->assertSame('Caio Ferreira', $response['context']['student']['name']);
        $this->assertSame('EM CURSO', $response['context']['student']['status']);
        $this->assertNotContains('email_aluno', $response['context']['student']);
        Http::assertNothingSent();
    }

    public function test_professor_cannot_receive_data_from_student_outside_scope(): void
    {
        Http::fake();
        [$professor] = $this->seedAuthorizedStudent();
        DB::table('tbl_alunos')->insert($this->studentRow('Fora do Escopo', 'fora@example.test', 'EM CURSO'));
        $this->actingAs($professor, 'admin');

        $response = app(ChatbotResponseService::class)
            ->respond('me dê informações do aluno Fora do Escopo', 'professor');

        $this->assertSame('private_student_data', $response['intent']);
        $this->assertStringContainsString('Por segurança', $response['message']);
        Http::assertNothingSent();
    }

    public function test_student_and_visitor_cannot_receive_private_student_data(): void
    {
        Http::fake();
        $this->assertStringContainsString(
            'Por segurança',
            app(ChatbotResponseService::class)
                ->respond('me dê informações do aluno Caio', 'visitante')['message']
        );

        DB::table('tbl_alunos')->insert($this->studentRow('Aluno A', 'alunoa@example.test', 'EM CURSO'));
        DB::table('tbl_alunos')->insert($this->studentRow('Aluno B', 'alunob@example.test', 'EM CURSO'));
        $student = DB::table('tbl_alunos')->where('email_aluno', 'alunoa@example.test')->first();
        $this->actingAs(\App\Models\Aluno::find($student->id_aluno), 'aluno');

        $response = app(ChatbotResponseService::class)
            ->respond('me dê informações do aluno Aluno B', 'aluno');

        $this->assertStringContainsString('Por segurança', $response['message']);
        Http::assertNothingSent();
    }

    private function seedAuthorizedStudent(): array
    {
        $professorId = DB::table('tbl_professor')->insertGetId($this->professorRow());
        $professor = Professor::find($professorId);
        $courseId = DB::table('tbl_cursos')->insertGetId([
            'nome_curso' => 'Inglês',
            'descricao_curso' => 'Curso de teste',
        ]);
        $levelId = DB::table('tbl_niveis')->insertGetId([
            'nome_nivel' => 'Básico',
        ]);
        $studentId = DB::table('tbl_alunos')->insertGetId($this->studentRow('Caio Ferreira', 'caio@example.test', 'EM CURSO'));

        DB::table('tbl_aulas')->insert([
            'id_professor' => $professorId,
            'id_curso' => $courseId,
            'titulo_aulas' => 'Aula',
            'descricao_aulas' => 'Aula de teste',
            'data_aulas' => now()->toDateString(),
            'hora_aulas' => '10:00:00',
            'cursos_aulas' => 'Inglês',
            'status_aulas' => 'ATIVO',
            'criado_em_aulas' => now(),
            'atualizado_em_aulas' => now(),
        ]);
        DB::table('tbl_matricula')->insert([
            'id_aluno' => $studentId,
            'id_curso' => $courseId,
            'id_nivel' => $levelId,
            'data_matricula' => now(),
            'status_matricula' => 'ATIVO',
        ]);

        return [$professor, $studentId];
    }

    private function professorRow(): array
    {
        return [
            'nome_professor' => 'Professor Teste',
            'especialidade_professor' => 'Inglês',
            'experiencia_professor' => '5 anos',
            'bio_professor' => 'Professor de teste',
            'foto_professor' => 'foto.jpg',
            'email_professor' => 'professor' . uniqid() . '@example.test',
            'curso_professor' => 'Inglês',
            'nivel_professor' => 'Avançado',
            'telefone_professor' => '11999999999',
            'senha_professor' => 'secret',
        ];
    }

    private function studentRow(string $name, string $email, string $status): array
    {
        return [
            'nome_aluno' => $name,
            'email_aluno' => $email,
            'senha_aluno' => 'secret',
            'telefone_aluno' => '11999999999',
            'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01',
            'nivel_aluno' => 'Básico',
            'foto_aluno' => 'foto.jpg',
            'status_aluno' => $status,
        ];
    }
}
