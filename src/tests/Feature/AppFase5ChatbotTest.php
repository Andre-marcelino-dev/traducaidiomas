<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * API do traduca-APP: Assistente (chat com IA). Sempre com Http::fake() —
 * nunca deve bater na Groq de verdade (cota paga).
 */
class AppFase5ChatbotTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Nivel $nivel;
    private Curso $curso;
    private Aluno $aluno;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.groq.key' => 'test-key']);

        $this->professor = Professor::create([
            'nome_professor' => 'Renato', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '10',
            'bio_professor' => 'Bio', 'foto_professor' => '', 'email_professor' => 'renato@prof.test',
            'curso_professor' => 'Inglês', 'nivel_professor' => 'Todos', 'telefone_professor' => '1',
            'senha_professor' => Hash::make('segredo123'),
        ]);

        $this->nivel = Nivel::create(['nome_nivel' => 'Intermediário']);
        $this->curso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Caio Ferreira', 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);

        Matricula::create([
            'id_aluno' => $this->aluno->id_aluno, 'id_curso' => $this->curso->id_curso,
            'id_nivel' => $this->nivel->id_nivel, 'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        $this->token = $this->aluno->createToken('app')->plainTextToken;
    }

    private function fakeGroq(string $conteudo): void
    {
        Http::fake([
            'api.groq.com/*' => Http::response([
                'choices' => [['message' => ['content' => $conteudo]]],
            ], 200),
        ]);
    }

    public function test_dados_devolve_perfil_aluno_e_nome(): void
    {
        $this->withToken($this->token)
            ->getJson('/api/v1/aluno/chatbot/dados')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('perfil', 'aluno')
            ->assertJsonPath('usuario.nome', 'Caio Ferreira');
    }

    public function test_mensagem_chama_groq_com_contexto_e_devolve_a_resposta(): void
    {
        $this->fakeGroq('Olá! O present perfect é usado para...');

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/chatbot/mensagem', ['mensagem' => 'o que é present perfect?'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('perfil', 'aluno')
            ->assertJsonPath('nome', 'Caio Ferreira')
            ->assertJsonPath('text', 'Olá! O present perfect é usado para...');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.groq.com')
                && $request->header('Authorization')[0] === 'Bearer test-key'
                && str_contains($request['messages'][0]['content'], 'Caio Ferreira')
                && str_contains($request['messages'][0]['content'], 'DADOS DO ALUNO');
        });
    }

    public function test_extrai_sugestoes_e_remove_do_texto_visivel(): void
    {
        $this->fakeGroq('Resposta normal. [SUGESTOES] ["Pergunta 1", "Pergunta 2", "Pergunta 3"] [/SUGESTOES]');

        $resposta = $this->withToken($this->token)
            ->postJson('/api/v1/aluno/chatbot/mensagem', ['mensagem' => 'oi'])
            ->assertOk();

        $this->assertSame('Resposta normal.', $resposta->json('text'));
        $this->assertSame(['Pergunta 1', 'Pergunta 2', 'Pergunta 3'], $resposta->json('sugestoes'));
    }

    public function test_card_de_agenda_quando_pergunta_sobre_aulas(): void
    {
        Aula::create([
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->curso->id_curso,
            'titulo_aulas' => 'Aula de conversação', 'descricao_aulas' => 'Desc',
            'data_aulas' => '2026-11-01', 'hora_aulas' => '19:00:00', 'cursos_aulas' => 'Inglês',
            'status_aulas' => 'ATIVO',
        ]);
        $this->fakeGroq('Sua próxima aula está marcada.');

        $resposta = $this->withToken($this->token)
            ->postJson('/api/v1/aluno/chatbot/mensagem', ['mensagem' => 'quais minhas próximas aulas?'])
            ->assertOk();

        $this->assertSame('schedule', $resposta->json('card.type'));
        $this->assertSame('Aula de conversação', $resposta->json('card.items.0.titulo'));
    }

    public function test_quando_groq_falha_devolve_resposta_offline(): void
    {
        Http::fake(['api.groq.com/*' => Http::response(['error' => 'boom'], 500)]);

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/chatbot/mensagem', ['mensagem' => 'qual meu progresso?'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('offline', true)
            ->assertJsonPath('card', null);
    }

    public function test_sem_chave_groq_devolve_erro(): void
    {
        config(['services.groq.key' => null]);

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/chatbot/mensagem', ['mensagem' => 'oi'])
            ->assertStatus(500)
            ->assertJsonPath('success', false);
    }

    public function test_mensagem_vazia_e_rejeitada(): void
    {
        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/chatbot/mensagem', ['mensagem' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors('mensagem');
    }
}
