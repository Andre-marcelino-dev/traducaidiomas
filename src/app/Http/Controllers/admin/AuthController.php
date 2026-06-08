<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dash');
    }
    return view('admin.auth.login');
    }

    public function autenticar(Request $request)
    {
        $request->validate([
            'email_professor' => 'required|email',
            'senha_professor' => 'required',
        ]);

        $credenciais = [
            'email_professor' => $request->email_professor,
            'password'        => $request->senha_professor,
        ];

        if (Auth::guard('admin')->attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dash');
        }

        return back()->withInput()->with('error', 'Email ou senha inválidos');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function perfil()
    {
        $professor = auth('admin')->user();
        return view('admin.dash.perfil', compact('professor'));
    }
}