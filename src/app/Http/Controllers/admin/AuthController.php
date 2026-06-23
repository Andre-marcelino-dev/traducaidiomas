<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function verificar()
    {
        return view('admin.auth.verificar');
    }

    public function verificarAcesso(Request $request)
    {
        $request->validate([
            'nome_professor'     => 'required|string',
            'telefone_professor' => 'required|string',
        ]);

        $professor = Professor::where('nome_professor', $request->nome_professor)
            ->where('telefone_professor', $request->telefone_professor)
            ->first();

        if (!$professor) {
            return back()->withInput()->with('error', 'Professor não encontrado. Verifique os dados informados.');
        }

        if ($professor->email_professor && $professor->senha_professor) {
            return redirect()->route('admin.login')
                ->with('info', 'Você já possui acesso cadastrado. Faça login com seu e-mail e senha.');
        }

        $request->session()->put('professor_primeiro_acesso', $professor->id_professor);
        return redirect()->route('admin.criar-credenciais');
    }

    public function criarCredenciais(Request $request)
    {
        $professorId = $request->session()->get('professor_primeiro_acesso');

        if (!$professorId) {
            return redirect()->route('admin.verificar')
                ->with('error', 'Primeiro verifique seus dados.');
        }

        $professor = Professor::find($professorId);

        if (!$professor) {
            return redirect()->route('admin.verificar')
                ->with('error', 'Professor não encontrado.');
        }

        return view('admin.auth.criar-credenciais', compact('professor'));
    }

    public function salvarCredenciais(Request $request)
    {
        $professorId = $request->session()->get('professor_primeiro_acesso');

        if (!$professorId) {
            return redirect()->route('admin.verificar')
                ->with('error', 'Sessão expirada. Verifique seus dados novamente.');
        }

        $professor = Professor::find($professorId);

        if (!$professor) {
            return redirect()->route('admin.verificar')
                ->with('error', 'Professor não encontrado.');
        }

        $request->validate([
            'email_professor'   => 'required|email|max:100|unique:tbl_professor,email_professor,' . $professor->id_professor . ',id_professor',
            'senha_professor'   => 'required|string|min:6|confirmed',
        ]);

        $professor->update([
            'email_professor'   => $request->email_professor,
            'senha_professor'   => Hash::make($request->senha_professor),
        ]);

        $request->session()->forget('professor_primeiro_acesso');

        return redirect()->route('admin.login')
            ->with('success', 'Credenciais criadas com sucesso! Faça login.');
    }

    public function recuperarSenha()
    {
        return view('admin.auth.recuperar-senha');
    }

    public function processarRecuperacao(Request $request)
    {
        $request->validate([
            'email_professor'    => 'required|email',
            'telefone_professor' => 'required|string',
        ]);

        $professor = Professor::where('email_professor', $request->email_professor)
            ->where('telefone_professor', $request->telefone_professor)
            ->first();

        if (!$professor) {
            return back()->withInput()->with('error', 'Dados não conferem. Verifique e-mail e telefone.');
        }

        $request->session()->put('professor_recuperar_senha', $professor->id_professor);
        return redirect()->route('admin.redefinir-senha');
    }

    public function redefinirSenha(Request $request)
    {
        $professorId = $request->session()->get('professor_recuperar_senha');

        if (!$professorId) {
            return redirect()->route('admin.recuperar-senha')
                ->with('error', 'Primeiro verifique seus dados.');
        }

        $professor = Professor::find($professorId);

        if (!$professor) {
            return redirect()->route('admin.recuperar-senha')
                ->with('error', 'Professor não encontrado.');
        }

        return view('admin.auth.redefinir-senha', compact('professor'));
    }

    public function salvarNovaSenha(Request $request)
    {
        $professorId = $request->session()->get('professor_recuperar_senha');

        if (!$professorId) {
            return redirect()->route('admin.recuperar-senha')
                ->with('error', 'Sessão expirada. Verifique seus dados novamente.');
        }

        $professor = Professor::find($professorId);

        if (!$professor) {
            return redirect()->route('admin.recuperar-senha')
                ->with('error', 'Professor não encontrado.');
        }

        $request->validate([
            'senha_professor' => 'required|string|min:6|confirmed',
        ]);

        $professor->update([
            'senha_professor' => Hash::make($request->senha_professor),
        ]);

        $request->session()->forget('professor_recuperar_senha');

        return redirect()->route('admin.login')
            ->with('success', 'Senha redefinida com sucesso! Faça login.');
    }
}