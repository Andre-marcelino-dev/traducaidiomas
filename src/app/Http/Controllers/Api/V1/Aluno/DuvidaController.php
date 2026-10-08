<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Duvida;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Mesma regra do site (aluno/DuvidaController).
 */
class DuvidaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $duvidas = Duvida::where('id_aluno', (int) $request->user()->id_aluno)
            ->where('ativo', true)
            ->latest('criado_em')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $duvidas->map(fn (Duvida $d) => $this->formatar($d)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'assunto_duvida'  => 'required|string|max:150',
            'mensagem_duvida' => 'required|string|max:2000',
        ], [
            'assunto_duvida.required'  => 'Escreva o assunto da dúvida.',
            'mensagem_duvida.required' => 'Escreva sua dúvida.',
        ]);

        $duvida = Duvida::create([
            'id_aluno'        => (int) $request->user()->id_aluno,
            'assunto_duvida'  => $dados['assunto_duvida'],
            'mensagem_duvida' => $dados['mensagem_duvida'],
            'status_duvida'   => 'pendente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dúvida enviada ao professor!',
            'data' => $this->formatar($duvida),
        ]);
    }

    private function formatar(Duvida $d): array
    {
        return [
            'id_duvida'          => $d->id_duvida,
            'assunto'            => $d->assunto_duvida,
            'mensagem'           => $d->mensagem_duvida,
            'resposta_professor' => $d->resposta_professor,
            'status'             => $d->status_duvida,
            'criado_em'          => $d->criado_em ? $d->criado_em->toIso8601String() : null,
            'respondido_em'      => $d->respondido_em ? $d->respondido_em->toIso8601String() : null,
        ];
    }
}
