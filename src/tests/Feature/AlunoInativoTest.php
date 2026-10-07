<?php

namespace Tests\Feature;

use App\Models\Aluno;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Aluno com status INATIVO não entra no site nem no app; quem já estava
 * logado perde o acesso. CONCLUIDO continua entrando.
 */
class AlunoInativoTest extends TestCase
{
    use RefreshDatabase;

    private function aluno(string $status): Aluno
    {
        return Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => $status,
        ]);
    }

    private function loginApi(Aluno $aluno, string $senha = 'segredo123')
    {
        return $this->postJson('/api/v1/aluno/login', ['email_aluno' => $aluno->email_aluno, 'senha_aluno' => $senha]);
    }

    public function test_app_login_de_aluno_inativo_e_recusado_sem_token(): void
    {
        $aluno = $this->aluno('INATIVO');

        $this->loginApi($aluno)
            ->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', Aluno::MENSAGEM_INATIVO)
            ->assertJsonMissingPath('data.token');

        $this->assertSame(0, $aluno->tokens()->count());
    }

    public function test_app_senha_errada_de_inativo_continua_dizendo_senha_invalida(): void
    {
        // Não revela que o cadastro existe/está inativo para quem não sabe a senha.
        $this->loginApi($this->aluno('INATIVO'), 'errada')
            ->assertStatus(401)
            ->assertJsonPath('message', 'Email ou senha inválidos.');
    }

    public function test_app_em_curso_e_concluido_continuam_entrando(): void
    {
        foreach (['EM CURSO', 'CONCLUIDO'] as $status) {
            $this->loginApi($this->aluno($status))->assertOk()->assertJsonPath('success', true);
        }
    }

    public function test_app_token_de_aluno_desativado_depois_para_de_funcionar(): void
    {
        $aluno = $this->aluno('EM CURSO');
        $token = $this->loginApi($aluno)->json('data.token');
        $aluno->createToken('outro-aparelho');

        $this->withToken($token)->getJson('/api/v1/aluno/me')->assertOk();

        $aluno->update(['status_aluno' => 'INATIVO']);

        // No teste o Laravel guarda o usuário do pedido anterior; num pedido real ele é lido de novo.
        $this->app['auth']->forgetGuards();

        $this->withToken($token)->getJson('/api/v1/aluno/me')
            ->assertUnauthorized()
            ->assertJsonPath('message', Aluno::MENSAGEM_INATIVO);

        // Todas as chaves dele foram apagadas (inclusive a do outro aparelho).
        $this->assertSame(0, $aluno->tokens()->count());
    }

    public function test_login_apaga_so_as_chaves_vencidas(): void
    {
        $aluno = $this->aluno('EM CURSO');

        $vencida = $aluno->createToken('celular-antigo')->accessToken;
        $vencida->forceFill(['created_at' => now()->subDays(31)])->save();
        $valendo = $aluno->createToken('outro-aparelho')->accessToken;

        $this->loginApi($aluno)->assertOk();

        $ids = $aluno->tokens()->pluck('id');
        $this->assertNotContains($vencida->id, $ids);   // vencida: apagada
        $this->assertContains($valendo->id, $ids);      // outro aparelho: continua
        $this->assertCount(2, $ids);                    // a que valia + a nova
    }

    public function test_site_login_de_aluno_inativo_e_recusado(): void
    {
        $aluno = $this->aluno('INATIVO');

        $this->from('/aluno/login')
            ->post('/aluno/login', ['email_aluno' => $aluno->email_aluno, 'senha_aluno' => 'segredo123'])
            ->assertRedirect('/aluno/login')
            ->assertSessionHas('error', Aluno::MENSAGEM_INATIVO);

        $this->assertGuest('aluno');
    }

    public function test_site_aluno_logado_e_desativado_sai_no_proximo_clique(): void
    {
        $aluno = $this->aluno('EM CURSO');
        $this->actingAs($aluno, 'aluno');

        $aluno->update(['status_aluno' => 'INATIVO']);

        $this->get('/aluno/perfil')
            ->assertRedirect(route('aluno.login'))
            ->assertSessionHas('error', Aluno::MENSAGEM_INATIVO);

        $this->assertGuest('aluno');
    }
}
