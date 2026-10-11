<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menu do topo do site: o item da página atual ganha a classe "ativo"
 * (sublinhado do header.css) e os outros não.
 */
class MenuAtivoTest extends TestCase
{
    use RefreshDatabase;

    private const ITENS = ['home', 'sobre', 'servicos', 'quiz', 'contato'];

    public function test_cada_pagina_destaca_so_o_proprio_item(): void
    {
        foreach (self::ITENS as $pagina) {
            $html = $this->get(route($pagina))->assertOk()->getContent();

            foreach (self::ITENS as $item) {
                $destacado = str_contains($html, 'href="' . route($item) . '" class="ativo"');
                $this->assertSame(
                    $item === $pagina,
                    $destacado,
                    "Na página {$pagina}, o item {$item} " . ($destacado ? 'não devia' : 'devia') . ' estar destacado.'
                );
            }
        }
    }
}
