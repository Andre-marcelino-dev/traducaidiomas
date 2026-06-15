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

    public function atualizarFoto(Request $request)
    {
        $request->validate(['foto_aluno' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);
        $aluno = auth('aluno')->user();
        $file = $request->file('foto_aluno');
        $nome = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('traducaidiomas/alunos/'), $nome);
        $aluno->update(['foto_aluno' => $nome]);
        return redirect()->route('aluno.perfil')->with('success', 'Foto atualizada com sucesso!');
    }

    public function atualizarEmail(Request $request)
    {
        $request->validate(['email_aluno' => 'required|email']);
        $aluno = auth('aluno')->user();
        $aluno->update(['email_aluno' => $request->email_aluno]);
        return redirect()->route('aluno.perfil')->with('success', 'Email atualizado com sucesso!');
    }

    public function atualizarSenha(Request $request)
    {
        $aluno = auth('aluno')->user();

        if ($request->modo === 'sem_senha') {
            $request->validate([
                'email_confirmacao' => 'required|email',
                'nova_senha'        => 'required|min:6|confirmed',
            ]);
            if ($request->email_confirmacao !== $aluno->email_aluno) {
                return back()->with('error', 'Email não confere com o cadastrado.');
            }
        } else {
            $request->validate([
                'senha_atual' => 'required',
                'nova_senha'  => 'required|min:6|confirmed',
            ]);
            if (!password_verify($request->senha_atual, $aluno->senha_aluno)) {
                return back()->with('error', 'Senha atual incorreta.');
            }
        }

        $aluno->update(['senha_aluno' => bcrypt($request->nova_senha)]);
        return redirect()->route('aluno.perfil')->with('success', 'Senha redefinida com sucesso!');
    }
}
