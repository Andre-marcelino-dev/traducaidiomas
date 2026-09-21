<?php

namespace Tests\Feature\Chatbot;

use App\Models\Professor;
use App\Services\ChatbotResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChatbotFollowUpTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_history_asks_which_question_should_be_consulted(): void
    {
        $professor = $this->seedProfessor();
        $this->actingAs($professor, 'admin');

        $result = app(ChatbotResponseService::class)->respondQuestionFollowUp('qual?', 'professor', []);

        $this->assertSame('teacher_question_performance', $result['intent']);
        $this->assertStringContainsString('Qual questão', $result['message']);
    }

    public function test_follow_up_uses_previous_question_and_reapplies_teacher_scope(): void
    {
        $professor = $this->seedProfessor();
        $courseId = DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Teste']);
        $levelId = DB::table('tbl_niveis')->insertGetId(['nome_nivel' => 'Básico']);
        $studentId = DB::table('tbl_alunos')->insertGetId([
            'nome_aluno' => 'Aluno Contexto', 'email_aluno' => uniqid('context') . '@example.test', 'senha_aluno' => 'x',
            'telefone_aluno' => '1', 'curso_aluno' => 'Inglês', 'data_nasc_aluno' => '2000-01-01',
            'nivel_aluno' => 'Básico', 'foto_aluno' => 'x', 'status_aluno' => 'EM CURSO',
        ]);
        DB::table('tbl_matricula')->insert(['id_aluno' => $studentId, 'id_curso' => $courseId, 'id_nivel' => $levelId, 'data_matricula' => now(), 'status_matricula' => 'ATIVO']);
        DB::table('tbl_aulas')->insert(['id_professor' => $professor->id_professor, 'id_curso' => $courseId, 'titulo_aulas' => 'Aula contexto', 'descricao_aulas' => 'A', 'data_aulas' => '2026-09-16', 'hora_aulas' => '10:00:00', 'cursos_aulas' => 'Inglês', 'status_aulas' => 'ATIVO', 'criado_em_aulas' => now(), 'atualizado_em_aulas' => now()]);
        $activityId = DB::table('tbl_atividades')->insertGetId(['id_professor' => $professor->id_professor, 'id_curso' => $courseId, 'titulo_atividade' => 'A', 'descricao_atividade' => 'A', 'tipo_atividade' => 'texto', 'status_atividade' => 'ATIVA']);
        $questionId = DB::table('tbl_atividade_questoes')->insertGetId(['id_atividade' => $activityId, 'enunciado' => 'Enunciado contexto', 'tipo_questao' => 'texto', 'resposta_correta' => 'A', 'ordem' => 1], 'id_questao');
        $answerId = DB::table('tbl_atividade_respostas')->insertGetId(['id_atividade' => $activityId, 'id_aluno' => $studentId, 'status_resposta' => 'CORRIGIDA', 'nota' => 5], 'id_resposta');
        DB::table('tbl_atividade_resposta_questoes')->insert(['id_resposta' => $answerId, 'id_questao' => $questionId, 'resposta_aluno' => 'B', 'correta' => 0]);
        $this->actingAs($professor, 'admin');

        $result = app(ChatbotResponseService::class)->respondQuestionFollowUp('quantos erraram', 'professor', [
            ['role' => 'user', 'content' => 'quais questoes erraram'],
        ]);

        $this->assertSame('database-follow-up', $result['source']);
        $this->assertStringContainsString('Erros:', $result['message']);

        DB::table('tbl_matricula')->update(['status_matricula' => 'CANCELADO']);
        $blocked = app(ChatbotResponseService::class)->respondQuestionFollowUp('quantos erraram', 'professor', [
            ['role' => 'user', 'content' => 'quais questoes erraram'],
        ]);
        $this->assertStringContainsString('Não encontrei questões erradas', $blocked['message']);
    }

    public function test_unrelated_message_does_not_reuse_previous_context(): void
    {
        $professor = $this->seedProfessor();
        $this->actingAs($professor, 'admin');
        $result = app(ChatbotResponseService::class)->respondQuestionFollowUp('qual o preço do curso?', 'professor', [
            ['role' => 'user', 'content' => 'quais questoes erraram'],
        ]);
        $this->assertNull($result);
    }

    private function seedProfessor(): Professor
    {
        $id = DB::table('tbl_professor')->insertGetId(['nome_professor' => 'Professor Contexto', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '1', 'bio_professor' => 'x', 'foto_professor' => 'x', 'email_professor' => uniqid('p') . '@example.test', 'curso_professor' => 'Inglês', 'nivel_professor' => 'Básico', 'telefone_professor' => '1', 'senha_professor' => 'x']);
        return Professor::find($id);
    }
}
