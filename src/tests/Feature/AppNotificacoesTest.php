<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Duvida;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Nivel;
use App\Models\Notificacao;
use App\Models\Professor;
use App\Models\Reagendamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Sino do app: o painel do professor cria os avisos e a API do aluno lista
 * e marca como lidos.
 */
class AppNotificacoesTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Curso $curso;
    private Nivel $basico;
    private Nivel $avancado;
    private Aluno $alunoBasico;   // matrícula ativa no nível básico
    private Aluno $alunoAvancado; // matrícula ativa no nível avançado
    private Aluno $alunoInativo;  // situação INATIVO (não recebe)
    private Aluno $exAluno;       // matrícula cancelada (não recebe)

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

        $this->curso = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));
        $this->basico = Nivel::create(['nome_nivel' => 'básico']);
        $this->avancado = Nivel::create(['nome_nivel' => 'avançado']);

        $this->alunoBasico = $this->aluno('EM CURSO', $this->basico);
        $this->alunoAvancado = $this->aluno('EM CURSO', $this->avancado);
        $this->alunoInativo = $this->aluno('INATIVO', $this->basico);
        $this->exAluno = $this->aluno('EM CURSO', $this->basico, 'CANCELADO');
    }

    private function aluno(string $status, Nivel $nivel, string $matricula = 'ATIVO'): Aluno
    {
        $aluno = Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'x', 'foto_aluno' => '', 'status_aluno' => $status,
        ]);
        Matricula::create([
            'id_aluno' => $aluno->id_aluno, 'id_curso' => $this->curso->id_curso, 'id_nivel' => $nivel->id_nivel,
            'data_matricula' => now(), 'status_matricula' => $matricula,
        ]);

        return $aluno;
    }

    private function avisosDe(Aluno $aluno)
    {
        return Notificacao::where('id_aluno', $aluno->id_aluno)->get();
    }

    private function dadosAula(array $extra = []): array
    {
        return $extra + [
            'titulo_aulas' => 'Verbo To Be', 'descricao_aulas' => 'Desc', 'data_aulas' => '2026-12-12',
            'hora_aulas' => '18:00', 'id_professor' => $this->professor->id_professor,
            'id_curso' => $this->curso->id_curso, 'cursos_aulas' => 'Inglês', 'status_aulas' => 'ATIVO',
        ];
    }

    public function test_aula_nova_avisa_so_alunos_ativos_do_curso(): void
    {
        $this->actingAs($this->professor, 'admin')->post('/admin/aulas', $this->dadosAula())->assertRedirect();

        foreach ([$this->alunoBasico, $this->alunoAvancado] as $aluno) {
            $avisos = $this->avisosDe($aluno);
            $this->assertCount(1, $avisos);
            $this->assertSame('Nova aula: Verbo To Be — 12/12 às 18:00', $avisos[0]->mensagem_notificacoes);
            $this->assertSame('/agenda', $avisos[0]->link_notificacoes);
        }
        $this->assertCount(0, $this->avisosDe($this->alunoInativo));
        $this->assertCount(0, $this->avisosDe($this->exAluno));
    }

    public function test_aula_de_modulo_avisa_so_o_nivel_do_modulo_e_inativa_nao_avisa(): void
    {
        $modulo = Modulo::create([
            'id_curso' => $this->curso->id_curso, 'id_nivel' => $this->avancado->id_nivel, 'ordem_modulo' => 1,
            'nome_modulo' => 'Avançado 1', 'carga_horaria_minutos' => 60, 'status_modulo' => 'ATIVO',
        ]);

        $this->actingAs($this->professor, 'admin')
            ->post('/admin/aulas', $this->dadosAula(['id_modulo' => $modulo->id_modulo]))->assertRedirect();
        $this->actingAs($this->professor, 'admin')
            ->post('/admin/aulas', $this->dadosAula(['titulo_aulas' => 'Cancelada', 'status_aulas' => 'CANCELADO']))->assertRedirect();

        $this->assertCount(1, $this->avisosDe($this->alunoAvancado));
        $this->assertCount(0, $this->avisosDe($this->alunoBasico));
    }

    public function test_atividade_e_material_novos_avisam_com_link_da_tela(): void
    {
        $this->actingAs($this->professor, 'admin')->post('/admin/atividades', [
            'titulo_atividade' => 'Conversa no aeroporto', 'id_curso' => $this->curso->id_curso,
            'data_entrega' => '2026-08-15', 'enunciado' => ['Qual é a data?'], 'tipo_questao' => ['texto'],
        ])->assertRedirect();

        $atividade = Atividade::where('titulo_atividade', 'Conversa no aeroporto')->firstOrFail();
        $aviso = $this->avisosDe($this->alunoBasico)->first();
        $this->assertSame('Nova atividade: Conversa no aeroporto — entrega 15/08', $aviso->mensagem_notificacoes);
        $this->assertSame("/atividade?id={$atividade->id_atividade}", $aviso->link_notificacoes);

        $this->actingAs($this->professor, 'admin')->post('/admin/materiais', [
            'id_professor' => $this->professor->id_professor, 'titulo_materiais' => 'Apostila',
            'descricao_materiais' => 'PDF', 'nivel_material' => 'básico', 'id_curso' => $this->curso->id_curso,
        ])->assertRedirect();

        $this->assertTrue($this->avisosDe($this->alunoBasico)->contains(
            fn ($n) => $n->mensagem_notificacoes === 'Novo material: Apostila' && $n->link_notificacoes === '/materiais'
        ));
    }

    public function test_correcao_duvida_e_reagendamento_avisam_so_o_aluno(): void
    {
        $atividade = Atividade::create([
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->curso->id_curso,
            'titulo_atividade' => 'Pronúncia', 'data_entrega' => '2026-07-30', 'status_atividade' => 'ATIVA',
        ]);
        $resposta = AtividadeResposta::create([
            'id_atividade' => $atividade->id_atividade, 'id_aluno' => $this->alunoBasico->id_aluno, 'status_resposta' => 'ENVIADA',
        ]);
        $this->actingAs($this->professor, 'admin')
            ->put("/admin/atividades/corrigir/{$resposta->id_resposta}", ['nota' => 8.5, 'feedback_professor' => 'Bom'])
            ->assertRedirect();

        $duvida = Duvida::create([
            'id_aluno' => $this->alunoBasico->id_aluno, 'assunto_duvida' => 'Verbo', 'mensagem_duvida' => '?', 'status_duvida' => 'pendente',
        ]);
        $this->actingAs($this->professor, 'admin')
            ->put("/admin/duvidas/{$duvida->id_duvida}/responder", ['resposta_professor' => 'Assim.'])
            ->assertRedirect();

        $aula = Aula::create($this->dadosAula());
        $reagendamento = Reagendamento::create([
            'aluno_id' => $this->alunoBasico->id_aluno, 'aula_id' => $aula->id_aulas,
            'professor_id' => $this->professor->id_professor, 'data_original' => '2026-12-12 18:00:00',
            'motivo' => 'Trabalho no horário', 'status' => 'pendente',
            'notificado_professor' => false, 'notificado_aluno' => false,
        ]);
        $this->actingAs($this->professor, 'admin')
            ->put("/admin/reagendamentos/{$reagendamento->id}/recusar", ['resposta_professor' => 'Não dá'])
            ->assertRedirect();

        $mensagens = $this->avisosDe($this->alunoBasico)->pluck('mensagem_notificacoes')->all();
        $this->assertContains('Sua atividade "Pronúncia" foi corrigida: nota 8,5', $mensagens);
        $this->assertContains('O professor respondeu sua dúvida: Verbo', $mensagens);
        $this->assertContains('Seu pedido de reagendamento da aula "Verbo To Be" foi recusado', $mensagens);
        $this->assertCount(0, $this->avisosDe($this->alunoAvancado));
    }

    public function test_api_lista_conta_e_marca_como_lida(): void
    {
        $token = $this->alunoBasico->createToken('app')->plainTextToken;
        $outroToken = $this->alunoAvancado->createToken('app')->plainTextToken;

        $antigo = Notificacao::create([
            'id_aluno' => $this->alunoBasico->id_aluno, 'id_professor' => $this->professor->id_professor,
            'mensagem_notificacoes' => 'Antigo', 'link_notificacoes' => '/agenda', 'lida_notificacoes' => false,
            'data_criacao_notificacoes' => now()->subDay(),
        ]);
        Notificacao::create([
            'id_aluno' => $this->alunoBasico->id_aluno, 'id_professor' => $this->professor->id_professor,
            'mensagem_notificacoes' => 'Novo', 'link_notificacoes' => '/materiais', 'lida_notificacoes' => false,
            'data_criacao_notificacoes' => now(),
        ]);

        $this->getJson('/api/v1/aluno/notificacoes')->assertUnauthorized();

        $this->withToken($token)->getJson('/api/v1/aluno/notificacoes')
            ->assertOk()
            ->assertJsonPath('data.nao_lidas', 2)
            ->assertJsonPath('data.notificacoes.0.mensagem', 'Novo')
            ->assertJsonPath('data.notificacoes.0.link', '/materiais');

        // O aviso de outro aluno não pode ser marcado.
        $this->app['auth']->forgetGuards();
        $this->withToken($outroToken)->postJson("/api/v1/aluno/notificacoes/{$antigo->id_notificacoes}/lida")->assertNotFound();
        $this->assertFalse((bool) $antigo->fresh()->lida_notificacoes);

        $this->app['auth']->forgetGuards();
        $this->withToken($token)->postJson("/api/v1/aluno/notificacoes/{$antigo->id_notificacoes}/lida")->assertOk();
        $this->assertTrue((bool) $antigo->fresh()->lida_notificacoes);

        $this->withToken($token)->postJson('/api/v1/aluno/notificacoes/lidas')->assertOk();
        $this->withToken($token)->getJson('/api/v1/aluno/notificacoes')->assertJsonPath('data.nao_lidas', 0);
    }
}
