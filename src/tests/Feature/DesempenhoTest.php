<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\AtividadeResposta;
use App\Models\Atividade;
use App\Models\Aula;
use App\Models\ConfiguracaoPainel;
use App\Models\Presenca;
use App\Models\Professor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Fase 4: páginas não podem voltar a fazer centenas de consultas, e os números do dashboard.
 */
class DesempenhoTest extends TestCase
{
    use RefreshDatabase;

    private function professor(): Professor
    {
        $p = Professor::create([
            'nome_professor' => 'Renato', 'especialidade_professor' => 'x', 'experiencia_professor' => 'x',
            'bio_professor' => 'x', 'foto_professor' => '', 'email_professor' => 'renato@prof.test',
            'curso_professor' => 'x', 'nivel_professor' => 'x', 'telefone_professor' => '1',
            'senha_professor' => Hash::make('segredo123'),
        ]);
        $p->forceFill(['is_admin' => true])->save();

        return $p;
    }

    private function contarConsultas(callable $acao): int
    {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $acao();
        $total = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $total;
    }

    public function test_paginas_do_site_fazem_poucas_consultas(): void
    {
        foreach (['/', '/sobre', '/servicos'] as $url) {
            $_SERVER['REQUEST_URI'] = $url;
            $this->app->forgetInstance('view.dados_globais');
            $this->app->forgetInstance(ConfiguracaoPainel::CACHE);

            $consultas = $this->contarConsultas(fn () => $this->get($url)->assertOk());

            // Antes desta correção: 157 a 173 consultas por página.
            $this->assertLessThan(15, $consultas, "$url fez $consultas consultas");
        }
    }

    public function test_dashboard_faz_poucas_consultas(): void
    {
        $prof = $this->professor();

        $consultas = $this->contarConsultas(fn () => $this->actingAs($prof, 'admin')->get('/admin')->assertOk());

        // Antes: 138.
        $this->assertLessThan(40, $consultas, "dashboard fez $consultas consultas");
    }

    public function test_configuracao_salva_aparece_na_hora(): void
    {
        $this->assertSame('padrão', ConfiguracaoPainel::get('banner1_titulo', 'padrão'));

        ConfiguracaoPainel::set('banner1_titulo', 'Título novo');

        $this->assertSame('Título novo', ConfiguracaoPainel::get('banner1_titulo', 'padrão'));
    }

    public function test_dashboard_calcula_taxa_de_presenca_e_faixas_de_nota(): void
    {
        $prof = $this->professor();
        $aluno = Aluno::create([
            'nome_aluno' => 'A', 'email_aluno' => 'a@a.test', 'senha_aluno' => 'x', 'telefone_aluno' => '1',
            'curso_aluno' => 'x', 'data_nasc_aluno' => '2000-01-01', 'nivel_aluno' => 'x', 'foto_aluno' => '',
        ]);
        $idCurso = DB::table('tbl_cursos')->insertGetId(['nome_curso' => 'Inglês', 'descricao_curso' => 'x']);
        $aula = Aula::create([
            'id_professor' => $prof->id_professor, 'id_curso' => $idCurso, 'titulo_aulas' => 'A',
            'descricao_aulas' => 'D', 'data_aulas' => '2026-09-01', 'hora_aulas' => '19:00',
            'cursos_aulas' => 'x', 'status_aulas' => 'ATIVO',
        ]);
        // 3 presenças e 1 falta = 75%
        foreach (['presente', 'presente', 'presente', 'falta'] as $i => $status) {
            Presenca::create([
                'id_aulas' => $aula->id_aulas, 'id_aluno' => $aluno->id_aluno,
                'status_presenca' => $status, 'data_registro_presenca' => '2026-09-0' . ($i + 1),
            ]);
        }
        $atividade = Atividade::create([
            'id_professor' => $prof->id_professor, 'id_curso' => $idCurso, 'titulo_atividade' => 'Q', 'status_atividade' => 'ATIVA',
        ]);
        foreach ([4.5, 6.5, 8.5, 10] as $nota) {
            AtividadeResposta::create([
                'id_atividade' => $atividade->id_atividade, 'id_aluno' => $aluno->id_aluno,
                'status_resposta' => 'CORRIGIDA', 'nota' => $nota,
            ]);
        }

        $resposta = $this->actingAs($prof, 'admin')->get('/admin')->assertOk();

        $this->assertSame(75.0, (float) $resposta->viewData('taxaPresenca'));
        $this->assertSame(['0–4' => 1, '5–6' => 1, '7–8' => 1, '9–10' => 1], $resposta->viewData('notasFaixas'));
    }
}
