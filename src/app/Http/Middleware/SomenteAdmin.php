<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Painel: libera a rota só para professor marcado como administrador.
 * Usar depois de auth:admin.
 */
class SomenteAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->user()?->is_admin) {
            abort(403, 'Acesso restrito ao administrador.');
        }

        return $next($request);
    }
}
