<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email_aluno' => 'required|email',
            'senha_aluno' => 'required|string',
        ]);

        $aluno = Aluno::where('email_aluno', $request->email_aluno)->first();

        if (!$aluno || !Hash::check($request->senha_aluno, $aluno->senha_aluno)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou senha inválidos.',
            ], 401);
        }

        $token = $aluno->createToken('app-mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'aluno' => [
                    'id_aluno'   => $aluno->id_aluno,
                    'nome_aluno' => $aluno->nome_aluno,
                    'email_aluno' => $aluno->email_aluno,
                    'foto_aluno' => $aluno->foto_aluno,
                ],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sessão encerrada.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $aluno = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id_aluno'    => $aluno->id_aluno,
                'nome_aluno'  => $aluno->nome_aluno,
                'email_aluno' => $aluno->email_aluno,
                'foto_aluno'  => $aluno->foto_aluno,
            ],
        ]);
    }
}
