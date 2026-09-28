<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\Curso;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Professor;
use App\Support\CursoAtual;
use App\Support\Upload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Garante as correções de segurança da fase 1.
 */
class SegurancaTest extends TestCase
{
    use RefreshDatabase;

    private function professor(bool $admin, string $email = null): Professor
    {
        $professor = Professor::create([
            'nome_professor'          => 'Prof ' . uniqid(),
            'especialidade_professor' => 'Inglês',
            'experiencia_professor'   => '5 anos',
            'bio_professor'           => 'Bio',
            'foto_professor'          => '',
            'email_professor'         => $email ?? uniqid() . '@prof.test',
            'curso_professor'         => 'Inglês',
            'nivel_professor'         => 'Todos',
            'telefone_professor'      => '11999999999',
            'senha_professor'         => Hash::make('segredo123'),
        ]);
        $professor->forceFill(['is_admin' => $admin])->save();

        return $professor;
    }

    private function aluno(): Aluno
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

    private function curso(string $nome): Curso
    {
        $id = DB::table('tbl_cursos')->insertGetId(['nome_curso' => $nome, 'descricao_curso' => $nome]);

        return Curso::find($id);
    }

    private function matricular(Aluno $aluno, Curso $curso, Nivel $nivel): Matricula
    {
        return Matricula::create([
            'id_aluno'         => $aluno->id_aluno,
            'id_curso'         => $curso->id_curso,
            'id_nivel'         => $nivel->id_nivel,
            'data_matricula'   => now(),
            'status_matricula' => 'ATIVO',
        ]);
    }

    private function material(Curso $curso, Professor $professor): Materiais
    {
        return Materiais::create([
            'id_professor'        => $professor->id_professor,
            'titulo_materiais'    => 'Apostila ' . $curso->nome_curso,
            'descricao_materiais' => 'Desc',
            'arquivo_materiais'   => 'traducaidiomas/materiais/nao-existe.pdf',
            'nivel_material'      => 'Iniciante',
            'id_curso'            => $curso->id_curso,
        ]);
    }

    // ── Rotas removidas ──

    public function test_rota_publica_de_alunos_foi_removida(): void
    {
        $this->get('/alunos')->assertNotFound();
    }

    public function test_recuperacao_de_senha_por_telefone_foi_removida(): void
    {
        $this->get('/admin/recuperar-senha')->assertNotFound();
        $this->post('/admin/recuperar-senha', ['email_professor' => 'a@b.c', 'telefone_professor' => '1'])->assertNotFound();
        $this->get('/admin/verificar')->assertNotFound();
    }

    // ── API ──

    public function test_api_de_gestao_exige_token(): void
    {
        $this->getJson('/api/v1/alunos')->assertUnauthorized();
        $this->deleteJson('/api/v1/alunos/1')->assertUnauthorized();
        $this->getJson('/api/v1/aulas')->assertUnauthorized();
        // Sem o header Accept também responde 401 (não redireciona para o login do painel).
        $this->get('/api/v1/alunos')->assertUnauthorized();
    }

    public function test_token_de_aluno_nao_acessa_gestao(): void
    {
        $token = $this->aluno()->createToken('app')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/alunos')->assertForbidden();
        $this->withToken($token)->getJson('/api/v1/aulas')->assertForbidden();
    }

    public function test_professor_comum_nao_gerencia_alunos_pela_api(): void
    {
        $token = $this->professor(false)->createToken('api')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/aulas')->assertOk();
        $this->withToken($token)->getJson('/api/v1/alunos')->assertForbidden();
    }

    public function test_professor_admin_gerencia_alunos_pela_api(): void
    {
        $token = $this->professor(true)->createToken('api')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/alunos')->assertOk();
    }

    public function test_login_de_professor_na_api(): void
    {
        $this->professor(true, 'renato@prof.test');

        $this->postJson('/api/v1/professor/login', [
            'email_professor' => 'renato@prof.test',
            'senha_professor' => 'errada',
        ])->assertUnauthorized();

        $token = $this->postJson('/api/v1/professor/login', [
            'email_professor' => 'renato@prof.test',
            'senha_professor' => 'segredo123',
        ])->assertOk()->json('data.token');

        $this->withToken($token)->getJson('/api/v1/professor/me')
            ->assertOk()
            ->assertJsonPath('data.is_admin', true);
    }

    public function test_token_de_professor_nao_acessa_rotas_do_aluno(): void
    {
        $token = $this->professor(true)->createToken('api')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/aluno/cursos')->assertForbidden();
    }

    // ── Limite de tentativas ──

    public function test_login_do_painel_bloqueia_apos_5_tentativas(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email_professor' => 'x@y.z', 'senha_professor' => 'errada'])->assertRedirect();
        }

        $this->post('/admin/login', ['email_professor' => 'x@y.z', 'senha_professor' => 'errada'])->assertStatus(429);
    }

    public function test_chatbot_publico_limita_mensagens_por_minuto(): void
    {
        Http::fake(); // não chama a Groq de verdade

        for ($i = 0; $i < 10; $i++) {
            $this->assertNotSame(429, $this->postJson('/chatbot/mensagem', ['mensagem' => 'oi'])->status());
        }

        $this->postJson('/chatbot/mensagem', ['mensagem' => 'oi'])->assertStatus(429);
    }

    public function test_login_da_api_bloqueia_apos_5_tentativas(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/aluno/login', ['email_aluno' => 'x@y.z', 'senha_aluno' => 'errada'])->assertUnauthorized();
        }

        $this->postJson('/api/v1/aluno/login', ['email_aluno' => 'x@y.z', 'senha_aluno' => 'errada'])->assertStatus(429);
    }

    // ── Painel: somente admin ──

    public function test_professor_comum_nao_acessa_telas_de_admin(): void
    {
        $professor = $this->professor(false);

        foreach (['/admin/professores', '/admin/alunos', '/admin/matriculas', '/admin/modulos', '/admin/servicos', '/admin/site'] as $url) {
            $this->actingAs($professor, 'admin')->get($url)->assertForbidden();
        }
    }

    public function test_telas_alteradas_abrem_para_o_admin(): void
    {
        $admin = $this->professor(true);
        $outro = $this->professor(false);
        $material = $this->material($this->curso('Inglês'), $admin);

        $this->actingAs($admin, 'admin')->get('/admin/professores')->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin/professores/create')->assertOk()->assertSee('name="is_admin"', false);
        $this->actingAs($admin, 'admin')->get('/admin/professores/' . $outro->id_professor . '/edit')->assertOk()->assertSee('name="is_admin"', false);
        $this->actingAs($admin, 'admin')->get('/admin/materiais/' . $material->id_materiais)->assertOk()
            ->assertSee(route('admin.materiais.download', $material->id_materiais), false);
        $this->actingAs($admin, 'admin')->get('/admin/materiais/' . $material->id_materiais . '/edit')->assertOk();
        $this->actingAs($admin, 'admin')->get('/admin')->assertOk()->assertSee(route('admin.site.index'), false);
        $this->get('/admin/login')->assertRedirect(); // já logado
    }

    public function test_menu_esconde_itens_de_admin_do_professor_comum(): void
    {
        $professor = $this->professor(false);

        $this->actingAs($professor, 'admin')->get('/admin/aulas')
            ->assertOk()
            ->assertDontSee(route('admin.professores.index'), false)
            ->assertDontSee(route('admin.site.index'), false)
            ->assertSee(route('admin.materiais.index'), false);
    }

    public function test_tela_de_login_do_painel_abre(): void
    {
        $this->get('/admin/login')->assertOk()->assertDontSee('recuperar-senha');
    }

    public function test_admin_nao_remove_a_si_mesmo(): void
    {
        $admin = $this->professor(true);

        $this->actingAs($admin, 'admin')
            ->delete('/admin/professores/' . $admin->id_professor)
            ->assertRedirect();

        $this->assertDatabaseHas('tbl_professor', ['id_professor' => $admin->id_professor]);
    }

    // ── Aluno não acessa conteúdo de outro curso ──

    public function test_aluno_nao_abre_material_de_outro_curso(): void
    {
        $professor = $this->professor(false);
        $nivel = Nivel::create(['nome_nivel' => 'Iniciante']);
        $ingles = $this->curso('Inglês');
        $italiano = $this->curso('Italiano');

        $aluno = $this->aluno();
        $matricula = $this->matricular($aluno, $ingles, $nivel);

        $doCurso = $this->material($ingles, $professor);
        $deOutroCurso = $this->material($italiano, $professor);

        $sessao = [CursoAtual::SESSAO => $matricula->id_matricula];

        // Material do próprio curso: passa da checagem (arquivo não existe, então volta com erro).
        $this->actingAs($aluno, 'aluno')->withSession($sessao)
            ->get('/aluno/materiais/' . $doCurso->id_materiais . '/download')
            ->assertRedirect();

        foreach (['', '/download', '/visualizar'] as $sufixo) {
            $this->actingAs($aluno, 'aluno')->withSession($sessao)
                ->get('/aluno/materiais/' . $deOutroCurso->id_materiais . $sufixo)
                ->assertNotFound();
        }
    }

    public function test_aluno_nao_abre_nem_responde_atividade_de_outro_curso(): void
    {
        $professor = $this->professor(false);
        $nivel = Nivel::create(['nome_nivel' => 'Iniciante']);
        $ingles = $this->curso('Inglês');
        $italiano = $this->curso('Italiano');

        $aluno = $this->aluno();
        $matricula = $this->matricular($aluno, $ingles, $nivel);

        $atividade = Atividade::create([
            'id_professor'     => $professor->id_professor,
            'id_curso'         => $italiano->id_curso,
            'titulo_atividade' => 'Atividade de italiano',
            'status_atividade' => 'ATIVA',
        ]);

        $sessao = [CursoAtual::SESSAO => $matricula->id_matricula];

        $this->actingAs($aluno, 'aluno')->withSession($sessao)
            ->get('/aluno/atividades/' . $atividade->id_atividade)
            ->assertNotFound();

        $this->actingAs($aluno, 'aluno')->withSession($sessao)
            ->post('/aluno/atividades/' . $atividade->id_atividade . '/responder')
            ->assertNotFound();

        $this->assertDatabaseCount('tbl_atividade_respostas', 0);
    }

    // ── Dados pessoais ──

    public function test_pagina_sobre_nao_mostra_email_dos_alunos(): void
    {
        $nivel = Nivel::create(['nome_nivel' => 'Iniciante']);
        $aluno = $this->aluno();
        $this->matricular($aluno, $this->curso('Inglês'), $nivel);

        // O header do site lê a global $_SERVER['REQUEST_URI'] direto.
        $_SERVER['REQUEST_URI'] = '/sobre';

        $this->get('/sobre')
            ->assertOk()
            ->assertSee($aluno->nome_aluno)
            ->assertDontSee($aluno->email_aluno);
    }

    // ── Upload ──

    public function test_upload_nao_salva_arquivo_php(): void
    {
        $pasta = sys_get_temp_dir() . '/traduca-teste-' . uniqid();
        mkdir($pasta);
        $this->app->usePublicPath($pasta);

        // PHP disfarçado de imagem: começa com a assinatura de um PNG.
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        // Arquivo real (o fake do Laravel detecta o tipo pelo nome, não pelo conteúdo).
        $tmp = tempnam(sys_get_temp_dir(), 'up');
        file_put_contents($tmp, $png . '<?php echo 1; ?>');
        $arquivo = new UploadedFile($tmp, 'shell.php', null, null, true);

        $nome = Upload::salvar($arquivo, 'alunos', Upload::IMAGENS, 'foto');

        $this->assertStringEndsWith('.png', $nome);
        $this->assertFileExists($pasta . '/traducaidiomas/alunos/' . $nome);
        $this->assertEmpty(glob($pasta . '/traducaidiomas/alunos/*.php'));
    }

    public function test_upload_recusa_tipo_nao_permitido(): void
    {
        $this->app->usePublicPath(sys_get_temp_dir());
        $tmp = tempnam(sys_get_temp_dir(), 'up');
        file_put_contents($tmp, '<?php echo 1; ?>');
        $arquivo = new UploadedFile($tmp, 'script.php', null, null, true);

        $this->expectException(\RuntimeException::class);
        Upload::salvar($arquivo, 'alunos', Upload::IMAGENS, 'foto');
    }
}
