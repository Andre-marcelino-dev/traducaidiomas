<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Duvida;
use App\Models\ForumResposta;
use App\Models\ForumTopico;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Nivel;
use App\Models\Presenca;
use App\Models\Professor;
use App\Models\Reagendamento;
use App\Support\CursoAtual;
use App\Support\ModuloProgresso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Bugs da fase 2: progresso dos módulos, exclusões, status do aluno, presença e reagendamento.
 */
class CorrecoesFase2Test extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Nivel $nivel;
    private Curso $curso;

    protected function setUp(): void
    {
        parent::setUp();

        $this->professor = Professor::create([
            'nome_professor'          => 'Renato',
            'especialidade_professor' => 'Inglês',
            'experiencia_professor'   => '10 anos',
            'bio_professor'           => 'Bio',
            'foto_professor'          => '',
            'email_professor'         => 'renato@prof.test',
            'curso_professor'         => 'Inglês',
            'nivel_professor'         => 'Todos',
            'telefone_professor'      => '11999999999',
            'senha_professor'         => Hash::make('segredo123'),
        ]);
        $this->professor->forceFill(['is_admin' => true])->save();

        $this->nivel = Nivel::create(['nome_nivel' => 'Iniciante']);
        $this->curso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));
    }

    private function aluno(string $status = 'EM CURSO'): Aluno
    {
        return Aluno::create([
            'nome_aluno'      => 'Aluno ' . uniqid(),
            'email_aluno'     => uniqid() . '@aluno.test',
            'senha_aluno'     => Hash::make('segredo123'),
            'telefone_aluno'  => '11999999999',
            'curso_aluno'     => 'Inglês',
            'data_nasc_aluno' => '2000-01-01',
            'nivel_aluno'     => 'Iniciante',
            'foto_aluno'      => '',
            'status_aluno'    => $status,
        ]);
    }

    private function matricular(Aluno $aluno, string $status = 'ATIVO'): Matricula
    {
        return Matricula::create([
            'id_aluno'         => $aluno->id_aluno,
            'id_curso'         => $this->curso->id_curso,
            'id_nivel'         => $this->nivel->id_nivel,
            'data_matricula'   => now(),
            'status_matricula' => $status,
        ]);
    }

    private function modulo(int $ordem): Modulo
    {
        return Modulo::create([
            'id_curso'              => $this->curso->id_curso,
            'id_nivel'              => $this->nivel->id_nivel,
            'ordem_modulo'          => $ordem,
            'nome_modulo'           => 'Módulo ' . $ordem,
            'carga_horaria_minutos' => 60,
            'status_modulo'         => 'ATIVO',
        ]);
    }

    private function material(?Modulo $modulo, string $arquivo = ''): Materiais
    {
        return Materiais::create([
            'id_professor'        => $this->professor->id_professor,
            'titulo_materiais'    => 'Material ' . uniqid(),
            'descricao_materiais' => 'Desc',
            'arquivo_materiais'   => $arquivo,
            'nivel_material'      => 'Iniciante',
            'id_curso'            => $this->curso->id_curso,
            'id_modulo'           => $modulo?->id_modulo,
        ]);
    }

    private function aula(string $data = '2026-09-01'): Aula
    {
        return Aula::create([
            'id_professor'    => $this->professor->id_professor,
            'id_curso'        => $this->curso->id_curso,
            'titulo_aulas'    => 'Aula',
            'descricao_aulas' => 'Desc',
            'data_aulas'      => $data,
            'hora_aulas'      => '19:00',
            'cursos_aulas'    => 'Inglês',
            'status_aulas'    => 'ATIVO',
        ]);
    }

    // ── Progresso dos materiais / liberação dos módulos ──

    public function test_baixar_material_conclui_e_libera_o_proximo_modulo(): void
    {
        // Arquivo de verdade dentro de uma pasta pública temporária.
        $publico = sys_get_temp_dir() . '/traduca-publico-' . uniqid();
        mkdir($publico . '/traducaidiomas/materiais', 0777, true);
        file_put_contents($publico . '/traducaidiomas/materiais/apostila.pdf', '%PDF-1.4 teste');
        $this->app->usePublicPath($publico);

        $aluno = $this->aluno();
        $matricula = $this->matricular($aluno);
        $modulo1 = $this->modulo(1);
        $this->modulo(2);
        $material = $this->material($modulo1, 'traducaidiomas/materiais/apostila.pdf');

        // Antes: módulo 2 bloqueado.
        $antes = ModuloProgresso::paraMatricula($matricula);
        $this->assertFalse($antes[1]->liberado);

        $sessao = [CursoAtual::SESSAO => $matricula->id_matricula];

        // Abrir só os detalhes = em andamento.
        $this->actingAs($aluno, 'aluno')->withSession($sessao)
            ->get('/aluno/materiais/' . $material->id_materiais);
        $this->assertDatabaseHas('tbl_progresso_materiais', [
            'id_aluno' => $aluno->id_aluno, 'id_materiais' => $material->id_materiais, 'status_progresso' => 'EM ANDAMENTO',
        ]);

        // Baixar = concluído.
        $this->actingAs($aluno, 'aluno')->withSession($sessao)
            ->get('/aluno/materiais/' . $material->id_materiais . '/download')
            ->assertOk();

        $this->assertDatabaseHas('tbl_progresso_materiais', [
            'id_aluno' => $aluno->id_aluno, 'id_materiais' => $material->id_materiais, 'status_progresso' => 'CONCLUIDO',
        ]);
        $this->assertSame(1, DB::table('tbl_progresso_materiais')->count(), 'não pode duplicar a linha');

        $depois = ModuloProgresso::paraMatricula($matricula);
        $this->assertTrue($depois[0]->concluido);
        $this->assertTrue($depois[1]->liberado);

        // Abrir de novo os detalhes não volta para "em andamento".
        $this->actingAs($aluno, 'aluno')->withSession($sessao)
            ->get('/aluno/materiais/' . $material->id_materiais);
        $this->assertDatabaseHas('tbl_progresso_materiais', ['status_progresso' => 'CONCLUIDO']);
    }

    public function test_material_sem_arquivo_conclui_ao_abrir(): void
    {
        $aluno = $this->aluno();
        $matricula = $this->matricular($aluno);
        $material = $this->material($this->modulo(1));

        $this->actingAs($aluno, 'aluno')
            ->withSession([CursoAtual::SESSAO => $matricula->id_matricula])
            ->get('/aluno/materiais/' . $material->id_materiais)
            ->assertOk();

        $this->assertDatabaseHas('tbl_progresso_materiais', [
            'id_materiais' => $material->id_materiais, 'status_progresso' => 'CONCLUIDO',
        ]);
    }

    // ── Exclusões que davam erro 500 ──

    public function test_excluir_aluno_com_forum_e_duvida(): void
    {
        $aluno = $this->aluno();
        $outro = $this->aluno();
        $this->matricular($aluno);

        $topico = ForumTopico::create([
            'id_curso' => $this->curso->id_curso, 'id_aluno' => $aluno->id_aluno,
            'titulo_topico' => 'Dúvida', 'descricao_topico' => 'Texto',
        ]);
        ForumResposta::create(['id_topico' => $topico->id_topico, 'id_aluno' => $outro->id_aluno, 'conteudo_resposta' => 'Oi']);
        Duvida::create(['id_aluno' => $aluno->id_aluno, 'assunto_duvida' => 'A', 'mensagem_duvida' => 'B', 'status_duvida' => 'pendente']);
        Presenca::create(['id_aulas' => $this->aula()->id_aulas, 'id_aluno' => $aluno->id_aluno, 'status_presenca' => 'presente', 'data_registro_presenca' => '2026-09-01']);

        $this->actingAs($this->professor, 'admin')
            ->delete('/admin/alunos/' . $aluno->id_aluno)
            ->assertRedirect(route('admin.alunos.index'));

        $this->assertDatabaseMissing('tbl_alunos', ['id_aluno' => $aluno->id_aluno]);
        $this->assertDatabaseMissing('tbl_forum_topicos', ['id_topico' => $topico->id_topico]);
        $this->assertDatabaseCount('tbl_duvidas', 0);
        $this->assertDatabaseHas('tbl_alunos', ['id_aluno' => $outro->id_aluno]);
    }

    public function test_excluir_atividade_com_respostas(): void
    {
        $aluno = $this->aluno();
        $atividade = Atividade::create([
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->curso->id_curso,
            'titulo_atividade' => 'Quiz', 'status_atividade' => 'ATIVA',
        ]);
        AtividadeResposta::create(['id_atividade' => $atividade->id_atividade, 'id_aluno' => $aluno->id_aluno, 'status_resposta' => 'ENVIADA']);

        $this->actingAs($this->professor, 'admin')
            ->delete('/admin/atividades/' . $atividade->id_atividade)
            ->assertRedirect();

        $this->assertDatabaseMissing('tbl_atividades', ['id_atividade' => $atividade->id_atividade]);
        $this->assertDatabaseCount('tbl_atividade_respostas', 0);
    }

    public function test_excluir_professor_com_aulas_mostra_aviso_em_vez_de_erro(): void
    {
        $outro = Professor::create([
            'nome_professor' => 'Outro', 'especialidade_professor' => 'x', 'experiencia_professor' => 'x',
            'bio_professor' => 'x', 'foto_professor' => '', 'email_professor' => 'outro@prof.test',
            'curso_professor' => 'x', 'nivel_professor' => 'x', 'telefone_professor' => '1', 'senha_professor' => Hash::make('x'),
        ]);
        Aula::create([
            'id_professor' => $outro->id_professor, 'id_curso' => $this->curso->id_curso, 'titulo_aulas' => 'A',
            'descricao_aulas' => 'D', 'data_aulas' => '2026-09-01', 'hora_aulas' => '19:00',
            'cursos_aulas' => 'Inglês', 'status_aulas' => 'ATIVO',
        ]);

        $this->actingAs($this->professor, 'admin')
            ->delete('/admin/professores/' . $outro->id_professor)
            ->assertRedirect()
            ->assertSessionHas('error', fn ($msg) => str_contains($msg, '1 aulas'));

        $this->assertDatabaseHas('tbl_professor', ['id_professor' => $outro->id_professor]);
    }

    // ── Status do aluno ──

    public function test_status_do_aluno_so_aceita_valores_validos(): void
    {
        $aluno = $this->aluno();

        $this->actingAs($this->professor, 'admin')
            ->put('/admin/alunos/' . $aluno->id_aluno . '/status', ['status_aluno' => 'CONCLUÍDO'])
            ->assertSessionHasErrors('status_aluno');

        $this->actingAs($this->professor, 'admin')
            ->put('/admin/alunos/' . $aluno->id_aluno . '/status', ['status_aluno' => 'CONCLUIDO'])
            ->assertSessionHasNoErrors();

        $this->assertSame('CONCLUIDO', $aluno->fresh()->status_aluno);
    }

    public function test_aluno_novo_no_banco_nasce_em_curso(): void
    {
        $id = DB::table('tbl_alunos')->insertGetId([
            'nome_aluno' => 'X', 'email_aluno' => 'x@x.test', 'senha_aluno' => 'x', 'telefone_aluno' => '1',
            'curso_aluno' => 'x', 'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'x', 'foto_aluno' => '',
        ]);

        $this->assertSame('EM CURSO', DB::table('tbl_alunos')->where('id_aluno', $id)->value('status_aluno'));
    }

    // ── Presença ──

    public function test_presenca_usa_a_data_da_aula_e_nao_duplica(): void
    {
        $aluno = $this->aluno();
        $this->matricular($aluno);
        $aula = $this->aula('2026-09-01');

        foreach (['falta', 'presente'] as $status) {
            $this->actingAs($this->professor, 'admin')
                ->post('/admin/presenca/salvar', ['id_aulas' => $aula->id_aulas, 'presencas' => [$aluno->id_aluno => $status]])
                ->assertRedirect(route('admin.presenca.index'));
        }

        $this->assertSame(1, Presenca::count());
        $registro = Presenca::first();
        $this->assertSame('presente', $registro->status_presenca);
        $this->assertStringStartsWith('2026-09-01', (string) $registro->data_registro_presenca);
    }

    public function test_meu_progresso_mostra_presenca_lancada_pelo_professor(): void
    {
        $aluno = $this->aluno();
        $matricula = $this->matricular($aluno);
        $presente = $this->aula('2026-09-01');
        $justificado = $this->aula('2026-09-02');

        // A tela do professor grava em minúsculas.
        $this->actingAs($this->professor, 'admin')
            ->post('/admin/presenca/salvar', ['id_aulas' => $presente->id_aulas, 'presencas' => [$aluno->id_aluno => 'presente']]);
        $this->actingAs($this->professor, 'admin')
            ->post('/admin/presenca/salvar', ['id_aulas' => $justificado->id_aulas, 'presencas' => [$aluno->id_aluno => 'justificado']]);

        $this->actingAs($aluno, 'aluno')
            ->withSession([CursoAtual::SESSAO => $matricula->id_matricula])
            ->get('/aluno/progresso')
            ->assertOk()
            ->assertSee('✅ Presente')
            ->assertSee('📝 Justificado')
            ->assertDontSee('❌ Falta')
            ->assertViewHas('totalPresente', 1)
            ->assertViewHas('totalFalta', 0);
    }

    public function test_presenca_lista_so_matricula_ativa_e_recusa_status_invalido(): void
    {
        $ativo = $this->aluno();
        $this->matricular($ativo);
        $cancelado = $this->aluno();
        $this->matricular($cancelado, 'CANCELADO');
        $aula = $this->aula();

        $this->actingAs($this->professor, 'admin')->get('/admin/presenca/' . $aula->id_aulas . '/alunos')
            ->assertOk()
            ->assertSee($ativo->nome_aluno)
            ->assertDontSee($cancelado->nome_aluno);

        $this->actingAs($this->professor, 'admin')
            ->post('/admin/presenca/salvar', ['id_aulas' => $aula->id_aulas, 'presencas' => [$ativo->id_aluno => 'hackeado']])
            ->assertSessionHasErrors('presencas.' . $ativo->id_aluno);

        // Aluno com matrícula cancelada não recebe presença.
        $this->actingAs($this->professor, 'admin')
            ->post('/admin/presenca/salvar', ['id_aulas' => $aula->id_aulas, 'presencas' => [$cancelado->id_aluno => 'presente']]);
        $this->assertSame(0, Presenca::where('id_aluno', $cancelado->id_aluno)->count());
    }

    public function test_tela_de_presenca_abre(): void
    {
        $this->aula(date('Y-m-d'));

        $this->actingAs($this->professor, 'admin')->get('/admin/presenca')->assertOk();
    }

    // ── Reagendamento ──

    public function test_pedido_de_reagendamento_aparece_no_contador_do_professor(): void
    {
        $aluno = $this->aluno();
        $matricula = $this->matricular($aluno);
        $aula = $this->aula();

        $this->actingAs($aluno, 'aluno')
            ->withSession([CursoAtual::SESSAO => $matricula->id_matricula])
            ->post('/aluno/reagendamento/solicitar', ['aula_id' => $aula->id_aulas, 'motivo' => 'Viagem a trabalho'])
            ->assertRedirect();

        $this->assertSame(1, Reagendamento::count());

        $this->actingAs($this->professor, 'admin')
            ->getJson('/admin/reagendamento/notificacoes')
            ->assertOk()
            ->assertJsonPath('count', 1);
    }

    // ── Rotas que apontavam para telas inexistentes ──

    public function test_rotas_quebradas_foram_removidas(): void
    {
        $this->actingAs($this->professor, 'admin');

        $this->get('/admin/professores/' . $this->professor->id_professor)->assertStatus(405); // só PUT/DELETE
        $this->get('/admin/agendas/aluno/1')->assertNotFound();
        $this->get('/admin/agendas/professor/1')->assertNotFound();
    }
}
