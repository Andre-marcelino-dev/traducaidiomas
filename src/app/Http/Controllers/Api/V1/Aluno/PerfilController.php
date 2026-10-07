<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Support\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Tela Perfil e modal "Alterar senha" do traduca-APP. Mesma regra do site
 * (aluno/AuthController@atualizarEmail/atualizarFoto/atualizarSenha), mas
 * trocar e-mail ou senha pelo app sempre exige a senha atual.
 */
class PerfilController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        /** @var Aluno $aluno */
        $aluno = $request->user();

        $cursos = Matricula::with(['curso', 'nivel'])
            ->where('id_aluno', $aluno->id_aluno)
            ->where('status_matricula', 'ATIVO')
            ->get()
            ->map(fn (Matricula $m) => [
                'id_curso'   => $m->id_curso,
                'nome_curso' => $m->curso?->nome_curso,
                'nome_nivel' => $m->nivel?->nome_nivel,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => $this->dados($aluno) + [
                'telefone_aluno'  => $aluno->telefone_aluno,
                'data_nasc_aluno' => $aluno->data_nasc_aluno ? substr((string) $aluno->data_nasc_aluno, 0, 10) : null,
                'status_aluno'    => $aluno->status_aluno,
                'cursos'          => $cursos,
            ],
        ]);
    }

    /**
     * Corpo: email_aluno, senha_atual.
     */
    public function email(Request $request): JsonResponse
    {
        /** @var Aluno $aluno */
        $aluno = $request->user();

        $request->validate([
            'email_aluno' => 'required|email|max:80|unique:tbl_alunos,email_aluno,' . $aluno->id_aluno . ',id_aluno',
            'senha_atual' => 'required|string',
        ], [
            'email_aluno.unique'   => 'Este e-mail já está em uso por outro cadastro.',
            'senha_atual.required' => 'Informe sua senha atual para trocar o e-mail.',
        ]);

        $this->conferirSenhaAtual($aluno, $request->senha_atual);

        $aluno->update(['email_aluno' => $request->email_aluno]);

        return response()->json([
            'success' => true,
            'message' => 'E-mail atualizado com sucesso!',
            'data'    => $this->dados($aluno),
        ]);
    }

    /**
     * Corpo: senha_atual, nova_senha, nova_senha_confirmation.
     * Desconecta os outros aparelhos do aluno (este continua logado).
     */
    public function senha(Request $request): JsonResponse
    {
        /** @var Aluno $aluno */
        $aluno = $request->user();

        $request->validate([
            'senha_atual' => 'required|string',
            'nova_senha'  => 'required|string|min:6|confirmed',
        ], [
            'nova_senha.min'       => 'A nova senha deve ter pelo menos 6 caracteres.',
            'nova_senha.confirmed' => 'A confirmação não confere com a nova senha.',
        ]);

        $this->conferirSenhaAtual($aluno, $request->senha_atual);

        $aluno->update(['senha_aluno' => Hash::make($request->nova_senha)]);

        $atual = $aluno->currentAccessToken();
        $aluno->tokens()->when($atual, fn ($q) => $q->where('id', '!=', $atual->id))->delete();

        return response()->json([
            'success' => true,
            'message' => 'Senha alterada com sucesso!',
        ]);
    }

    /**
     * Upload multipart: campo foto_aluno (jpg, png ou webp, até 2 MB).
     */
    public function foto(Request $request): JsonResponse
    {
        /** @var Aluno $aluno */
        $aluno = $request->user();

        $request->validate([
            'foto_aluno' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto_aluno.required' => 'Escolha uma foto.',
            'foto_aluno.image'    => 'O arquivo precisa ser uma imagem.',
            'foto_aluno.mimes'    => 'A foto deve ser JPG, PNG ou WEBP.',
            'foto_aluno.max'      => 'A foto pode ter no máximo 2 MB.',
        ]);

        $nome = Upload::salvar($request->file('foto_aluno'), 'alunos', Upload::IMAGENS, 'aluno');
        $aluno->update(['foto_aluno' => $nome]);

        return response()->json([
            'success' => true,
            'message' => 'Foto atualizada com sucesso!',
            'data'    => $this->dados($aluno),
        ]);
    }

    private function conferirSenhaAtual(Aluno $aluno, string $senha): void
    {
        if (!Hash::check($senha, $aluno->senha_aluno)) {
            throw ValidationException::withMessages(['senha_atual' => 'Senha atual incorreta.']);
        }
    }

    /** Mesmo formato do /aluno/me (o app guarda isso na sessão). */
    private function dados(Aluno $aluno): array
    {
        return [
            'id_aluno'    => $aluno->id_aluno,
            'nome_aluno'  => $aluno->nome_aluno,
            'email_aluno' => $aluno->email_aluno,
            'foto_aluno'  => $aluno->foto_aluno,
        ];
    }
}
