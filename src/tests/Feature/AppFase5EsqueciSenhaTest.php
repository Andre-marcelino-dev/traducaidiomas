<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\CodigoSenhaAluno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * API do traduca-APP: "Esqueci minha senha" (código de 6 dígitos por e-mail).
 */
class AppFase5EsqueciSenhaTest extends TestCase
{
    use RefreshDatabase;

    private Aluno $aluno;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('senhaAntiga123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);
    }

    public function test_esqueci_cria_codigo_quando_aluno_existe(): void
    {
        // Mail::raw() não é rastreável por Mail::assertSent() no fake do Laravel
        // (o método fica vazio em MailFake) — por isso o efeito testável aqui é o
        // código gravado no banco, não o envio em si.
        Mail::fake();

        $this->postJson('/api/v1/aluno/senha/esqueci', [
            'email_aluno' => $this->aluno->email_aluno,
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('tbl_codigo_senha_aluno', [
            'email_aluno' => $this->aluno->email_aluno,
            'usado' => false,
        ]);
    }

    public function test_esqueci_nao_revela_que_email_nao_existe(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/aluno/senha/esqueci', [
            'email_aluno' => 'naoexiste@aluno.test',
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        Mail::assertNothingSent();
        $this->assertDatabaseCount('tbl_codigo_senha_aluno', 0);
    }

    public function test_pedir_de_novo_invalida_o_codigo_anterior(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/aluno/senha/esqueci', ['email_aluno' => $this->aluno->email_aluno]);
        $antigo = CodigoSenhaAluno::where('email_aluno', $this->aluno->email_aluno)->first();

        $this->postJson('/api/v1/aluno/senha/esqueci', ['email_aluno' => $this->aluno->email_aluno]);

        $this->assertTrue($antigo->refresh()->usado);
        $this->assertDatabaseHas('tbl_codigo_senha_aluno', [
            'email_aluno' => $this->aluno->email_aluno,
            'usado' => false,
        ]);
    }

    public function test_redefinir_com_codigo_correto_troca_a_senha(): void
    {
        $codigo = CodigoSenhaAluno::create([
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'expira_em' => now()->addMinutes(30),
        ]);

        $this->postJson('/api/v1/aluno/senha/redefinir', [
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertTrue(Hash::check('senhaNova456', $this->aluno->refresh()->senha_aluno));
        $this->assertTrue($codigo->refresh()->usado);
    }

    public function test_redefinir_com_codigo_errado_e_rejeitado(): void
    {
        CodigoSenhaAluno::create([
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'expira_em' => now()->addMinutes(30),
        ]);

        $this->postJson('/api/v1/aluno/senha/redefinir', [
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '000000',
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('codigo');
    }

    public function test_redefinir_com_codigo_expirado_e_rejeitado(): void
    {
        CodigoSenhaAluno::create([
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'expira_em' => now()->subMinutes(1),
        ]);

        $this->postJson('/api/v1/aluno/senha/redefinir', [
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('codigo');
    }

    public function test_redefinir_com_codigo_ja_usado_e_rejeitado(): void
    {
        CodigoSenhaAluno::create([
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'expira_em' => now()->addMinutes(30),
            'usado' => true,
        ]);

        $this->postJson('/api/v1/aluno/senha/redefinir', [
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('codigo');
    }

    public function test_redefinir_revoga_tokens_antigos(): void
    {
        $token = $this->aluno->createToken('app')->plainTextToken;
        CodigoSenhaAluno::create([
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'expira_em' => now()->addMinutes(30),
        ]);

        $this->postJson('/api/v1/aluno/senha/redefinir', [
            'email_aluno' => $this->aluno->email_aluno,
            'codigo' => '123456',
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])->assertOk();

        $this->withToken($token)
            ->getJson('/api/v1/aluno/me')
            ->assertStatus(401);
    }
}
