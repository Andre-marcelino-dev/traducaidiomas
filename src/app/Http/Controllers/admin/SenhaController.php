<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CodigoSenhaProfessor;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * "Esqueci minha senha" do painel (admin e professores): código de 6 dígitos
 * por e-mail, mesmo modelo do app do aluno (Api\V1\Aluno\SenhaController).
 * Substitui a antiga recuperação por telefone, que usava só dados públicos.
 */
class SenhaController extends Controller
{
    private const MINUTOS_VALIDADE = 30;
    private const MAX_TENTATIVAS = 5;

    public function esqueci()
    {
        return view('admin.auth.esqueci-senha');
    }

    public function enviarCodigo(Request $request)
    {
        $request->validate([
            'email_professor' => 'required|email',
        ], [
            'email_professor.required' => 'Informe o seu e-mail.',
            'email_professor.email'    => 'Digite um e-mail válido.',
        ]);

        $professor = Professor::where('email_professor', $request->email_professor)->first();

        // Mesma resposta exista ou não o e-mail, pra não revelar quem está cadastrado.
        if ($professor) {
            $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            CodigoSenhaProfessor::where('email_professor', $professor->email_professor)
                ->where('usado', false)
                ->update(['usado' => true]);

            CodigoSenhaProfessor::create([
                'email_professor' => $professor->email_professor,
                'codigo' => $codigo,
                'expira_em' => now()->addMinutes(self::MINUTOS_VALIDADE),
            ]);

            try {
                Mail::raw(
                    "Olá, {$professor->nome_professor}!\n\n" .
                    "Seu código para redefinir a senha do painel Traduca é: {$codigo}\n\n" .
                    'Ele vale por ' . self::MINUTOS_VALIDADE . " minutos.\n\n" .
                    'Se você não pediu essa redefinição, pode ignorar este e-mail.',
                    fn ($message) => $message->to($professor->email_professor)
                        ->subject('Traduca Idiomas - Código para redefinir sua senha')
                );
            } catch (\Throwable $e) {
                Log::error('Erro ao enviar código de senha (painel)', ['erro' => $e->getMessage()]);
            }
        }

        return redirect()->route('admin.senha.redefinir')
            ->with('email_senha', $request->email_professor)
            ->with('info', 'Se esse e-mail estiver cadastrado, você vai receber um código em instantes.');
    }

    public function redefinir()
    {
        return view('admin.auth.redefinir-senha');
    }

    public function salvar(Request $request)
    {
        $dados = $request->validate([
            'email_professor' => 'required|email',
            'codigo' => 'required|string',
            'nova_senha' => 'required|string|min:6|confirmed',
        ], [
            'email_professor.required' => 'Informe o seu e-mail.',
            'codigo.required' => 'Informe o código que você recebeu por e-mail.',
            'nova_senha.required' => 'Digite a nova senha.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 6 caracteres.',
            'nova_senha.confirmed' => 'A confirmação não confere com a nova senha.',
        ]);

        $registro = CodigoSenhaProfessor::where('email_professor', $dados['email_professor'])
            ->where('usado', false)
            ->where('expira_em', '>=', now())
            ->latest('id_codigo')
            ->first();

        if (!$registro || !hash_equals($registro->codigo, trim($dados['codigo']))) {
            // Conta o erro no código vigente; ao chegar no limite, ele deixa de valer.
            if ($registro) {
                $registro->increment('tentativas');
                if ($registro->tentativas >= self::MAX_TENTATIVAS) {
                    $registro->update(['usado' => true]);
                }
            }

            throw ValidationException::withMessages(['codigo' => 'Código inválido ou expirado.']);
        }

        $professor = Professor::where('email_professor', $dados['email_professor'])->first();

        if (!$professor) {
            throw ValidationException::withMessages(['codigo' => 'Código inválido ou expirado.']);
        }

        $professor->update(['senha_professor' => Hash::make($dados['nova_senha'])]);
        $registro->update(['usado' => true]);

        // Derruba tokens antigos (app do professor), já que a senha mudou.
        $professor->tokens()->delete();

        return redirect()->route('admin.login')
            ->with('success', 'Senha redefinida com sucesso! Entre com a nova senha.');
    }
}
