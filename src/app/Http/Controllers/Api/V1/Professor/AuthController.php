<?php

namespace App\Http\Controllers\Api\V1\Professor;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email_professor' => 'required|email',
            'senha_professor' => 'required|string',
        ]);

        $professor = Professor::where('email_professor', $request->email_professor)->first();

        if (!$professor || !$professor->senha_professor || !Hash::check($request->senha_professor, $professor->senha_professor)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou senha inválidos.',
            ], 401);
        }

        $token = $professor->createToken('api-professor')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'token'     => $token,
                'professor' => $this->dados($professor),
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
        return response()->json([
            'success' => true,
            'data' => $this->dados($request->user()),
        ]);
    }

    private function dados(Professor $professor): array
    {
        return [
            'id_professor'    => $professor->id_professor,
            'nome_professor'  => $professor->nome_professor,
            'email_professor' => $professor->email_professor,
            'is_admin'        => (bool) $professor->is_admin,
        ];
    }
}
