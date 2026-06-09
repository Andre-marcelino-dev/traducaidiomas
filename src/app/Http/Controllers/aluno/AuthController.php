<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('aluno')->check()) {
            return redirect()->route('aluno.dash');
        }
        return view('aluno.auth.login');
    }

    public function autenticar(Request $request)
    {
        $request->validate([
            'email_aluno' => 'required|email',
            'senha_aluno' => 'required',
        ]);

        $credenciais = [
            'email_aluno' => $request->email_aluno,
            'password'    => $request->senha_aluno,
        ];

        if (Auth::guard('aluno')->attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect()->route('aluno.dash');
        }

        return back()->withInput()->with('error', 'Email ou senha inválidos');
    }

    public function logout(Request $request)
    {
        Auth::guard('aluno')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('aluno.login');
    }

    public function perfil()
    {
        $aluno = auth('aluno')->user();
        return view('aluno.dash.perfil', compact('aluno'));
    }
}