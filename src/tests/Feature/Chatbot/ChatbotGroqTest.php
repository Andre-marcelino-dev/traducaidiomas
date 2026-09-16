<?php

namespace Tests\Feature\Chatbot;

use App\Models\Professor;
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

    private function seedProfessor(): Professor
    {
        $id = DB::table('tbl_professor')->insertGetId(['nome_professor' => 'Professor Groq', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '1', 'bio_professor' => 'x', 'foto_professor' => 'x', 'email_professor' => uniqid('g') . '@example.test', 'curso_professor' => 'Inglês', 'nivel_professor' => 'Básico', 'telefone_professor' => '1', 'senha_professor' => 'x']);
        return Professor::find($id);
    }
}
