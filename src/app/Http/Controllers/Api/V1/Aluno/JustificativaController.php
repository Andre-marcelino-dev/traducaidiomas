<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\JustificativaFalta;
use App\Models\Presenca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mesma regra do site (aluno/JustificativaController@store).
 */
class JustificativaController extends Controller
{
    public function store(Request $request, int $idPresenca): JsonResponse
    {
        $validado = $request->validate([
            'motivo_justificativa' => 'required|string|min:10|max:1000',
        ], [
            'motivo_justificativa.required' => 'Escreva o motivo da falta.',
            'motivo_justificativa.min'      => 'O motivo deve ter ao menos 10 caracteres.',
            'motivo_justificativa.max'      => 'O motivo pode ter no máximo 1000 caracteres.',
        ]);

        $presenca = Presenca::where('id_aluno', (int) $request->user()->id_aluno)->find($idPresenca);

        if (!$presenca) {
            return response()->json(['success' => false, 'message' => 'Presença não encontrada.'], Response::HTTP_NOT_FOUND);
        }

        if (!$presenca->ehFalta()) {
            return response()->json([
                'success' => false,
                'message' => 'Só é possível justificar aulas marcadas como falta.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $emAnalise = JustificativaFalta::where('id_presenca', $presenca->id_presenca)->pendentes()->exists();
        if ($emAnalise) {
            return response()->json([
                'success' => false,
                'message' => 'Essa falta já tem uma justificativa em análise pelo professor.',
            ], Response::HTTP_CONFLICT);
        }

        JustificativaFalta::create([
            'id_presenca'          => $presenca->id_presenca,
            'motivo_justificativa' => $validado['motivo_justificativa'],
            'status_justificativa' => 'pendente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Justificativa enviada! O professor vai analisar e você verá a resposta aqui.',
        ]);
    }
}
