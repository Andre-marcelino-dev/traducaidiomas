<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // API responde 401 em JSON, mesmo se o app não mandar "Accept: application/json".
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        return $request->is('aluno', 'aluno/*')
            ? route('aluno.login')
            : route('admin.login');
    }
}
