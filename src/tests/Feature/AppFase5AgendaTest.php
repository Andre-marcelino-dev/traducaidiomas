<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Nivel;
use App\Models\Presenca;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: tela Agenda (aulas de todos os cursos matriculados, em ordem).
 */
class AppFase5AgendaTest extends TestCase
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
        $this->curso = $this->curso('Inglês');

        $this->aluno = $this->novoAluno();
        $this->matricular($this->aluno, $this->curso);
        $this->token = $this->aluno->createToken('app')->plainTextToken;
    }

    private function curso(string $nome): Curso
    {
        return Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => $nome, 'descricao_curso' => $nome]));
    }

    private function novoAluno(): Aluno
    {
        return Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);
    }

    private function matricular(Aluno $aluno, Curso $curso, ?Nivel $nivel = null): Matricula
    {
        return Matricula::create([
            'id_aluno' => $aluno->id_aluno, 'id_curso' => $curso->id_curso,
            'id_nivel' => ($nivel ?? $this->nivel)->id_nivel,
            'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);
    }

    private function modulo(Curso $curso, int $ordem, ?Nivel $nivel = null): Modulo
    {
        return Modulo::create([
            'id_curso' => $curso->id_curso, 'id_nivel' => ($nivel ?? $this->nivel)->id_nivel,
            'ordem_modulo' => $ordem, 'nome_modulo' => 'Módulo ' . $ordem, 'descricao_modulo' => 'Desc ' . $ordem,
            'carga_horaria_minutos' => 150, 'status_modulo' => 'ATIVO',
        ]);
    }

    private function aula(Curso $curso, string $titulo, array $extra = []): Aula
    {
        return Aula::create($extra + [
            'id_professor' => $this->professor->id_professor, 'id_curso' => $curso->id_curso,
            'titulo_aulas' => $titulo, 'descricao_aulas' => 'Desc',
            'data_aulas' => '2026-09-01', 'hora_aulas' => '19:00:00', 'cursos_aulas' => $curso->nome_curso,
            'status_aulas' => 'ATIVO',
        ]);
    }

    private function presenca(Aula $aula, string $status): void
    {
        Presenca::create([
            'id_aulas' => $aula->id_aulas, 'id_aluno' => $this->aluno->id_aluno,
            'status_presenca' => $status, 'data_registro_presenca' => '2026-09-01',
        ]);
    }

    public function test_lista_aulas_de_todos_os_cursos_matriculados_em_ordem_cronologica(): void
    {
        $espanhol = $this->curso('Espanhol');
        $this->matricular($this->aluno, $espanhol);

        $this->aula($this->curso, 'Aula de inglês', ['data_aulas' => '2026-09-10', 'hora_aulas' => '10:00:00']);
        $this->aula($espanhol, 'Aula de espanhol', ['data_aulas' => '2026-09-05', 'hora_aulas' => '14:00:00']);

        $resposta = $this->withToken($this->token)
            ->getJson('/api/v1/aluno/agenda')
            ->assertOk()
            ->assertJsonCount(2, 'data.aulas');

        $aulas = $resposta->json('data.aulas');
        $this->assertSame(['Aula de espanhol', 'Aula de inglês'], array_column($aulas, 'titulo'));
        $this->assertSame(['Espanhol', 'Inglês'], array_column($aulas, 'curso'));
    }

    public function test_proxima_aula_ignora_aulas_passadas(): void
    {
        $passada = $this->aula($this->curso, 'Passada', ['data_aulas' => '2020-01-01', 'hora_aulas' => '08:00:00']);
        $futura = $this->aula($this->curso, 'Futura', ['data_aulas' => '2099-01-01', 'hora_aulas' => '08:00:00']);

        $resposta = $this->withToken($this->token)
            ->getJson('/api/v1/aluno/agenda')
            ->assertOk();

        $this->assertSame('Futura', $resposta->json('data.proxima_aula.titulo'));
        $this->assertSame((int) $futura->id_aulas, $resposta->json('data.proxima_aula.id_aula'));
    }

    public function test_concluida_reflete_presenca_e_link_vira_ao_vivo(): void
    {
        $comPresenca = $this->aula($this->curso, 'Com presença', ['link_teams' => 'https://teams.test/x']);
        $semPresenca = $this->aula($this->curso, 'Sem presença');
        $this->presenca($comPresenca, 'presente');

        $aulas = collect(
            $this->withToken($this->token)->getJson('/api/v1/aluno/agenda')->json('data.aulas')
        )->keyBy('titulo');

        $this->assertTrue($aulas['Com presença']['concluida']);
        $this->assertTrue($aulas['Com presença']['ao_vivo']);
        $this->assertFalse($aulas['Sem presença']['concluida']);
        $this->assertFalse($aulas['Sem presença']['ao_vivo']);
    }

    public function test_nao_mostra_aula_de_outro_aluno_nem_de_curso_sem_matricula(): void
    {
        $outroAluno = $this->novoAluno();
        $this->matricular($outroAluno, $this->curso);

        $semMatricula = $this->curso('Italiano');
        $this->aula($semMatricula, 'Italiano sem matrícula');
        $this->aula($this->curso, 'Minha aula');

        $aulas = $this->withToken($this->token)->getJson('/api/v1/aluno/agenda')->json('data.aulas');

        $this->assertSame(['Minha aula'], array_column($aulas, 'titulo'));
    }

    public function test_aula_de_modulo_de_outro_nivel_nao_aparece(): void
    {
        $outroNivel = Nivel::create(['nome_nivel' => 'Avançado']);
        $moduloOutroNivel = $this->modulo($this->curso, 1, $outroNivel);
        $this->aula($this->curso, 'Módulo de outro nível', ['id_modulo' => $moduloOutroNivel->id_modulo]);
        $this->aula($this->curso, 'Sem módulo (todos os níveis)');

        $aulas = $this->withToken($this->token)->getJson('/api/v1/aluno/agenda')->json('data.aulas');

        $this->assertSame(['Sem módulo (todos os níveis)'], array_column($aulas, 'titulo'));
    }

    public function test_sem_matricula_ativa_devolve_lista_vazia(): void
    {
        $semCurso = $this->novoAluno();
        $token = $semCurso->createToken('app')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/v1/aluno/agenda')
            ->assertOk()
            ->assertJsonPath('data.aulas', [])
            ->assertJsonPath('data.proxima_aula', null);
    }
}
