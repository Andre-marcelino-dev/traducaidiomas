<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Nivel;
use App\Models\Presenca;
use App\Models\Professor;
use App\Support\CursoAtual;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: telas Curso, Módulo e Materiais, e a regra de aula concluída por presença.
 */
class AppFase3Test extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Nivel $nivel;
    private Curso $curso;
    private Aluno $aluno;
    private Matricula $matricula;
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
        $this->professor->forceFill(['is_admin' => true])->save();

        $this->nivel = Nivel::create(['nome_nivel' => 'Intermediário']);
        $this->curso = $this->curso('Inglês');

        $this->aluno = $this->novoAluno();
        $this->matricula = $this->matricular($this->aluno, $this->curso);
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

    private function matricular(Aluno $aluno, Curso $curso): Matricula
    {
        return Matricula::create([
            'id_aluno' => $aluno->id_aluno, 'id_curso' => $curso->id_curso, 'id_nivel' => $this->nivel->id_nivel,
            'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);
    }

    private function modulo(int $ordem, ?Curso $curso = null): Modulo
    {
        return Modulo::create([
            'id_curso' => ($curso ?? $this->curso)->id_curso, 'id_nivel' => $this->nivel->id_nivel,
            'ordem_modulo' => $ordem, 'nome_modulo' => 'Módulo ' . $ordem, 'descricao_modulo' => 'Desc ' . $ordem,
            'carga_horaria_minutos' => 150, 'status_modulo' => 'ATIVO',
        ]);
    }

    private function aula(Modulo $modulo, string $titulo, array $extra = []): Aula
    {
        return Aula::create($extra + [
            'id_professor' => $this->professor->id_professor, 'id_curso' => $modulo->id_curso,
            'id_modulo' => $modulo->id_modulo, 'titulo_aulas' => $titulo, 'descricao_aulas' => 'Desc',
            'data_aulas' => '2026-09-01', 'hora_aulas' => '19:00:00', 'cursos_aulas' => 'Inglês',
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

    // ── Tela Módulo ──

    public function test_tela_do_modulo_lista_aulas_em_ordem_com_status(): void
    {
        $modulo = $this->modulo(1);
        $segunda = $this->aula($modulo, 'Pronomes', ['ordem_aula' => 2, 'duracao_minutos' => 22]);
        $primeira = $this->aula($modulo, 'Alfabeto', ['ordem_aula' => 1, 'duracao_minutos' => 30, 'link_teams' => 'https://teams.test/x']);
        $this->aula($modulo, 'Cancelada', ['status_aulas' => 'CANCELADO']);
        $this->presenca($primeira, 'presente');

        $resposta = $this->withToken($this->token)
            ->getJson('/api/v1/aluno/modulos/' . $modulo->id_modulo)
            ->assertOk()
            ->assertJsonPath('data.curso', 'Inglês')
            ->assertJsonPath('data.nivel', 'Intermediário')
            ->assertJsonPath('data.modulo.nome_modulo', 'Módulo 1')
            ->assertJsonPath('data.progresso.total_aulas', 2)
            ->assertJsonPath('data.progresso.aulas_concluidas', 1)
            ->assertJsonPath('data.progresso.percentual', 50)
            ->assertJsonCount(2, 'data.aulas');

        $aulas = $resposta->json('data.aulas');
        $this->assertSame(['Alfabeto', 'Pronomes'], array_column($aulas, 'titulo'));
        $this->assertSame([1, 2], array_column($aulas, 'numero'));
        $this->assertSame([30, 22], array_column($aulas, 'duracao_minutos'));
        $this->assertSame([true, false], array_column($aulas, 'ao_vivo'));
        $this->assertSame([true, false], array_column($aulas, 'concluida'));
        $this->assertSame('19:00', $aulas[0]['hora']);
        $this->assertSame('2026-09-01', $aulas[0]['data']);
        $this->assertSame((int) $segunda->id_aulas, $aulas[1]['id_aula']);
    }

    public function test_aula_sem_numero_e_numerada_pela_posicao(): void
    {
        $modulo = $this->modulo(1);
        $this->aula($modulo, 'B', ['data_aulas' => '2026-09-10']);
        $this->aula($modulo, 'A', ['data_aulas' => '2026-09-05']);

        $aulas = $this->withToken($this->token)
            ->getJson('/api/v1/aluno/modulos/' . $modulo->id_modulo)
            ->assertOk()
            ->json('data.aulas');

        $this->assertSame(['A', 'B'], array_column($aulas, 'titulo'));
        $this->assertSame([1, 2], array_column($aulas, 'numero'));
    }

    public function test_justificado_conclui_e_falta_nao(): void
    {
        $modulo = $this->modulo(1);
        $justificada = $this->aula($modulo, 'Justificada');
        $comFalta = $this->aula($modulo, 'Com falta');
        $this->presenca($justificada, 'JUSTIFICADO');
        $this->presenca($comFalta, 'falta');

        $aulas = collect($this->withToken($this->token)
            ->getJson('/api/v1/aluno/modulos/' . $modulo->id_modulo)
            ->json('data.aulas'))->keyBy('titulo');

        $this->assertTrue($aulas['Justificada']['concluida']);
        $this->assertFalse($aulas['Com falta']['concluida']);
        $this->assertSame('falta', $aulas['Com falta']['presenca']);
    }

    // ── Liberação sequencial ──

    public function test_modulo_seguinte_so_libera_quando_todas_as_aulas_tem_presenca(): void
    {
        $m1 = $this->modulo(1);
        $m2 = $this->modulo(2);
        $a1 = $this->aula($m1, 'Aula 1');
        $a2 = $this->aula($m1, 'Aula 2');
        $this->aula($m2, 'Aula 3');

        $this->withToken($this->token)->getJson('/api/v1/aluno/modulos/' . $m2->id_modulo)
            ->assertForbidden()
            ->assertJsonPath('message', 'Conclua o módulo anterior para liberar este.');

        $this->presenca($a1, 'presente');
        $this->withToken($this->token)->getJson('/api/v1/aluno/modulos/' . $m2->id_modulo)->assertForbidden();

        $this->presenca($a2, 'presente');
        $this->withToken($this->token)->getJson('/api/v1/aluno/modulos/' . $m2->id_modulo)->assertOk();

        $this->withToken($this->token)->getJson('/api/v1/aluno/modulos/' . $m1->id_modulo)
            ->assertJsonPath('data.progresso.concluido', true)
            ->assertJsonPath('data.proximo_modulo.id_modulo', (int) $m2->id_modulo)
            ->assertJsonPath('data.proximo_modulo.liberado', true);
    }

    public function test_tela_curso_conta_aulas_e_materiais(): void
    {
        $m1 = $this->modulo(1);
        $this->modulo(2);
        $aula = $this->aula($m1, 'Aula 1');
        $this->aula($m1, 'Aula 2');
        $this->presenca($aula, 'presente');
        Materiais::create([
            'id_professor' => $this->professor->id_professor, 'titulo_materiais' => 'Apostila',
            'descricao_materiais' => 'D', 'arquivo_materiais' => '', 'nivel_material' => 'x',
            'id_curso' => $this->curso->id_curso, 'id_modulo' => $m1->id_modulo,
        ]);

        $this->withToken($this->token)
            ->getJson('/api/v1/aluno/cursos/' . $this->curso->id_curso . '/modulos')
            ->assertOk()
            ->assertJsonPath('data.total_modulos', 2)
            ->assertJsonPath('data.total_aulas', 2)
            ->assertJsonPath('data.aulas_concluidas', 1)
            ->assertJsonPath('data.modulos.0.aulas_concluidas', 1)
            ->assertJsonPath('data.modulos.0.total_materiais', 1)
            ->assertJsonPath('data.modulos.0.percentual', 33) // 1 de 3 itens
            ->assertJsonPath('data.modulos.0.em_andamento', true)
            ->assertJsonPath('data.modulos.1.liberado', false);
    }

    // ── Acesso ──

    public function test_nao_abre_modulo_de_curso_sem_matricula(): void
    {
        $outroCurso = $this->curso('Italiano');
        $modulo = $this->modulo(1, $outroCurso);

        $this->withToken($this->token)->getJson('/api/v1/aluno/modulos/' . $modulo->id_modulo)->assertNotFound();
        $this->withToken($this->token)->getJson('/api/v1/aluno/cursos/' . $outroCurso->id_curso . '/materiais')->assertNotFound();
        // Sem token (limpa o header e o usuário guardado pelas chamadas acima).
        $this->flushHeaders();
        $this->app['auth']->forgetGuards();
        $this->getJson('/api/v1/aluno/modulos/' . $modulo->id_modulo)->assertUnauthorized();
    }

    // ── Aba Materiais ──

    public function test_aba_materiais_e_download_marcam_concluido(): void
    {
        $publico = sys_get_temp_dir() . '/traduca-app-' . uniqid();
        mkdir($publico . '/traducaidiomas/materiais', 0777, true);
        file_put_contents($publico . '/traducaidiomas/materiais/apostila.pdf', '%PDF-1.4');
        $this->app->usePublicPath($publico);

        $m1 = $this->modulo(1);
        $material = Materiais::create([
            'id_professor' => $this->professor->id_professor, 'titulo_materiais' => 'Apostila',
            'descricao_materiais' => 'D', 'arquivo_materiais' => 'traducaidiomas/materiais/apostila.pdf',
            'nivel_material' => 'x', 'id_curso' => $this->curso->id_curso, 'id_modulo' => $m1->id_modulo,
        ]);
        $deOutroCurso = Materiais::create([
            'id_professor' => $this->professor->id_professor, 'titulo_materiais' => 'Outro',
            'descricao_materiais' => 'D', 'arquivo_materiais' => 'traducaidiomas/materiais/apostila.pdf',
            'nivel_material' => 'x', 'id_curso' => $this->curso('Italiano')->id_curso,
        ]);

        $url = '/api/v1/aluno/cursos/' . $this->curso->id_curso . '/materiais';

        $this->withToken($this->token)->getJson($url)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.titulo', 'Apostila')
            ->assertJsonPath('data.0.nome_modulo', 'Módulo 1')
            ->assertJsonPath('data.0.extensao', 'pdf')
            ->assertJsonPath('data.0.concluido', false)
            ->assertJsonPath('data.0.url_download', route('api.aluno.materiais.download', $material->id_materiais));

        $this->withToken($this->token)
            ->get('/api/v1/aluno/materiais/' . $material->id_materiais . '/download')
            ->assertOk()
            ->assertDownload('Apostila.pdf');

        $this->withToken($this->token)->getJson($url)->assertJsonPath('data.0.concluido', true);

        $this->withToken($this->token)
            ->getJson('/api/v1/aluno/materiais/' . $deOutroCurso->id_materiais . '/download')
            ->assertNotFound();
    }

    // ── Painel do professor ──

    public function test_painel_salva_numero_e_duracao_da_aula(): void
    {
        $modulo = $this->modulo(1);

        $this->actingAs($this->professor, 'admin')->post('/admin/aulas', [
            'titulo_aulas' => 'Verbo To Be', 'descricao_aulas' => 'D', 'data_aulas' => '2026-10-01',
            'hora_aulas' => '19:00', 'id_professor' => $this->professor->id_professor,
            'id_curso' => $this->curso->id_curso, 'id_modulo' => $modulo->id_modulo,
            'ordem_aula' => 4, 'duracao_minutos' => 22, 'cursos_aulas' => 'Inglês', 'status_aulas' => 'ATIVO',
        ])->assertRedirect(route('admin.aulas.index'));

        $this->assertDatabaseHas('tbl_aulas', ['titulo_aulas' => 'Verbo To Be', 'ordem_aula' => 4, 'duracao_minutos' => 22]);

        $this->actingAs($this->professor, 'admin')->get('/admin/aulas/create')
            ->assertOk()->assertSee('name="duracao_minutos"', false);
    }

    public function test_tela_de_curso_do_site_continua_abrindo(): void
    {
        $m1 = $this->modulo(1);
        $this->presenca($this->aula($m1, 'Aula 1'), 'presente');
        $this->aula($m1, 'Aula 2');

        $this->actingAs($this->aluno, 'aluno')
            ->withSession([CursoAtual::SESSAO => $this->matricula->id_matricula])
            ->get('/aluno/curso')
            ->assertOk()
            ->assertSee('1/2 aulas');
    }
}
