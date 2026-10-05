<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Professor;
use App\Support\CursoAtual;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Professor define categoria (Gramática, Áudio, Fala, Leitura...) e finalidade da atividade.
 */
class AtividadeCategoriaTest extends TestCase
{
    use RefreshDatabase;

    private Professor $professor;
    private Curso $ingles;
    private Curso $italiano;

    protected function setUp(): void
    {
        parent::setUp();

        $this->professor = Professor::create([
            'nome_professor'          => 'Renato Caetano',
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

        $this->ingles = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'Inglês']));
        $this->italiano = Curso::find(DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Italiano', 'descricao_curso' => 'Italiano']));
    }

    private function payload(array $extra = []): array
    {
        return array_merge([
            'titulo_atividade' => 'Conjunção de verbo',
            'id_curso'         => $this->ingles->id_curso,
            'data_entrega'     => '2026-07-20',
            'enunciado'        => ['Complete a frase'],
            'tipo_questao'     => ['texto'],
        ], $extra);
    }

    public function test_professor_salva_categoria_e_finalidade(): void
    {
        $this->actingAs($this->professor, 'admin')
            ->post(route('admin.atividades.store'), $this->payload([
                'categoria_atividade'  => 'GRAMATICA',
                'finalidade_atividade' => 'REVISAO',
            ]))
            ->assertRedirect(route('admin.atividades.index'));

        $atividade = Atividade::firstOrFail();
        $this->assertSame('GRAMATICA', $atividade->categoria_atividade);
        $this->assertSame('REVISAO', $atividade->finalidade_atividade);
        $this->assertSame('Gramática', $atividade->categoriaInfo()['label']);
    }

    public function test_campos_sao_opcionais_e_finalidade_padrao_e_fixacao(): void
    {
        $this->actingAs($this->professor, 'admin')
            ->post(route('admin.atividades.store'), $this->payload())
            ->assertRedirect(route('admin.atividades.index'));

        $atividade = Atividade::firstOrFail();
        $this->assertNull($atividade->categoria_atividade);
        $this->assertSame('FIXACAO', $atividade->finalidade_atividade);
    }

    public function test_categoria_invalida_e_rejeitada(): void
    {
        $this->actingAs($this->professor, 'admin')
            ->post(route('admin.atividades.store'), $this->payload(['categoria_atividade' => 'XPTO']))
            ->assertSessionHasErrors('categoria_atividade');

        $this->assertSame(0, Atividade::count());
    }

    public function test_lista_mostra_selo_e_filtra_por_idioma(): void
    {
        foreach ([[$this->ingles, 'Conversa no aeroporto', 'AUDIO'], [$this->italiano, 'Leitura de crônica', 'LEITURA']] as [$curso, $titulo, $cat]) {
            Atividade::create([
                'id_professor'        => $this->professor->id_professor,
                'id_curso'            => $curso->id_curso,
                'titulo_atividade'    => $titulo,
                'categoria_atividade' => $cat,
                'data_entrega'        => '2026-07-20',
            ]);
        }

        $this->actingAs($this->professor, 'admin')
            ->get(route('admin.atividades.index'))
            ->assertOk()
            ->assertSee('Áudio')
            ->assertSee('Leitura')
            ->assertSee('Prof. Renato Caetano');

        $this->actingAs($this->professor, 'admin')
            ->get(route('admin.atividades.index', ['curso' => $this->italiano->id_curso]))
            ->assertOk()
            ->assertSee('Leitura de crônica')
            ->assertDontSee('Conversa no aeroporto');
    }

    public function test_aluno_ve_selo_finalidade_e_professor_no_card(): void
    {
        $nivel = Nivel::create(['nome_nivel' => 'Iniciante']);
        $aluno = Aluno::create([
            'nome_aluno'      => 'Aluno Teste',
            'email_aluno'     => 'aluno@aluno.test',
            'senha_aluno'     => Hash::make('segredo123'),
            'telefone_aluno'  => '11999999999',
            'curso_aluno'     => 'Inglês',
            'data_nasc_aluno' => '2000-01-01',
            'nivel_aluno'     => 'Iniciante',
            'foto_aluno'      => '',
            'status_aluno'    => 'EM CURSO',
        ]);
        $matricula = Matricula::create([
            'id_aluno'         => $aluno->id_aluno,
            'id_curso'         => $this->ingles->id_curso,
            'id_nivel'         => $nivel->id_nivel,
            'data_matricula'   => now(),
            'status_matricula' => 'ATIVO',
        ]);
        Atividade::create([
            'id_professor'         => $this->professor->id_professor,
            'id_curso'             => $this->ingles->id_curso,
            'titulo_atividade'     => 'Pronúncia do TH',
            'categoria_atividade'  => 'FALA',
            'finalidade_atividade' => 'FIXACAO',
            'data_entrega'         => '2026-07-20',
        ]);

        $this->actingAs($aluno, 'aluno')
            ->withSession([CursoAtual::SESSAO => $matricula->id_matricula])
            ->get(route('aluno.atividades.index'))
            ->assertOk()
            ->assertSee('Pronúncia do TH')
            ->assertSee('fa-microphone')
            ->assertSee('Exercício de fixação')
            ->assertSee('Professor: Renato Caetano');
    }
}
