<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cabeçalhos de segurança em toda resposta (site, painel e API):
 * - X-Frame-Options: outro site não consegue abrir o nosso dentro de um iframe
 *   (golpe de "clickjacking"); o mapa do Contato continua funcionando, porque
 *   ali é o nosso site que abre o do Google.
 * - X-Content-Type-Options: o navegador não "adivinha" o tipo de um arquivo
 *   enviado (um anexo não vira página/script).
 * - Referrer-Policy: links para fora não levam o endereço completo da página.
 * - Tira o X-Powered-By, que anunciava a versão do PHP do servidor.
 */
class CabecalhosSeguranca
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        $response->headers->remove('X-Powered-By');
        if (!headers_sent()) {
            header_remove('X-Powered-By');
        }

        return $response;
    }
}
