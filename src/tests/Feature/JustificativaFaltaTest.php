<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\JustificativaFalta;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Presenca;
use App\Models\Professor;
use App\Support\CursoAtual;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Aluno justifica a falta em Meu Progresso; o professor da aula aceita ou recusa.
 */
class JustificativaFaltaTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Curso $curso;
    private Nivel $nivel;
    private Aluno $aluno;
    private Matricula $matricula;

    protected function setUp(): void
    {
        parent::setUp();

        $this->professor = $this->professor('renato@prof.test', admin: false);
        $this->nivel = Nivel::create(['nome_nivel' => 'Iniciante']);
        $this->curso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));

        $this->aluno = $this->novoAluno();
        $this->matricula = Matricula::create([
            'id_aluno'         => $this->aluno->id_aluno,
            'id_curso'         => $this->curso->id_curso,
            'id_nivel'         => $this->nivel->id_nivel,
            'data_matricula'   => now(),
            'status_matricula' => 'ATIVO',
        ]);
    }

    private function professor(string $email, bool $admin): Professor
    {
        $professor = Professor::create([
            'nome_professor'          => 'Prof ' . $email,
            'especialidade_professor' => 'Inglês',
            'experiencia_professor'   => '10 anos',
            'bio_professor'           => 'Bio',
            'foto_professor'          => '',
            'email_professor'         => $email,
            'curso_professor'         => 'Inglês',
            'nivel_professor'         => 'Todos',
            'telefone_professor'      => '11999999999',
            'senha_professor'         => Hash::make('segredo123'),
        ]);
        $professor->forceFill(['is_admin' => $admin])->save();

        return $professor;
    }

    private function novoAluno(): Aluno
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
            'status_aluno'    => 'EM CURSO',
        ]);
    }

    private function presenca(string $status, ?Professor $professor = null, ?Aluno $aluno = null): Presenca
    {
        $aula = Aula::create([
            'id_professor'    => ($professor ?? $this->professor)->id_professor,
            'id_curso'        => $this->curso->id_curso,
            'titulo_aulas'    => 'Aula de Inglês',
            'descricao_aulas' => 'Desc',
            'data_aulas'      => '2026-09-01',
            'hora_aulas'      => '19:00',
            'cursos_aulas'    => 'Inglês',
            'status_aulas'    => 'ATIVO',
        ]);

        return Presenca::create([
            'id_aulas'               => $aula->id_aulas,
            'id_aluno'               => ($aluno ?? $this->aluno)->id_aluno,
            'status_presenca'        => $status,
            'data_registro_presenca' => '2026-09-01',
        ]);
    }

    private function comoAluno()
    {
        return $this->actingAs($this->aluno, 'aluno')->withSession([CursoAtual::SESSAO => $this->matricula->id_matricula]);
    }

    // O botão Justificar leva a rota da presença no data-action.
    private function botao(Presenca $presenca): string
    {
        return 'data-action="' . route('aluno.justificativa.store', $presenca->id_presenca) . '"';
    }

    private function justificar(Presenca $presenca, string $motivo = 'Estava com febre e fui ao médico.')
    {
        return $this->comoAluno()->post(route('aluno.justificativa.store', $presenca->id_presenca), ['motivo_justificativa' => $motivo]);
    }

    // ── Aluno ──

    public function test_botao_justificar_so_aparece_na_falta(): void
    {
        $presente = $this->presenca('presente');
        $justificado = $this->presenca('justificado');
        $falta = $this->presenca('falta');

        $this->comoAluno()->get('/aluno/progresso')->assertOk()
            ->assertDontSee($this->botao($presente), false)
            ->assertDontSee($this->botao($justificado), false)
            ->assertSee($this->botao($falta), false);
    }

    public function test_aluno_justifica_falta_e_fica_em_analise(): void
    {
        $falta = $this->presenca('falta');

        $this->justificar($falta)->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_justificativa_falta', [
            'id_presenca' => $falta->id_presenca, 'status_justificativa' => 'pendente',
        ]);
        $this->comoAluno()->get('/aluno/progresso')
            ->assertSee('Em análise pelo professor')
            ->assertDontSee($this->botao($falta), false);

        // Não manda outra enquanto a primeira não for respondida.
        $this->justificar($falta)->assertSessionHas('error');
        $this->assertSame(1, JustificativaFalta::count());
    }

    public function test_aluno_nao_justifica_presenca_nem_falta_de_outro(): void
    {
        $this->justificar($this->presenca('presente'))->assertSessionHas('error');
        $this->justificar($this->presenca('falta'), 'curto')->assertSessionHasErrors('motivo_justificativa');

        $deOutro = $this->presenca('falta', aluno: $this->novoAluno());
        $this->justificar($deOutro)->assertNotFound();

        $this->assertSame(0, JustificativaFalta::count());
    }

    // ── Professor ──

    public function test_professor_ve_aviso_e_aceita_justificativa(): void
    {
        $falta = $this->presenca('falta');
        $this->justificar($falta);
        $justificativa = JustificativaFalta::first();

        // Aviso no menu lateral e na lista.
        $this->actingAs($this->professor, 'admin')->get(route('admin.justificativas.index'))
            ->assertOk()
            ->assertSee('Estava com febre e fui ao médico.')
            ->assertSee('title="Justificativas aguardando resposta"', false);

        $this->actingAs($this->professor, 'admin')
            ->put(route('admin.justificativas.aceitar', $justificativa->id_justificativa))
            ->assertSessionHas('success');

        $this->assertSame('justificado', $falta->fresh()->status_presenca);
        $this->assertSame('aceita', $justificativa->fresh()->status_justificativa);

        $this->comoAluno()->get('/aluno/progresso')
            ->assertSee('📝 Justificado')
            ->assertSee('Aceita pelo professor')
            ->assertDontSee($this->botao($falta), false);
    }

    public function test_professor_recusa_e_aluno_pode_justificar_de_novo(): void
    {
        $falta = $this->presenca('falta');
        $this->justificar($falta);

        $this->actingAs($this->professor, 'admin')
            ->put(route('admin.justificativas.recusar', JustificativaFalta::first()->id_justificativa), [
                'resposta_professor' => 'Precisa enviar o atestado.',
            ])
            ->assertSessionHas('success');

        $this->assertSame('falta', $falta->fresh()->status_presenca);
        $this->comoAluno()->get('/aluno/progresso')
            ->assertSee('Justificativa recusada: Precisa enviar o atestado.')
            ->assertSee('Justificar novamente');

        $this->justificar($falta, 'Segue o atestado médico do dia.')->assertSessionHas('success');
        $this->assertSame(2, JustificativaFalta::count());
    }

    public function test_professor_so_responde_justificativas_das_proprias_aulas(): void
    {
        $outroProfessor = $this->professor('outro@prof.test', admin: false);
        $admin = $this->professor('admin@prof.test', admin: true);

        $this->justificar($this->presenca('falta'));
        $id = JustificativaFalta::first()->id_justificativa;

        $this->actingAs($outroProfessor, 'admin')->get(route('admin.justificativas.index'))
            ->assertOk()->assertDontSee('Estava com febre');
        $this->actingAs($outroProfessor, 'admin')->put(route('admin.justificativas.aceitar', $id))->assertNotFound();

        $this->actingAs($admin, 'admin')->get(route('admin.justificativas.index'))->assertSee('Estava com febre');
    }

    public function test_nao_aceita_se_o_professor_ja_trocou_a_falta(): void
    {
        $falta = $this->presenca('falta');
        $this->justificar($falta);
        $falta->update(['status_presenca' => 'presente']); // professor refez a chamada

        $this->actingAs($this->professor, 'admin')
            ->put(route('admin.justificativas.aceitar', JustificativaFalta::first()->id_justificativa))
            ->assertSessionHas('error');

        $this->assertSame('presente', $falta->fresh()->status_presenca);
    }

    public function test_justificativa_some_junto_com_o_aluno(): void
    {
        $this->justificar($this->presenca('falta'));

        $this->aluno->excluirComDependencias();

        $this->assertSame(0, JustificativaFalta::count());
    }
}
