<?php

namespace Tests\Feature;

use App\Models\CodigoSenhaProfessor;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Painel: "Esqueci minha senha" (código de 6 dígitos por e-mail).
 */
class AdminEsqueciSenhaTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->professor = Professor::create([
            'nome_professor'          => 'Prof ' . uniqid(),
            'especialidade_professor' => 'Inglês',
            'experiencia_professor'   => '5 anos',
            'bio_professor'           => 'Bio',
            'foto_professor'          => '',
            'email_professor'         => uniqid() . '@prof.test',
            'curso_professor'         => 'Inglês',
            'nivel_professor'         => 'Todos',
            'telefone_professor'      => '11999999999',
            'senha_professor'         => Hash::make('senhaAntiga123'),
        ]);
        $this->professor->forceFill(['is_admin' => true])->save();
    }

    private function pedirCodigo(): string
    {
        $this->post('/admin/senha/esqueci', ['email_professor' => $this->professor->email_professor])
            ->assertRedirect(route('admin.senha.redefinir'));

        return CodigoSenhaProfessor::where('email_professor', $this->professor->email_professor)
            ->latest('id_codigo')->value('codigo');
    }

    public function test_login_tem_link_para_esqueci_a_senha(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee(route('admin.senha.esqueci'));
        $this->get('/admin/senha/esqueci')->assertOk()->assertSee('Enviar código');
    }

    public function test_pedido_cria_codigo_e_redefinir_troca_a_senha(): void
    {
        $codigo = $this->pedirCodigo();

        $this->post('/admin/senha/redefinir', [
            'email_professor' => $this->professor->email_professor,
            'codigo' => $codigo,
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])->assertRedirect(route('admin.login'))->assertSessionHas('success');

        $this->assertTrue(Hash::check('senhaNova456', $this->professor->fresh()->senha_professor));

        // Com a senha nova, o login funciona.
        $this->post('/admin/login', [
            'email_professor' => $this->professor->email_professor,
            'senha_professor' => 'senhaNova456',
        ])->assertRedirect(route('admin.dash'));
    }

    public function test_email_inexistente_tem_a_mesma_resposta_e_nao_cria_codigo(): void
    {
        $this->post('/admin/senha/esqueci', ['email_professor' => 'naoexiste@prof.test'])
            ->assertRedirect(route('admin.senha.redefinir'))
            ->assertSessionHas('info', 'Se esse e-mail estiver cadastrado, você vai receber um código em instantes.');

        $this->assertSame(0, CodigoSenhaProfessor::count());
    }

    public function test_codigo_nao_pode_ser_usado_duas_vezes(): void
    {
        $codigo = $this->pedirCodigo();
        $dados = [
            'email_professor' => $this->professor->email_professor,
            'codigo' => $codigo,
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ];

        $this->post('/admin/senha/redefinir', $dados)->assertRedirect(route('admin.login'));
        $this->post('/admin/senha/redefinir', ['nova_senha' => 'outra789', 'nova_senha_confirmation' => 'outra789'] + $dados)
            ->assertSessionHasErrors('codigo');

        $this->assertTrue(Hash::check('senhaNova456', $this->professor->fresh()->senha_professor));
    }

    public function test_codigo_expirado_nao_vale(): void
    {
        $codigo = $this->pedirCodigo();
        $this->travel(31)->minutes();

        $this->post('/admin/senha/redefinir', [
            'email_professor' => $this->professor->email_professor,
            'codigo' => $codigo,
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])->assertSessionHasErrors('codigo');

        $this->assertTrue(Hash::check('senhaAntiga123', $this->professor->fresh()->senha_professor));
    }

    public function test_cinco_erros_invalidam_o_codigo(): void
    {
        // O throttle:login (5/min) já barraria antes; aqui o foco é o contador do próprio código.
        $this->withoutMiddleware(ThrottleRequests::class);

        $codigo = $this->pedirCodigo();
        $errado = $codigo === '000000' ? '111111' : '000000';
        $dados = [
            'email_professor' => $this->professor->email_professor,
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ];

        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/senha/redefinir', ['codigo' => $errado] + $dados)->assertSessionHasErrors('codigo');
        }

        // Depois de 5 erros, nem o código certo funciona mais (precisa pedir outro).
        $this->post('/admin/senha/redefinir', ['codigo' => $codigo] + $dados)->assertSessionHasErrors('codigo');

        $this->assertTrue(Hash::check('senhaAntiga123', $this->professor->fresh()->senha_professor));
    }

    public function test_pedir_novo_codigo_invalida_o_anterior(): void
    {
        $primeiro = $this->pedirCodigo();
        $segundo = $this->pedirCodigo();

        if ($primeiro === $segundo) {
            $this->markTestSkipped('Os dois códigos sorteados saíram iguais.');
        }

        $this->post('/admin/senha/redefinir', [
            'email_professor' => $this->professor->email_professor,
            'codigo' => $primeiro,
            'nova_senha' => 'senhaNova456',
            'nova_senha_confirmation' => 'senhaNova456',
        ])->assertSessionHasErrors('codigo');
    }
}
