<?php

namespace Tests\Feature;

use App\Models\QuizResultado;
use App\Support\QuizNivelamento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Teste de nivelamento: a página, a correção no servidor (o gabarito não pode
 * vazar), o cálculo do nível CEFR e a gravação do resultado anônimo.
 */
class QuizTest extends TestCase
{
    use RefreshDatabase;

    public function test_configuracao_tem_12_perguntas_validas_por_idioma(): void
    {
        foreach (array_keys(config('quiz.idiomas')) as $slug) {
            $perguntas = QuizNivelamento::perguntas($slug);

            $this->assertCount(QuizNivelamento::total(), $perguntas, "Idioma {$slug}");

            foreach ($perguntas as $i => $p) {
                $this->assertCount(4, $p['alternativas'], "{$slug} #{$i}");
                $this->assertArrayHasKey($p['correta'], $p['alternativas'], "{$slug} #{$i}");
                $this->assertArrayHasKey($p['nivel'], config('quiz.niveis'), "{$slug} #{$i}");
            }
        }
    }

    public function test_faixas_de_pontuacao_cobrem_de_0_a_12(): void
    {
        $esperado = [0 => 'A1', 2 => 'A1', 3 => 'A2', 4 => 'A2', 5 => 'B1', 6 => 'B1',
                     7 => 'B2', 8 => 'B2', 9 => 'C1', 10 => 'C1', 11 => 'C2', 12 => 'C2'];

        foreach ($esperado as $acertos => $nivel) {
            $this->assertSame($nivel, QuizNivelamento::nivelPorPontuacao($acertos), "{$acertos} acertos");
        }
    }

    public function test_pagina_renderiza_idiomas_cursos_e_secao_cefr(): void
    {
        $html = $this->get(route('quiz'))->assertOk()->getContent();

        foreach (['ingles', 'portugues', 'italiano'] as $slug) {
            $this->assertStringContainsString('data-idioma="' . $slug . '"', $html);
        }

        $this->assertStringContainsString('id="quiz-cefr"', $html);       // alvo do botão do topo
        $this->assertStringContainsString('data-csrf="', $html);
        $this->assertSame(18, substr_count($html, 'class="quizCursoCard"')); // 3 idiomas x 6 níveis
        $this->assertStringContainsString('#contcts_container', $html);
    }

    public function test_perguntas_nao_expoem_o_gabarito(): void
    {
        $resposta = $this->getJson('/quiz/ingles/perguntas')->assertOk();

        $resposta->assertJsonCount(12, 'perguntas');
        $corpo = $resposta->getContent();

        $this->assertStringNotContainsString('"correta', $corpo);
        $this->assertStringNotContainsString('explicacao', $corpo);
    }

    public function test_idioma_desconhecido_retorna_404(): void
    {
        $this->getJson('/quiz/klingon/perguntas')->assertNotFound();
        $this->postJson('/quiz/klingon/finalizar')->assertNotFound();
    }

    public function test_responder_corrige_no_servidor_e_nao_deixa_trocar_a_resposta(): void
    {
        $certa = config('quiz_perguntas.ingles.0.correta');
        $errada = ($certa + 1) % 4;

        $this->postJson('/quiz/ingles/responder', ['pergunta' => 0, 'resposta' => $errada])
            ->assertOk()
            ->assertJson(['correta' => false, 'correta_indice' => $certa, 'acertos' => 0, 'respondidas' => 1]);

        // Tentar corrigir depois de ver o gabarito não muda nada.
        $this->postJson('/quiz/ingles/responder', ['pergunta' => 0, 'resposta' => $certa])
            ->assertOk()
            ->assertJson(['correta' => false, 'escolhida' => $errada, 'acertos' => 0, 'respondidas' => 1]);
    }

    public function test_responder_valida_os_dados(): void
    {
        $this->postJson('/quiz/ingles/responder', ['pergunta' => 99, 'resposta' => 0])->assertUnprocessable();
        $this->postJson('/quiz/ingles/responder', ['pergunta' => 0, 'resposta' => 7])->assertUnprocessable();
        $this->postJson('/quiz/ingles/responder', [])->assertUnprocessable();
    }

    public function test_nao_finaliza_com_perguntas_em_aberto(): void
    {
        $this->postJson('/quiz/ingles/responder', ['pergunta' => 0, 'resposta' => 0]);

        $this->postJson('/quiz/ingles/finalizar')->assertUnprocessable();
        $this->assertSame(0, QuizResultado::count());
    }

    public function test_fluxo_completo_gera_resultado_e_grava_uma_unica_vez(): void
    {
        // Acerta as 7 primeiras e erra o resto => 7 acertos => B2.
        foreach (config('quiz_perguntas.italiano') as $i => $p) {
            $resposta = $i < 7 ? $p['correta'] : ($p['correta'] + 1) % 4;
            $this->postJson('/quiz/italiano/responder', ['pergunta' => $i, 'resposta' => $resposta])->assertOk();
        }

        $this->postJson('/quiz/italiano/finalizar')
            ->assertOk()
            ->assertJson([
                'idioma'    => 'italiano',
                'pontuacao' => 7,
                'total'     => 12,
                'nivel'     => 'B2',
                'curso'     => 'Italiano B2',
            ])
            ->assertJsonPath('cta_url', route('contato', ['curso' => 'Italiano B2']) . '#contcts_container');

        // Reenvio (duplo clique) devolve o mesmo resultado sem duplicar o registro.
        $this->postJson('/quiz/italiano/finalizar')->assertOk()->assertJson(['nivel' => 'B2']);

        $this->assertSame(1, QuizResultado::count());
        $this->assertDatabaseHas('tbl_quiz_resultados', [
            'idioma' => 'italiano', 'pontuacao' => 7, 'nivel_cefr' => 'B2', 'curso_recomendado' => 'Italiano B2',
        ]);
    }

    public function test_recarregar_a_pagina_retoma_o_progresso_e_refazer_zera(): void
    {
        $p = config('quiz_perguntas.portugues');
        $this->postJson('/quiz/portugues/responder', ['pergunta' => 0, 'resposta' => $p[0]['correta']]);
        $this->postJson('/quiz/portugues/responder', ['pergunta' => 1, 'resposta' => ($p[1]['correta'] + 1) % 4]);

        $dados = $this->getJson('/quiz/portugues/perguntas')->assertOk()->json();
        $this->assertSame([0, 1], array_map('intval', array_keys($dados['respondidas'])));
        $this->assertTrue($dados['respondidas'][0]['correta']);
        $this->assertFalse($dados['respondidas'][1]['correta']);

        $this->deleteJson('/quiz/portugues/respostas')->assertOk();

        $this->assertCount(0, $this->getJson('/quiz/portugues/perguntas')->json('respondidas'));
    }

    public function test_progressos_de_idiomas_diferentes_sao_independentes(): void
    {
        $this->postJson('/quiz/ingles/responder', ['pergunta' => 0, 'resposta' => 0]);

        $this->assertCount(0, $this->getJson('/quiz/italiano/perguntas')->json('respondidas'));
    }

    public function test_contato_preenche_o_assunto_com_o_curso_da_url(): void
    {
        $html = $this->get(route('contato', ['curso' => 'Inglês B1']))->assertOk()->getContent();

        $this->assertStringContainsString('value="Interesse no curso Inglês B1"', $html);
    }

    public function test_contato_preenche_a_mensagem_com_a_descricao_do_curso(): void
    {
        $html = $this->get(route('contato', ['curso' => 'Inglês A1']))->assertOk()->getContent();

        $this->assertStringContainsString('Olá! Tenho interesse no curso Inglês A1 (Iniciante).', $html);
        $this->assertStringContainsString('Primeiros passos em Inglês: vocabulário essencial', $html);
    }

    public function test_contato_sem_curso_deixa_a_mensagem_vazia(): void
    {
        $html = $this->get(route('contato'))->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/<textarea name="mensagem"[^>]*><\/textarea>/', $html);
    }

    public function test_contato_nao_executa_html_vindo_do_curso(): void
    {
        $html = $this->get(route('contato', ['curso' => '<script>alert(1)</script>']))->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>alert(1)', $html);
    }
}
