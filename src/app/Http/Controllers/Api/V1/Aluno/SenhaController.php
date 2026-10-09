<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\CodigoSenhaAluno;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * "Esqueci minha senha" pelo app: código de 6 dígitos por e-mail, igual ao
 * previsto pro site (ainda não existe lá também — é a primeira implementação).
 * Rotas públicas (sem token), porque o aluno está sem conseguir entrar.
 */
class SenhaController extends Controller
{
    private const MINUTOS_VALIDADE = 30;

    public function esqueci(Request $request): JsonResponse
    {
        $request->validate([
            'email_aluno' => 'required|email',
        ]);

        $aluno = Aluno::where('email_aluno', $request->email_aluno)->first();

        // Mesma mensagem exista ou não o e-mail, pra não revelar quem está cadastrado.
        if ($aluno) {
            $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            CodigoSenhaAluno::where('email_aluno', $aluno->email_aluno)
                ->where('usado', false)
                ->update(['usado' => true]);

            CodigoSenhaAluno::create([
                'email_aluno' => $aluno->email_aluno,
                'codigo' => $codigo,
                'expira_em' => now()->addMinutes(self::MINUTOS_VALIDADE),
            ]);

            Mail::raw(
                "Olá, {$aluno->nome_aluno}!\n\n" .
                "Seu código para redefinir a senha no app Traduca é: {$codigo}\n\n" .
                'Ele vale por ' . self::MINUTOS_VALIDADE . " minutos.\n\n" .
                'Se você não pediu essa redefinição, pode ignorar este e-mail.',
                fn ($message) => $message->to($aluno->email_aluno)
                    ->subject('Traduca Idiomas - Código para redefinir sua senha')
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Se esse e-mail estiver cadastrado, você vai receber um código em instantes.',
        ]);
    }

    public function redefinir(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'email_aluno' => 'required|email',
            'codigo' => 'required|string',
            'nova_senha' => 'required|string|min:6|confirmed',
        ], [
            'codigo.required' => 'Informe o código que você recebeu por e-mail.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 6 caracteres.',
            'nova_senha.confirmed' => 'A confirmação não confere com a nova senha.',
        ]);

        $registro = CodigoSenhaAluno::where('email_aluno', $dados['email_aluno'])
            ->where('codigo', $dados['codigo'])
            ->where('usado', false)
            ->where('expira_em', '>=', now())
            ->latest('id_codigo')
            ->first();

        if (!$registro) {
            throw ValidationException::withMessages(['codigo' => 'Código inválido ou expirado.']);
        }

        $aluno = Aluno::where('email_aluno', $dados['email_aluno'])->first();

        if (!$aluno) {
            throw ValidationException::withMessages(['email_aluno' => 'Aluno não encontrado.']);
        }

        $aluno->update(['senha_aluno' => Hash::make($dados['nova_senha'])]);
        $registro->update(['usado' => true]);

        // Derruba qualquer sessão/token antigo — o aluno estava sem acesso, então
        // não tem um "aparelho atual" pra manter conectado.
        $aluno->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Senha redefinida com sucesso! Faça login com a nova senha.',
        ]);
    }
}
