<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\AtividadeQuestao;
use App\Models\AtividadeResposta;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: tela Atividades (listar, abrir, ouvir áudio e responder).
 */
class AppFase5AtividadesTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Nivel $nivel;
    private Curso $ingles;
    private Curso $italiano;
    private Aluno $aluno;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->professor = Professor::create([
            'nome_professor' => 'Renato Caetano', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '10',
            'bio_professor' => 'Bio', 'foto_professor' => '', 'email_professor' => 'renato@prof.test',
            'curso_professor' => 'Inglês', 'nivel_professor' => 'Todos', 'telefone_professor' => '1',
            'senha_professor' => Hash::make('segredo123'),
        ]);

        $this->nivel = Nivel::create(['nome_nivel' => 'Intermediário']);
        $this->ingles = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));
        $this->italiano = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Italiano', 'descricao_curso' => 'Italiano']));

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);

        // Matriculado só em Inglês.
        Matricula::create([
            'id_aluno' => $this->aluno->id_aluno, 'id_curso' => $this->ingles->id_curso,
            'id_nivel' => $this->nivel->id_nivel, 'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        $this->token = $this->aluno->createToken('app')->plainTextToken;
    }

    private function atividade(array $extra = []): Atividade
    {
        return Atividade::create($extra + [
            'id_professor'         => $this->professor->id_professor,
            'id_curso'             => $this->ingles->id_curso,
            'titulo_atividade'     => 'Conjunção de verbo',
            'descricao_atividade'  => 'Exercício de verbo',
            'categoria_atividade'  => 'GRAMATICA',
            'finalidade_atividade' => 'FIXACAO',
            'data_entrega'         => '2026-07-30',
            'status_atividade'     => 'ATIVA',
        ]);
    }

    /** Atividade com 1 questão de múltipla escolha (certa = B) e 1 de texto. */
    private function atividadeComQuestoes(): array
    {
        $atividade = $this->atividade();

        $mc = AtividadeQuestao::create([
            'id_atividade' => $atividade->id_atividade, 'enunciado' => 'She ___ a dress.',
            'tipo_questao' => 'multipla_escolha', 'opcao_a' => 'have', 'opcao_b' => 'has',
            'opcao_c' => 'having', 'opcao_d' => null, 'resposta_correta' => 'B', 'ordem' => 1,
        ]);
        $texto = AtividadeQuestao::create([
            'id_atividade' => $atividade->id_atividade, 'enunciado' => 'Descreva seu dia.',
            'tipo_questao' => 'texto', 'ordem' => 2,
        ]);

        return [$atividade, $mc, $texto];
    }

    public function test_exige_token(): void
    {
        $this->getJson('/api/v1/aluno/atividades')->assertUnauthorized();
    }

    public function test_lista_atividades_do_curso_com_resumo(): void
    {
        $this->atividade(['titulo_atividade' => 'Pendente']);
        $enviada = $this->atividade(['titulo_atividade' => 'Enviada', 'data_entrega' => '2026-08-15']);
        AtividadeResposta::create([
            'id_atividade' => $enviada->id_atividade, 'id_aluno' => $this->aluno->id_aluno,
            'status_resposta' => 'ENVIADA', 'data_envio' => now(),
        ]);
        $corrigida = $this->atividade(['titulo_atividade' => 'Corrigida', 'data_entrega' => '2026-09-01']);
        AtividadeResposta::create([
            'id_atividade' => $corrigida->id_atividade, 'id_aluno' => $this->aluno->id_aluno,
            'status_resposta' => 'CORRIGIDA', 'nota' => 8.5, 'data_envio' => now(),
        ]);

        // Não aparecem: inativa e de curso sem matrícula.
        $this->atividade(['titulo_atividade' => 'Inativa', 'status_atividade' => 'INATIVA']);
        $this->atividade(['titulo_atividade' => 'Italiano', 'id_curso' => $this->italiano->id_curso]);

        $resposta = $this->withToken($this->token)->getJson('/api/v1/aluno/atividades')
            ->assertOk()
            ->assertJsonPath('data.total', 3)
            ->assertJsonPath('data.concluidas', 2)
            ->assertJsonPath('data.pendentes', 1);

        $lista = collect($resposta->json('data.atividades'))->keyBy('titulo');
        $this->assertSame(['Pendente', 'Enviada', 'Corrigida'], $lista->keys()->all());
        $this->assertSame('pendente', $lista['Pendente']['status']);
        $this->assertSame('enviada', $lista['Enviada']['status']);
        $this->assertNull($lista['Enviada']['nota']);
        $this->assertSame('corrigida', $lista['Corrigida']['status']);
        $this->assertEquals(8.5, $lista['Corrigida']['nota']);
        $this->assertSame('Gramática', $lista['Pendente']['categoria']['label']);
        $this->assertSame('Exercício de fixação', $lista['Pendente']['finalidade']);
        $this->assertSame('Renato Caetano', $lista['Pendente']['professor']);
        $this->assertSame('Inglês', $lista['Pendente']['curso']);
    }

    public function test_abre_atividade_sem_mostrar_resposta_certa(): void
    {
        [$atividade] = $this->atividadeComQuestoes();

        $resposta = $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$atividade->id_atividade}")
            ->assertOk()
            ->assertJsonPath('data.pode_responder', true)
            ->assertJsonPath('data.questoes.0.tipo', 'multipla_escolha')
            ->assertJsonPath('data.questoes.0.opcoes.1.letra', 'B')
            ->assertJsonPath('data.questoes.1.tipo', 'texto');

        // Opção D vazia não vai; a resposta certa nunca vai.
        $this->assertCount(3, $resposta->json('data.questoes.0.opcoes'));
        $this->assertStringNotContainsString('resposta_correta', $resposta->getContent());
    }

    public function test_responde_e_marca_acerto_da_multipla_escolha(): void
    {
        [$atividade, $mc, $texto] = $this->atividadeComQuestoes();

        $this->withToken($this->token)
            ->postJson("/api/v1/aluno/atividades/{$atividade->id_atividade}/responder", [
                'respostas' => [$mc->id_questao => 'B', $texto->id_questao => 'Acordei cedo.'],
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$atividade->id_atividade}")
            ->assertJsonPath('data.status', 'enviada')
            ->assertJsonPath('data.questoes.0.resposta_aluno', 'B')
            ->assertJsonPath('data.questoes.0.correta', true)
            ->assertJsonPath('data.questoes.1.resposta_aluno', 'Acordei cedo.')
            ->assertJsonPath('data.questoes.1.correta', null);
    }

    public function test_nao_reenvia_depois_de_corrigida(): void
    {
        [$atividade, $mc] = $this->atividadeComQuestoes();
        AtividadeResposta::create([
            'id_atividade' => $atividade->id_atividade, 'id_aluno' => $this->aluno->id_aluno,
            'status_resposta' => 'CORRIGIDA', 'nota' => 10, 'feedback_professor' => 'Muito bem!',
        ]);

        $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$atividade->id_atividade}")
            ->assertJsonPath('data.pode_responder', false)
            ->assertJsonPath('data.feedback_professor', 'Muito bem!');

        $this->withToken($this->token)
            ->postJson("/api/v1/aluno/atividades/{$atividade->id_atividade}/responder", [
                'respostas' => [$mc->id_questao => 'A'],
            ])
            ->assertStatus(409);
    }

    public function test_atividade_de_outro_curso_e_inexistente_dao_404(): void
    {
        $italiano = $this->atividade(['id_curso' => $this->italiano->id_curso]);

        $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$italiano->id_atividade}")->assertNotFound();
        $this->withToken($this->token)->getJson('/api/v1/aluno/atividades/999999')->assertNotFound();
        $this->withToken($this->token)
            ->postJson("/api/v1/aluno/atividades/{$italiano->id_atividade}/responder", ['respostas' => ['1' => 'A']])
            ->assertNotFound();
    }

    public function test_audio_inexistente_da_404(): void
    {
        $semAudio = $this->atividade();
        $arquivoSumiu = $this->atividade(['arquivo_audio' => 'traducaidiomas/atividades/nao-existe.mp3']);

        $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$arquivoSumiu->id_atividade}")
            ->assertJsonPath('data.tem_audio', true)
            ->assertJsonPath('data.extensao_audio', 'mp3');

        $this->withToken($this->token)->get("/api/v1/aluno/atividades/{$semAudio->id_atividade}/audio")->assertNotFound();
        $this->withToken($this->token)->get("/api/v1/aluno/atividades/{$arquivoSumiu->id_atividade}/audio")->assertNotFound();
    }
}
