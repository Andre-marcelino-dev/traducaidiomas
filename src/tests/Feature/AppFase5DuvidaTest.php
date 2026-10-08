<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Duvida;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: tela Dúvida (aluno envia dúvida ao professor).
 */
class AppFase5DuvidaTest extends TestCase
{
    use RefreshDatabase;

    private Aluno $aluno;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);

        $this->token = $this->aluno->createToken('app')->plainTextToken;
    }

    public function test_envia_duvida_com_sucesso(): void
    {
        $resposta = $this->withToken($this->token)
            ->postJson('/api/v1/aluno/duvidas', [
                'assunto_duvida' => 'Dúvida sobre o verbo to be',
                'mensagem_duvida' => 'Professor, não entendi quando usar was e were.',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pendente')
            ->assertJsonPath('data.resposta_professor', null);

        $this->assertDatabaseHas('tbl_duvidas', [
            'id_aluno' => $this->aluno->id_aluno,
            'assunto_duvida' => 'Dúvida sobre o verbo to be',
            'status_duvida' => 'pendente',
        ]);
    }

    public function test_assunto_em_branco_e_rejeitado(): void
    {
        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/duvidas', [
                'assunto_duvida' => '',
                'mensagem_duvida' => 'Mensagem válida aqui.',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('assunto_duvida');
    }

    public function test_lista_so_as_proprias_duvidas_ativas_mais_recentes_primeiro(): void
    {
        $antiga = Duvida::create([
            'id_aluno' => $this->aluno->id_aluno, 'assunto_duvida' => 'Antiga',
            'mensagem_duvida' => 'Mensagem antiga.', 'status_duvida' => 'respondida',
            'resposta_professor' => 'Resposta do professor.', 'respondido_em' => now(),
        ]);
        $antiga->forceFill(['criado_em' => now()->subDays(2)])->save();

        $nova = Duvida::create([
            'id_aluno' => $this->aluno->id_aluno, 'assunto_duvida' => 'Nova',
            'mensagem_duvida' => 'Mensagem nova.', 'status_duvida' => 'pendente',
        ]);
        $nova->forceFill(['criado_em' => now()])->save();
        Duvida::create([
            'id_aluno' => $this->aluno->id_aluno, 'assunto_duvida' => 'Inativa',
            'mensagem_duvida' => 'Não deveria aparecer.', 'status_duvida' => 'pendente', 'ativo' => false,
        ]);

        $outroAluno = Aluno::create([
            'nome_aluno' => 'Outro', 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);
        Duvida::create([
            'id_aluno' => $outroAluno->id_aluno, 'assunto_duvida' => 'De outro aluno',
            'mensagem_duvida' => 'Não deveria aparecer.', 'status_duvida' => 'pendente',
        ]);

        $duvidas = $this->withToken($this->token)
            ->getJson('/api/v1/aluno/duvidas')
            ->assertOk()
            ->json('data');

        $this->assertSame(['Nova', 'Antiga'], array_column($duvidas, 'assunto'));

        $respondida = collect($duvidas)->firstWhere('assunto', 'Antiga');
        $this->assertSame('respondida', $respondida['status']);
        $this->assertSame('Resposta do professor.', $respondida['resposta_professor']);
    }
}
