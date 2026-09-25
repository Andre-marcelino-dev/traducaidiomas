<?php

namespace App\Http\Middleware;

use App\Support\CursoAtual;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CursoSelecionado
{
    public function handle(Request $request, Closure $next): Response
    {
        $matricula = CursoAtual::matricula();

        if (!$matricula) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Selecione um curso.'], 409);
            }
            return redirect()->route('aluno.cursos.escolher');
        }

        View::share('cursoAtual', $matricula);

        return $next($request);
    }
}
