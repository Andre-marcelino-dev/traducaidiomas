<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

/**
 * Rotas de manutenção do servidor (usadas sem terminal, pelo navegador).
 */
class SistemaRotasTest extends TestCase
{
    private function existe(string $uri): bool
    {
        return collect(Route::getRoutes()->getRoutes())->contains(fn ($r) => $r->uri() === $uri);
    }

    // Confere o "não encontrado" sem desenhar a página 404 do site (ela lê o
    // menu de serviços do banco, tabela que não existe no banco de teste).
    private function assertNaoEncontrado(string $url): void
    {
        try {
            $this->withoutExceptionHandling()->get($url);
            $this->fail("Esperava 'não encontrado' em {$url}");
        } catch (NotFoundHttpException) {
            $this->addToAssertionCount(1);
        }
    }

    public function test_rota_de_diagnostico_foi_removida(): void
    {
        // Era temporária (mostrava o fim do log e limpava o cache).
        $this->assertFalse($this->existe('sistema/diagnostico/{token}'));
        $this->assertNaoEncontrado('/sistema/diagnostico/qualquer-coisa');
    }

    public function test_rota_de_migrate_continua(): void
    {
        // É ela que atualiza o banco nas publicações pelo FileZilla.
        $this->assertTrue($this->existe('sistema/migrate/{token}'));
        $this->assertNaoEncontrado('/sistema/migrate/segredo-errado');
    }
}
