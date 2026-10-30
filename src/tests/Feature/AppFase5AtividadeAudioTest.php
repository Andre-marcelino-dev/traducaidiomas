<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\AtividadeQuestao;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Matricula;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * API do traduca-APP: questão de áudio em Atividades (aluno grava a resposta
 * com o microfone, o professor ouve e corrige).
 */
class AppFase5AtividadeAudioTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Curso $ingles;
    private Aluno $aluno;
    private string $token;
    private Atividade $atividade;
    private AtividadeQuestao $questaoAudio;

    protected function setUp(): void
    {
        parent::setUp();

        $this->professor = Professor::create([
            'nome_professor' => 'Renato Caetano', 'especialidade_professor' => 'Inglês', 'experiencia_professor' => '10',
            'bio_professor' => 'Bio', 'foto_professor' => '', 'email_professor' => uniqid() . '@prof.test',
            'curso_professor' => 'Inglês', 'nivel_professor' => 'Todos', 'telefone_professor' => '1',
            'senha_professor' => Hash::make('segredo123'),
        ]);

        $nivel = Nivel::create(['nome_nivel' => 'Intermediário']);
        $this->ingles = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));

        $this->aluno = Aluno::create([
            'nome_aluno' => 'Aluno ' . uniqid(), 'email_aluno' => uniqid() . '@aluno.test',
            'senha_aluno' => Hash::make('segredo123'), 'telefone_aluno' => '1', 'curso_aluno' => 'Inglês',
            'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'Intermediário', 'foto_aluno' => '',
            'status_aluno' => 'EM CURSO',
        ]);

        Matricula::create([
            'id_aluno' => $this->aluno->id_aluno, 'id_curso' => $this->ingles->id_curso,
            'id_nivel' => $nivel->id_nivel, 'data_matricula' => now(), 'status_matricula' => 'ATIVO',
        ]);

        $this->token = $this->aluno->createToken('app')->plainTextToken;

        $this->atividade = Atividade::create([
            'id_professor' => $this->professor->id_professor, 'id_curso' => $this->ingles->id_curso,
            'titulo_atividade' => 'Pronúncia do TH', 'descricao_atividade' => 'Grave sua pronúncia.',
            'categoria_atividade' => 'FALA', 'finalidade_atividade' => 'FIXACAO',
            'data_entrega' => '2026-12-01', 'status_atividade' => 'ATIVA',
        ]);

        $this->questaoAudio = AtividadeQuestao::create([
            'id_atividade' => $this->atividade->id_atividade,
            'enunciado' => 'Grave você falando "think, three, bathroom".',
            'tipo_questao' => 'audio', 'ordem' => 1,
        ]);
    }

    private function audioFake(string $nome = 'fala.webm'): UploadedFile
    {
        return UploadedFile::fake()->create($nome, 50, 'audio/webm');
    }

    public function test_tipo_audio_aparece_na_atividade(): void
    {
        $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$this->atividade->id_atividade}")
            ->assertOk()
            ->assertJsonPath('data.questoes.0.tipo', 'audio')
            ->assertJsonPath('data.questoes.0.url_resposta_audio', null);
    }

    public function test_envia_gravacao_e_marca_atividade_como_enviada(): void
    {
        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => $this->audioFake(),
            ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('success', true);

        $detalhe = $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$this->atividade->id_atividade}")
            ->assertJsonPath('data.status', 'enviada')
            ->assertJsonPath('data.questoes.0.resposta_aluno', null); // não vaza o nome do arquivo

        $this->assertNotNull($detalhe->json('data.questoes.0.url_resposta_audio'));

        $this->assertDatabaseHas('tbl_atividade_respostas', [
            'id_atividade' => $this->atividade->id_atividade,
            'id_aluno' => $this->aluno->id_aluno,
            'status_resposta' => 'ENVIADA',
        ]);
    }

    public function test_ouve_a_propria_gravacao(): void
    {
        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => $this->audioFake(),
            ]);

        $this->withToken($this->token)
            ->get("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio")
            ->assertOk();
    }

    public function test_regrava_substitui_a_anterior(): void
    {
        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => $this->audioFake('primeira.webm'),
            ]);

        $resposta = AtividadeResposta::where('id_atividade', $this->atividade->id_atividade)->first();
        $primeiroArquivo = AtividadeRespostaQuestao::where('id_resposta', $resposta->id_resposta)->first()->resposta_aluno;

        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => $this->audioFake('segunda.webm'),
            ])->assertOk();

        $this->assertDatabaseCount('tbl_atividade_resposta_questoes', 1);
        $segundoArquivo = AtividadeRespostaQuestao::where('id_resposta', $resposta->id_resposta)->first()->resposta_aluno;
        $this->assertNotSame($primeiroArquivo, $segundoArquivo);
    }

    public function test_nao_regrava_depois_de_corrigida(): void
    {
        $resposta = AtividadeResposta::create([
            'id_atividade' => $this->atividade->id_atividade, 'id_aluno' => $this->aluno->id_aluno,
            'status_resposta' => 'CORRIGIDA', 'nota' => 9, 'data_envio' => now(),
        ]);

        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => $this->audioFake(),
            ])
            ->assertStatus(409);
    }

    public function test_arquivo_que_nao_e_audio_e_recusado(): void
    {
        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => UploadedFile::fake()->create('virus.php', 10, 'application/x-php'),
            ], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('audio');
    }

    public function test_responder_texto_nao_apaga_gravacao_de_audio(): void
    {
        $texto = AtividadeQuestao::create([
            'id_atividade' => $this->atividade->id_atividade, 'enunciado' => 'Como foi seu dia?',
            'tipo_questao' => 'texto', 'ordem' => 2,
        ]);

        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$this->questaoAudio->id_questao}/audio", [
                'audio' => $this->audioFake(),
            ]);

        $this->withToken($this->token)
            ->postJson("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/responder", [
                'respostas' => [$texto->id_questao => 'Foi bom.'],
            ])
            ->assertOk();

        $detalhe = $this->withToken($this->token)->getJson("/api/v1/aluno/atividades/{$this->atividade->id_atividade}");
        $this->assertNotNull($detalhe->json('data.questoes.0.url_resposta_audio'));
        $this->assertSame('Foi bom.', $detalhe->json('data.questoes.1.resposta_aluno'));
    }

    public function test_resposta_de_texto_nao_vira_caminho_de_arquivo(): void
    {
        // Um aluno escreve um caminho de arquivo numa questão de TEXTO e depois
        // pede a "gravação" dela: a rota de áudio não pode entregar esse arquivo.
        $texto = AtividadeQuestao::create([
            'id_atividade' => $this->atividade->id_atividade, 'enunciado' => 'Como foi seu dia?',
            'tipo_questao' => 'texto', 'ordem' => 2,
        ]);

        $this->withToken($this->token)
            ->postJson("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/responder", [
                'respostas' => [$texto->id_questao => '../.env'],
            ])
            ->assertOk();

        $this->withToken($this->token)
            ->getJson("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/{$texto->id_questao}/audio")
            ->assertNotFound();

        // Pelo painel também não.
        $rq = AtividadeRespostaQuestao::where('id_questao', $texto->id_questao)->firstOrFail();
        $this->actingAs($this->professor, 'admin')
            ->get(route('admin.atividades.resposta-questao.audio', $rq->id))
            ->assertNotFound();
    }

    public function test_questao_de_outra_atividade_da_404(): void
    {
        $this->withToken($this->token)
            ->post("/api/v1/aluno/atividades/{$this->atividade->id_atividade}/questoes/999999/audio", [
                'audio' => $this->audioFake(),
            ], ['Accept' => 'application/json'])
            ->assertNotFound();
    }
}
