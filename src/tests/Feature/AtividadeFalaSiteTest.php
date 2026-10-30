<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\AtividadeQuestao;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Professor;
use App\Support\CursoAtual;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Site do aluno: questão de fala (o aluno grava a resposta no navegador),
 * com as mesmas regras da API do app.
 */
class AtividadeFalaSiteTest extends TestCase
{
    use RefreshDatabase;

    private Aluno $aluno;
    private Matricula $matricula;
    private Atividade $atividade;
    private AtividadeQuestao $questaoAudio;
    private AtividadeQuestao $questaoTexto;

    protected function setUp(): void
    {
        parent::setUp();

        $professor = Professor::create([
            'nome_professor' => 'Prof', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '10',
            'bio_professor' => 'Bio', 'foto_professor' => '', 'email_professor' => uniqid() . '@prof.test',
            'curso_professor' => 'Inglês', 'nivel_professor' => 'Todos', 'telefone_professor' => '1',
            'senha_professor' => Hash::make('segredo123'),
        ]);
        $nivel = Nivel::create(['nome_nivel' => 'Intermediário']);
        $ingles = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));

        $this->aluno = $this->novoAluno();
        $this->matricula = $this->matricular($this->aluno, $ingles, $nivel);

        $this->atividade = Atividade::create([
            'id_professor' => $professor->id_professor, 'id_curso' => $ingles->id_curso,
            'titulo_atividade' => 'Pronúncia do TH', 'descricao_atividade' => 'Grave sua pronúncia.',
            'categoria_atividade' => 'FALA', 'finalidade_atividade' => 'FIXACAO',
            'data_entrega' => '2026-12-01', 'status_atividade' => 'ATIVA',
        ]);
        $this->questaoAudio = AtividadeQuestao::create([
            'id_atividade' => $this->atividade->id_atividade,
            'enunciado' => 'Grave você falando "think, three, bathroom".', 'tipo_questao' => 'audio', 'ordem' => 1,
        ]);
        $this->questaoTexto = AtividadeQuestao::create([
            'id_atividade' => $this->atividade->id_atividade,
            'enunciado' => 'Como foi seu dia?', 'tipo_questao' => 'texto', 'ordem' => 2,
        ]);
    }

    protected function tearDown(): void
    {
        // Não deixa as gravações do teste na pasta de respostas.
        foreach (AtividadeRespostaQuestao::where('id_questao', $this->questaoAudio->id_questao)->pluck('resposta_aluno') as $arquivo) {
            if ($arquivo && is_file(public_path($arquivo))) {
                @unlink(public_path($arquivo));
            }
        }

        parent::tearDown();
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

    private function matricular(Aluno $aluno, Curso $curso, Nivel $nivel): Matricula
    {
        return Matricula::create([
            'id_aluno' => $aluno->id_aluno, 'id_curso' => $curso->id_curso,
            'id_nivel' => $nivel->id_nivel, 'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);
    }

    private function comoAluno(?Aluno $aluno = null, ?Matricula $matricula = null): self
    {
        return $this->actingAs($aluno ?? $this->aluno, 'aluno')
            ->withSession([CursoAtual::SESSAO => ($matricula ?? $this->matricula)->id_matricula]);
    }

    private function url(string $sufixo = ''): string
    {
        return '/aluno/atividades/' . $this->atividade->id_atividade . $sufixo;
    }

    private function audioFake(): UploadedFile
    {
        return UploadedFile::fake()->create('resposta.webm', 50, 'audio/webm');
    }

    public function test_questao_de_fala_mostra_o_gravador(): void
    {
        $this->comoAluno()->get($this->url())
            ->assertOk()
            ->assertSee('Fala (gravação)')
            ->assertSee('name="audio_' . $this->questaoAudio->id_questao . '"', false)
            ->assertSee('enctype="multipart/form-data"', false);
    }

    public function test_envia_gravacao_pelo_site_e_aluno_ouve_de_novo(): void
    {
        $this->comoAluno()->post($this->url('/responder'), [
            'audio_' . $this->questaoAudio->id_questao => $this->audioFake(),
            'questao_' . $this->questaoTexto->id_questao => 'Foi bom.',
        ])->assertRedirect(route('aluno.atividades.index'));

        $resposta = AtividadeResposta::where('id_aluno', $this->aluno->id_aluno)->firstOrFail();
        $this->assertSame('ENVIADA', $resposta->status_resposta);

        $gravacao = $resposta->respostasQuestoes()->where('id_questao', $this->questaoAudio->id_questao)->firstOrFail();
        $this->assertStringStartsWith(AtividadeRespostaQuestao::PASTA_GRAVACOES, $gravacao->resposta_aluno);
        $this->assertFileExists(public_path($gravacao->resposta_aluno));
        $this->assertSame('Foi bom.', $resposta->respostasQuestoes()->where('id_questao', $this->questaoTexto->id_questao)->value('resposta_aluno'));

        // A tela mostra o player e a rota devolve o arquivo ao próprio aluno.
        $this->comoAluno()->get($this->url())->assertSee('Sua gravação');
        $this->comoAluno()->get($this->url('/questoes/' . $this->questaoAudio->id_questao . '/audio'))->assertOk();
    }

    public function test_sem_gravacao_nao_envia(): void
    {
        $this->comoAluno()->from($this->url())->post($this->url('/responder'), [
            'questao_' . $this->questaoTexto->id_questao => 'Foi bom.',
        ])->assertRedirect($this->url())->assertSessionHas('error', 'Grave sua resposta da questão 1 antes de enviar.');

        $this->assertNotSame('ENVIADA', AtividadeResposta::where('id_aluno', $this->aluno->id_aluno)->value('status_resposta'));
    }

    public function test_arquivo_que_nao_e_audio_e_recusado(): void
    {
        $this->comoAluno()->from($this->url())->post($this->url('/responder'), [
            'audio_' . $this->questaoAudio->id_questao => UploadedFile::fake()->create('virus.php', 10, 'application/x-php'),
            'questao_' . $this->questaoTexto->id_questao => 'Foi bom.',
        ])->assertSessionHasErrors('audio_' . $this->questaoAudio->id_questao);

        $this->assertSame(0, AtividadeRespostaQuestao::count());
    }

    public function test_enviar_pelo_site_nao_apaga_gravacao_feita_no_app(): void
    {
        $token = $this->aluno->createToken('app')->plainTextToken;
        $this->withToken($token)->post(
            "/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio",
            ['audio' => $this->audioFake()],
            ['Accept' => 'application/json']
        )->assertOk();
        $doApp = AtividadeRespostaQuestao::where('id_questao', $this->questaoAudio->id_questao)->value('resposta_aluno');

        // Sem gravação nova: a do app vale, e o texto é salvo.
        $this->app['auth']->forgetGuards();
        $this->comoAluno()->post($this->url('/responder'), [
            'questao_' . $this->questaoTexto->id_questao => 'Foi bom.',
        ])->assertRedirect(route('aluno.atividades.index'));

        $this->assertSame($doApp, AtividadeRespostaQuestao::where('id_questao', $this->questaoAudio->id_questao)->value('resposta_aluno'));
        $this->assertFileExists(public_path($doApp));
        $this->assertSame('Foi bom.', AtividadeRespostaQuestao::where('id_questao', $this->questaoTexto->id_questao)->value('resposta_aluno'));
    }

    public function test_regravar_pelo_site_substitui_e_apaga_o_arquivo_antigo(): void
    {
        $dados = ['questao_' . $this->questaoTexto->id_questao => 'Foi bom.'];

        $this->comoAluno()->post($this->url('/responder'), $dados + ['audio_' . $this->questaoAudio->id_questao => $this->audioFake()]);
        $primeira = AtividadeRespostaQuestao::where('id_questao', $this->questaoAudio->id_questao)->value('resposta_aluno');

        $this->comoAluno()->post($this->url('/responder'), $dados + ['audio_' . $this->questaoAudio->id_questao => $this->audioFake()]);
        $segunda = AtividadeRespostaQuestao::where('id_questao', $this->questaoAudio->id_questao)->value('resposta_aluno');

        $this->assertNotSame($primeira, $segunda);
        $this->assertFileDoesNotExist(public_path($primeira));
        $this->assertFileExists(public_path($segunda));
        $this->assertSame(1, AtividadeRespostaQuestao::where('id_questao', $this->questaoAudio->id_questao)->count());
    }

    public function test_outro_aluno_nao_ouve_a_gravacao(): void
    {
        $this->comoAluno()->post($this->url('/responder'), [
            'audio_' . $this->questaoAudio->id_questao => $this->audioFake(),
            'questao_' . $this->questaoTexto->id_questao => 'Foi bom.',
        ]);

        $outro = $this->novoAluno();
        $matriculaOutro = Matricula::create([
            'id_aluno' => $outro->id_aluno, 'id_curso' => $this->matricula->id_curso,
            'id_nivel' => $this->matricula->id_nivel, 'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        $this->app['auth']->forgetGuards();
        $this->comoAluno($outro, $matriculaOutro)
            ->get($this->url('/questoes/' . $this->questaoAudio->id_questao . '/audio'))
            ->assertNotFound();
    }

    public function test_resposta_de_texto_nao_vira_download_pelo_site(): void
    {
        $this->comoAluno()->post($this->url('/responder'), [
            'audio_' . $this->questaoAudio->id_questao => $this->audioFake(),
            'questao_' . $this->questaoTexto->id_questao => '../.env',
        ]);

        $this->comoAluno()->get($this->url('/questoes/' . $this->questaoTexto->id_questao . '/audio'))->assertNotFound();
    }
}
