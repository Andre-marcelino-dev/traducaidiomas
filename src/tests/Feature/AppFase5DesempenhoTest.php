<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\JustificativaFalta;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Presenca;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: tela Desempenho (presença, materiais, minutos estudados)
 * e justificar falta.
 */
class AppFase5DesempenhoTest extends TestCase
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
            'status_aulas' => 'ATIVO', 'duracao_minutos' => 30,
        ]);
    }

    private function presenca(Aula $aula, string $status, array $extra = []): Presenca
    {
        return Presenca::create($extra + [
            'id_aulas' => $aula->id_aulas, 'id_aluno' => $this->aluno->id_aluno,
            'status_presenca' => $status, 'data_registro_presenca' => '2026-09-01',
        ]);
    }

    // ── Desempenho ──

    public function test_desempenho_calcula_presenca_materiais_e_minutos(): void
    {
        $a1 = $this->aula(['duracao_minutos' => 30]);
        $a2 = $this->aula(['duracao_minutos' => 20]);
        $a3 = $this->aula(['duracao_minutos' => 50]);
        $this->presenca($a1, 'presente');
        $this->presenca($a2, 'justificado');
        $this->presenca($a3, 'falta');

        Materiais::create([
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->curso->id_curso,
            'titulo_materiais' => 'Apostila', 'descricao_materiais' => 'Desc',
            'arquivo_materiais' => 'apostila.pdf', 'nivel_material' => 'Intermediário',
        ]);
        Materiais::create([
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->curso->id_curso,
            'titulo_materiais' => 'Apostila 2', 'descricao_materiais' => 'Desc',
            'arquivo_materiais' => 'apostila2.pdf', 'nivel_material' => 'Intermediário',
        ]);
        DB::table('tbl_progresso_materiais')->insert([
            'id_aluno' => $this->aluno->id_aluno, 'id_materiais' => 1,
            'status_progresso' => 'CONCLUIDO', 'progresso_materiais' => 100,
            'data_acesso_progresso_materiais' => now(),
        ]);

        $resposta = $this->withToken($this->token)
            ->getJson('/api/v1/aluno/cursos/' . $this->curso->id_curso . '/desempenho')
            ->assertOk()
            ->assertJsonPath('data.curso', 'Inglês')
            ->assertJsonPath('data.presenca.total_aulas', 3)
            ->assertJsonPath('data.presenca.presentes', 1)
            ->assertJsonPath('data.presenca.faltas', 1)
            ->assertJsonPath('data.presenca.justificadas', 1)
            ->assertJsonPath('data.presenca.percentual', 67)
            ->assertJsonPath('data.minutos_estudados', 50)
            ->assertJsonPath('data.materiais.total', 2)
            ->assertJsonPath('data.materiais.vistos', 1)
            ->assertJsonPath('data.materiais.percentual', 50)
            ->assertJsonCount(3, 'data.ultimas_presencas');
    }

    public function test_desempenho_404_sem_matricula_no_curso(): void
    {
        $outroCurso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Italiano', 'descricao_curso' => 'Italiano']));

        $this->withToken($this->token)
            ->getJson('/api/v1/aluno/cursos/' . $outroCurso->id_curso . '/desempenho')
            ->assertNotFound();
    }

    // ── Justificar falta ──

    public function test_justificar_falta_com_sucesso(): void
    {
        $falta = $this->presenca($this->aula(), 'falta');

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/presencas/' . $falta->id_presenca . '/justificar', [
                'motivo_justificativa' => 'Fiquei doente nesse dia e não pude ir.',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('tbl_justificativa_falta', [
            'id_presenca' => $falta->id_presenca,
            'status_justificativa' => 'pendente',
        ]);
    }

    public function test_motivo_curto_e_rejeitado(): void
    {
        $falta = $this->presenca($this->aula(), 'falta');

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/presencas/' . $falta->id_presenca . '/justificar', [
                'motivo_justificativa' => 'curto',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('motivo_justificativa');
    }

    public function test_nao_deixa_justificar_presenca_que_nao_e_falta(): void
    {
        $presente = $this->presenca($this->aula(), 'presente');

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/presencas/' . $presente->id_presenca . '/justificar', [
                'motivo_justificativa' => 'Não era pra eu poder justificar isso.',
            ])
            ->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_nao_deixa_duplicar_justificativa_pendente(): void
    {
        $falta = $this->presenca($this->aula(), 'falta');
        JustificativaFalta::create([
            'id_presenca' => $falta->id_presenca, 'motivo_justificativa' => 'Primeiro motivo válido aqui.',
            'status_justificativa' => 'pendente',
        ]);

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/presencas/' . $falta->id_presenca . '/justificar', [
                'motivo_justificativa' => 'Segundo motivo também válido aqui.',
            ])
            ->assertStatus(409)
            ->assertJsonPath('success', false);

        $this->assertSame(1, JustificativaFalta::where('id_presenca', $falta->id_presenca)->count());
    }

    public function test_nao_deixa_justificar_presenca_de_outro_aluno(): void
    {
        $outroAluno = Aluno::create([
            'nome_aluno' => 'Outro', 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);

        $falta = Presenca::create([
            'id_aulas' => $this->aula()->id_aulas, 'id_aluno' => $outroAluno->id_aluno,
            'status_presenca' => 'falta', 'data_registro_presenca' => '2026-09-01',
        ]);

        $this->withToken($this->token)
            ->postJson('/api/v1/aluno/presencas/' . $falta->id_presenca . '/justificar', [
                'motivo_justificativa' => 'Não é minha falta, não devia funcionar.',
            ])
            ->assertNotFound();
    }
}
