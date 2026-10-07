<?php

namespace App\Http\Middleware;

use App\Models\Aluno;
use App\Models\Professor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * API: confere de quem é o token Sanctum. Usar depois de auth:sanctum.
 *
 *   token:aluno      -> só token de aluno
 *   token:professor  -> só token de professor
 *   token:admin      -> só token de professor administrador
 */
class TipoToken
{
    public function handle(Request $request, Closure $next, string $tipo): Response
    {
        $usuario = $request->user();

        $permitido = match ($tipo) {
            'aluno'     => $usuario instanceof Aluno,
            'professor' => $usuario instanceof Professor,
            'admin'     => $usuario instanceof Professor && $usuario->is_admin,
            default     => false,
        };

        if (!$permitido) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não permitido para este usuário.',
            ], 403);
        }

        // Aluno desativado depois de entrar: apaga as chaves dele e manda o app
        // de volta para o login (401 = o app limpa a sessão sozinho).
        if ($usuario instanceof Aluno && $usuario->estaInativo()) {
            $usuario->tokens()->delete();

            return response()->json([
                'success' => false,
                'message' => Aluno::MENSAGEM_INATIVO,
            ], 401);
        }

        return $next($request);
    }
}
