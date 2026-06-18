<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    const TIMEOUT = 1800; // 30 minutos de inatividade

    public function handle(Request $request, Closure $next, string $guard = 'admin'): mixed
    {
        if (!Auth::guard($guard)->check()) {
            return $next($request);
        }

        $key = 'last_activity_' . $guard;
        $last = session($key);

        if ($last !== null && (time() - $last) > self::TIMEOUT) {
            Auth::guard($guard)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $route = $guard === 'aluno' ? 'aluno.login' : 'admin.login';
            return redirect()->route($route)->with('sessao_expirada', true);
        }

        session([$key => time()]);

        return $next($request);
    }
}
