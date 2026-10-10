<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sino do traduca-APP: avisos criados por App\Support\Notificar quando o
 * professor cadastra aula/atividade/material, corrige, responde dúvida etc.
 */
class NotificacaoController extends Controller
{
    /** Últimos 50 avisos do aluno + quantos não lidos (para o número no sino). */
    public function index(Request $request): JsonResponse
    {
        $idAluno = (int) $request->user()->id_aluno;

        $avisos = Notificacao::where('id_aluno', $idAluno)
            ->orderByDesc('data_criacao_notificacoes')
            ->orderByDesc('id_notificacoes')
            ->limit(50)
            ->get()
            ->map(fn (Notificacao $n) => [
                'id'       => $n->id_notificacoes,
                'mensagem' => $n->mensagem_notificacoes,
                'link'     => $n->link_notificacoes ?: null,
                'lida'     => (bool) $n->lida_notificacoes,
                'data'     => $n->data_criacao_notificacoes ? substr((string) $n->data_criacao_notificacoes, 0, 16) : null,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'nao_lidas'    => Notificacao::where('id_aluno', $idAluno)->where('lida_notificacoes', false)->count(),
                'notificacoes' => $avisos,
            ],
        ]);
    }

    public function marcarLida(Request $request, int $id): JsonResponse
    {
        $atualizadas = Notificacao::where('id_notificacoes', $id)
            ->where('id_aluno', (int) $request->user()->id_aluno)
            ->update(['lida_notificacoes' => true]);

        if (!$atualizadas && !Notificacao::where('id_notificacoes', $id)->where('id_aluno', (int) $request->user()->id_aluno)->exists()) {
            return response()->json(['success' => false, 'message' => 'Aviso não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['success' => true]);
    }

    public function marcarTodas(Request $request): JsonResponse
    {
        Notificacao::where('id_aluno', (int) $request->user()->id_aluno)
            ->where('lida_notificacoes', false)
            ->update(['lida_notificacoes' => true]);

        return response()->json(['success' => true]);
    }
}
