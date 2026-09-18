<?php

namespace Tests\Feature\Chatbot;

use App\Models\Professor;
use App\Services\ChatbotSemanticInterpreter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotGroqTest extends TestCase
{
    use RefreshDatabase;

    public function test_groq_success_response_is_returned_without_real_request(): void
    {
        $this->actingAs($this->seedProfessor(), 'admin');
        config(['services.groq.key' => 'test-key']);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['content' => 'Resposta simulada']]]], 200)]);
        $response = $this->postJson('/chatbot/mensagem', ['message' => 'explique o present perfect']);
        $response->assertOk()->assertJsonPath('message', 'Resposta simulada');
        Http::assertSent(fn ($request) => str_contains($request->url(), 'api.groq.com') && $request->header('Authorization')[0] === 'Bearer test-key');
    }

    public function test_groq_invalid_payload_is_reported_as_invalid_ai_response(): void
    {
        $this->actingAs($this->seedProfessor(), 'admin');
        config(['services.groq.key' => 'test-key']);
        Http::fake(['*' => Http::response(['unexpected' => true], 200)]);
        $response = $this->postJson('/chatbot/mensagem', ['message' => 'explique o present perfect']);
        $response->assertStatus(502)->assertJsonPath('message', 'Não recebi uma resposta válida da IA. Tente novamente.');
    }

    public function test_groq_connection_exception_returns_controlled_error(): void
    {
        $this->actingAs($this->seedProfessor(), 'admin');
        config(['services.groq.key' => 'test-key']);
        Http::fake(fn () => throw new ConnectionException('simulated connection failure'));
        $response = $this->postJson('/chatbot/mensagem', ['message' => 'explique o present perfect']);
        $response->assertStatus(503)->assertJsonPath('message', 'O serviço está indisponível no momento. Tente novamente mais tarde.');
    }

    public function test_semantic_interpreter_accepts_only_allowed_structured_intent(): void
    {
        config(['services.groq.key' => 'test-key']);
        $payload = json_encode(['intent' => 'teacher_student_classes', 'confidence' => 0.96, 'entities' => ['student_name' => 'Caio Ferreira', 'time_scope' => 'upcoming'], 'needs_clarification' => false, 'clarification_question' => null]);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['content' => $payload]]]], 200)]);
        $result = app(ChatbotSemanticInterpreter::class)->interpret('Que aulas o Caio tem?', 'professor');
        $this->assertSame('teacher_student_classes', $result['intent']);
        $this->assertSame('Caio Ferreira', $result['entities']['student_name']);
    }

    public function test_semantic_interpreter_rejects_invalid_intent_and_low_confidence(): void
    {
        config(['services.groq.key' => 'test-key']);
        Http::fakeSequence()
            ->push(['choices' => [['message' => ['content' => '{"intent":"run_sql","confidence":0.99}']]]], 200)
            ->push(['choices' => [['message' => ['content' => '{"intent":"teacher_students","confidence":0.4}']]]], 200);
        $interpreter = app(ChatbotSemanticInterpreter::class);
        $this->assertNull($interpreter->interpret('consulte o banco', 'professor'));
        $this->assertNull($interpreter->interpret('talvez meus alunos', 'professor'));
    }

    public function test_semantic_interpreter_preserves_ambiguity_as_clarification(): void
    {
        config(['services.groq.key' => 'test-key']);
        $payload = json_encode(['intent' => null, 'confidence' => 0.8, 'entities' => [], 'needs_clarification' => true, 'clarification_question' => 'VocÃª quer as prÃ³ximas aulas ou o histÃ³rico?']);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['content' => $payload]]]], 200)]);
        $result = app(ChatbotSemanticInterpreter::class)->interpret('Me fala das aulas do Caio.', 'professor');
        $this->assertTrue($result['needs_clarification']);
        $this->assertSame('VocÃª quer as prÃ³ximas aulas ou o histÃ³rico?', $result['clarification_question']);
    }

    private function seedProfessor(): Professor
    {
        $id = DB::table('tbl_professor')->insertGetId(['nome_professor' => 'Professor Groq', 'especialidade_professor' => 'InglÃªs', 'experiencia_professor' => '1', 'bio_professor' => 'x', 'foto_professor' => 'x', 'email_professor' => uniqid('g') . '@example.test', 'curso_professor' => 'InglÃªs', 'nivel_professor' => 'BÃ¡sico', 'telefone_professor' => '1', 'senha_professor' => 'x']);
        return Professor::find($id);
    }
}
