<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * A página pública /api/documentacao precisa listar TODAS as rotas da API
 * (com o método certo) e os itens pedidos na entrega: nome, URL base,
 * versão, acesso público x autenticado e parâmetros.
 */
class DocumentacaoApiTest extends TestCase
{
    // O site compartilha o menu de serviços (banco) com todas as páginas.
    use RefreshDatabase;

    private function html(): string
    {
        return $this->get('/api/documentacao')->assertOk()->getContent();
    }

    public function test_todas_as_rotas_da_api_estao_documentadas(): void
    {
        $html = html_entity_decode($this->html());
        $faltando = [];

        foreach (Route::getRoutes()->getRoutes() as $rota) {
            if (!str_starts_with($rota->uri(), 'api/v1/')) {
                continue;
            }
            $metodo = collect($rota->methods())->reject(fn ($m) => $m === 'HEAD')->first();
            $caminho = '/' . $rota->uri(); // ex.: /api/v1/aluno/cursos/{idCurso}/modulos

            // Cartão da rota: <span class="metodo GET">GET</span> ... <code>/api/v1/...</code>
            $padrao = '#class="metodo ' . $metodo . '">' . $metodo . '</span>\s*<code>' . preg_quote($caminho, '#') . '</code>#';
            if (!preg_match($padrao, $html)) {
                $faltando[] = "{$metodo} {$caminho}";
            }
        }

        $this->assertSame([], $faltando, "Rotas sem documentação:\n" . implode("\n", $faltando));
    }

    public function test_pagina_tem_os_itens_pedidos_na_entrega(): void
    {
        $html = $this->html();

        $this->assertStringContainsString('API Traduca Idiomas', $html);           // nome e identificação
        $this->assertStringContainsString(url('/api/v1'), $html);                 // URL base
        $this->assertStringContainsString('/api/v1', $html);                      // versão
        $this->assertStringContainsString('selo-publica', $html);                 // rotas públicas
        $this->assertStringContainsString('selo-aluno', $html);                   // rotas autenticadas
        $this->assertStringContainsString('Authorization: Bearer', $html);        // como autenticar
        $this->assertStringContainsString('<th>Parâmetro</th>', $html);           // parâmetros
    }
}
