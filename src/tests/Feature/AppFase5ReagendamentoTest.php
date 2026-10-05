<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Professor;
use App\Models\Reagendamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: modal "Solicitar reagendamento" (POST /aluno/reagendamento/solicitar).
 */
class AppFase5ReagendamentoTest extends TestCase
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

        $this->professor = Professor::create([
            'nome_professor' => 'Renato', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '10',
            'bio_professor' => 'Bio', 'foto_professor' => '', 'email_professor' => 'renato@prof.test',
            'curso_professor' => 'Inglês', 'nivel_professor' => 'Todos', 'telefone_professor' => '1',
            'senha_professor' => Hash::make('segredo123'),
        ]);

        $this->nivel = Nivel::create(['nome_nivel' => 'Intermediário']);
        $this->curso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
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

    private function aula(array $extra = []): Aula
    {
        return Aula::create($extra + [
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->curso->id_curso,
            'titulo_aulas' => 'Aula', 'descricao_aulas' => 'Desc',
            'data_aulas' => '2026-09-01', 'hora_aulas' => '19:00:00', 'cursos_aulas' => 'Inglês',
            'status_aulas' => 'ATIVO',
        ]);
    }

    public function test_solicita_reagendamento_com_sucesso(): void
    {
        $aula = $this->aula();

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/reagendamento/solicitar', [
                'aula_id' => $aula->id_aulas,
                'motivo'  => 'Preciso remarcar por causa do trabalho.',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('reagendamentos', [
            'aluno_id' => $this->aluno->id_aluno,
            'aula_id'  => $aula->id_aulas,
            'professor_id' => $this->professor->id_professor,
            'motivo'   => 'Preciso remarcar por causa do trabalho.',
            'status'   => 'pendente',
        ]);
    }

    public function test_motivo_curto_e_rejeitado(): void
    {
        $aula = $this->aula();

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/reagendamento/solicitar', [
                'aula_id' => $aula->id_aulas,
                'motivo'  => 'curto',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('motivo');
    }

    public function test_nao_deixa_duplicar_solicitacao_pendente_para_a_mesma_aula(): void
    {
        $aula = $this->aula();

        $this->withToken($this->token)->postJson('/api/v1/aluno/reagendamento/solicitar', [
            'aula_id' => $aula->id_aulas, 'motivo' => 'Primeiro motivo válido aqui.',
        ])->assertOk();

        $this->withToken($this->token)->postJson('/api/v1/aluno/reagendamento/solicitar', [
            'aula_id' => $aula->id_aulas, 'motivo' => 'Segundo motivo válido aqui.',
        ])->assertStatus(409)->assertJsonPath('success', false);

        $this->assertSame(1, Reagendamento::where('aula_id', $aula->id_aulas)->count());
    }

    public function test_nao_deixa_reagendar_aula_de_outro_aluno(): void
    {
        $aula = $this->aula();

        $outroAluno = Aluno::create([
            'nome_aluno' => 'Outro', 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);
        $outroToken = $outroAluno->createToken('app')->plainTextToken;

        $this->withToken($outroToken)
            ->postJson('/api/v1/aluno/reagendamento/solicitar', [
                'aula_id' => $aula->id_aulas, 'motivo' => 'Não é minha aula, não devia funcionar.',
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('reagendamentos', ['aula_id' => $aula->id_aulas]);
    }
}
