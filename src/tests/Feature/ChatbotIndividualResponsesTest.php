<?php

namespace Tests\Feature\Chatbot;

use App\Models\Professor;
use App\Services\ChatbotResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChatbotIndividualResponsesTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_receives_correct_and_incorrect_answers_only_for_authorized_student(): void
    {
        [$professor, $studentId, $activityId] = $this->seedClass();
        $questionId = $this->seedQuestion($activityId, 'Qual é a resposta correta?');
        $responseId = $this->seedAnswer($activityId, $studentId);
        DB::table('tbl_atividade_resposta_questoes')->insert([
            ['id_resposta' => $responseId, 'id_questao' => $questionId, 'resposta_aluno' => 'B', 'correta' => 1],
        ]);
        $this->actingAs($professor, 'admin');

        $correct = app(ChatbotResponseService::class)->respond('quais questoes acertaram', 'professor');

        $this->assertSame('teacher_question_performance', $correct['intent']);
        $this->assertSame(1, $correct['context']['questions_answered']);
        $this->assertSame('B', $correct['context']['questions'][0]['student_answer']);

        DB::table('tbl_atividade_resposta_questoes')->update(['correta' => 0]);
        $incorrect = app(ChatbotResponseService::class)->respond('quais questoes erraram', 'professor');

        $this->assertSame(1, $incorrect['context']['questions_answered']);
        $this->assertSame('incorrect', $incorrect['context']['questions'][0]['result']);
    }

    public function test_teacher_does_not_receive_questions_from_another_professors_scope(): void
    {
        [$professor, $studentId, $activityId] = $this->seedClass();
        $questionId = $this->seedQuestion($activityId, 'Questão privada');
        $responseId = $this->seedAnswer($activityId, $studentId);
        DB::table('tbl_atividade_resposta_questoes')->insert([
            'id_resposta' => $responseId, 'id_questao' => $questionId,
            'resposta_aluno' => 'A', 'correta' => 0,
        ]);
        $otherProfessorId = DB::table('tbl_professor')->insertGetId([
            'nome_professor' => 'Outro Professor', 'especialidade_professor' => 'Inglês',
            'experiencia_professor' => '1 ano', 'bio_professor' => 'Teste', 'foto_professor' => 'foto.jpg',
            'email_professor' => uniqid('other') . '@example.test', 'curso_professor' => 'Inglês',
            'nivel_professor' => 'Básico', 'telefone_professor' => '11999999998', 'senha_professor' => 'secret',
        ]);
        $this->actingAs(Professor::find($otherProfessorId), 'admin');

        $result = app(ChatbotResponseService::class)->respond('quais questoes erraram', 'professor');

        $this->assertSame(0, $result['context']['questions_answered']);
        $this->assertStringContainsString('Não encontrei questões erradas', $result['message']);
    }

    public function test_student_cannot_use_teacher_question_response_query(): void
    {
        [, $studentId] = $this->seedClass();
        $student = \App\Models\Aluno::find($studentId);
        $this->actingAs($student, 'aluno');

        $result = app(ChatbotResponseService::class)->respond('quais questoes erraram', 'aluno');

        $this->assertStringContainsString('apenas para professores autenticados', $result['message']);
    }

    public function test_teacher_gets_empty_result_when_authorized_student_has_no_answers(): void
    {
        [$professor] = $this->seedClass();
        $this->actingAs($professor, 'admin');

        $result = app(ChatbotResponseService::class)->respond('quais questoes acertaram', 'professor');

        $this->assertSame(0, $result['context']['questions_answered']);
        $this->assertStringContainsString('Não encontrei questões acertadas', $result['message']);
    }

    private function seedClass(): array
    {
        $professorId = DB::table('tbl_professor')->insertGetId([
            'nome_professor' => 'Professor Respostas', 'especialidade_professor' => 'Inglês',
            'experiencia_professor' => '5 anos', 'bio_professor' => 'Teste', 'foto_professor' => 'foto.jpg',
            'email_professor' => uniqid('prof') . '@example.test', 'curso_professor' => 'Inglês',
            'nivel_professor' => 'Avançado', 'telefone_professor' => '11999999999', 'senha_professor' => 'secret',
        ]);
        $courseId = DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Teste']);
        $levelId = DB::table('tbl_niveis')->insertGetId(['nome_nivel' => 'Básico']);
        $studentId = DB::table('tbl_alunos')->insertGetId([
            'nome_aluno' => 'Caio Ferreira', 'email_aluno' => uniqid('caio') . '@example.test',
            'senha_aluno' => 'secret', 'telefone_aluno' => '11999999999', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Básico', 'foto_aluno' => 'foto.jpg',
            'status_aluno' => 'EM CURSO',
        ]);
        $activityId = DB::table('tbl_atividades')->insertGetId([
            'id_professor' => $professorId, 'id_curso' => $courseId, 'titulo_atividade' => 'Atividade respostas',
            'descricao_atividade' => 'Teste', 'tipo_atividade' => 'texto', 'status_atividade' => 'ATIVA',
        ]);
        DB::table('tbl_matricula')->insert([
            'id_aluno' => $studentId, 'id_curso' => $courseId, 'id_nivel' => $levelId,
            'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        return [Professor::find($professorId), $studentId, $activityId];
    }

    private function seedQuestion(int $activityId, string $statement): int
    {
        return DB::table('tbl_atividade_questoes')->insertGetId([
            'id_atividade' => $activityId, 'enunciado' => $statement, 'tipo_questao' => 'multipla_escolha',
            'opcao_a' => 'A', 'opcao_b' => 'B', 'resposta_correta' => 'B', 'ordem' => 1,
        ], 'id_questao');
    }

    private function seedAnswer(int $activityId, int $studentId): int
    {
        return DB::table('tbl_atividade_respostas')->insertGetId([
            'id_atividade' => $activityId, 'id_aluno' => $studentId,
            'status_resposta' => 'CORRIGIDA', 'nota' => 8.0,
        ], 'id_resposta');
    }
}
