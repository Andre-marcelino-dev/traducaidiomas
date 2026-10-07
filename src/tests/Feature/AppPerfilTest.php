<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: tela Perfil (dados, e-mail, foto) e modal "Alterar senha".
 */
class AppPerfilTest extends TestCase
{
    use RefreshDatabase;

    private Aluno $aluno;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Caio Teste', 'email_aluno' => 'caio@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '(11) 98888-7777', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'iniciante', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);

        $nivel = Nivel::create(['nome_nivel' => 'iniciante']);
        $curso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));
        Matricula::create([
            'id_aluno' => $this->aluno->id_aluno, 'id_curso' => $curso->id_curso,
            'id_nivel' => $nivel->id_nivel, 'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        $this->token = $this->aluno->createToken('app')->plainTextToken;
    }

    public function test_mostra_dados_do_perfil(): void
    {
        $this->withToken($this->token)->getJson('/api/v1/aluno/perfil')
            ->assertOk()
            ->assertJsonPath('data.nome_aluno', 'Caio Teste')
            ->assertJsonPath('data.telefone_aluno', '(11) 98888-7777')
            ->assertJsonPath('data.cursos.0.nome_curso', 'Inglês')
            ->assertJsonPath('data.cursos.0.nome_nivel', 'iniciante')
            ->assertJsonMissingPath('data.senha_aluno');
    }

    public function test_troca_senha_com_senha_atual_e_desconecta_outros_aparelhos(): void
    {
        $outro = $this->aluno->createToken('outro-aparelho')->accessToken;

        $this->withToken($this->token)->putJson('/api/v1/aluno/perfil/senha', [
            'senha_atual' => 'segredo123', 'nova_senha' => 'novaSenha9', 'nova_senha_confirmation' => 'novaSenha9',
        ])->assertOk()->assertJsonPath('success', true);

        $this->assertTrue(Hash::check('novaSenha9', $this->aluno->fresh()->senha_aluno));

        $ids = $this->aluno->tokens()->pluck('id');
        $this->assertNotContains($outro->id, $ids); // outro aparelho saiu
        $this->assertCount(1, $ids);                // este continua
    }

    public function test_senha_atual_errada_ou_confirmacao_diferente_nao_troca(): void
    {
        $this->withToken($this->token)->putJson('/api/v1/aluno/perfil/senha', [
            'senha_atual' => 'errada', 'nova_senha' => 'novaSenha9', 'nova_senha_confirmation' => 'novaSenha9',
        ])->assertStatus(422)->assertJsonValidationErrors('senha_atual');

        $this->withToken($this->token)->putJson('/api/v1/aluno/perfil/senha', [
            'senha_atual' => 'segredo123', 'nova_senha' => 'novaSenha9', 'nova_senha_confirmation' => 'outra',
        ])->assertStatus(422)->assertJsonValidationErrors('nova_senha');

        $this->assertTrue(Hash::check('segredo123', $this->aluno->fresh()->senha_aluno));
    }

    public function test_troca_email_exige_senha_e_email_livre(): void
    {
        Aluno::create([
            'nome_aluno' => 'Outro', 'email_aluno' => 'ocupado@aluno.test', 'senha_aluno' => Hash::make('x12345'),
            'telefone_aluno' => '1', 'curso_aluno' => 'Inglês', 'data_nasc_aluno' => '2000-01-01',
            'nivel_aluno' => 'iniciante', 'foto_aluno' => '', 'status_aluno' => 'EM CURSO',
        ]);

        $this->withToken($this->token)->putJson('/api/v1/aluno/perfil/email', ['email_aluno' => 'novo@aluno.test'])
            ->assertStatus(422)->assertJsonValidationErrors('senha_atual');

        $this->withToken($this->token)->putJson('/api/v1/aluno/perfil/email', [
            'email_aluno' => 'ocupado@aluno.test', 'senha_atual' => 'segredo123',
        ])->assertStatus(422)->assertJsonValidationErrors('email_aluno');

        $this->withToken($this->token)->putJson('/api/v1/aluno/perfil/email', [
            'email_aluno' => 'novo@aluno.test', 'senha_atual' => 'segredo123',
        ])->assertOk()->assertJsonPath('data.email_aluno', 'novo@aluno.test');
    }

    public function test_troca_foto(): void
    {
        $resposta = $this->withToken($this->token)->post('/api/v1/aluno/perfil/foto', [
            // PNG real de 1x1 (o PHP do Docker de teste não tem GD para gerar imagem).
            'foto_aluno' => UploadedFile::fake()->createWithContent('eu.png', base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
            )),
        ], ['Accept' => 'application/json'])->assertOk();

        $nome = $resposta->json('data.foto_aluno');
        $this->assertSame($nome, $this->aluno->fresh()->foto_aluno);

        $caminho = public_path('traducaidiomas/alunos/' . $nome);
        $this->assertFileExists($caminho);
        @unlink($caminho); // não deixa lixo do teste na pasta de fotos
    }

    public function test_foto_que_nao_e_imagem_e_recusada(): void
    {
        $this->withToken($this->token)->post('/api/v1/aluno/perfil/foto', [
            'foto_aluno' => UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf'),
        ], ['Accept' => 'application/json'])->assertStatus(422)->assertJsonValidationErrors('foto_aluno');
    }
}
