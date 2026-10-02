<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\Reagendamento;
use App\Support\CursoAtual;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReagendamentoController extends Controller
{
    /**
     * Aluno solicita o reagendamento de uma aula. Mesma regra do site
     * (aluno/ReagendamentoController@solicitar), adaptada pra não depender
     * da matrícula "atual" da sessão: aceita aula de qualquer curso
     * matriculado ativo do aluno.
     */
    public function solicitar(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'aula_id' => 'required|integer',
            'motivo'  => 'required|string|min:10|max:500',
        ], [
            'aula_id.required' => 'Selecione uma aula.',
            'motivo.required'  => 'Informe o motivo do reagendamento.',
            'motivo.min'       => 'O motivo deve ter ao menos 10 caracteres.',
        ]);

        $idAluno = (int) $request->user()->id_aluno;

        $aula = $this->aulaDoAluno($idAluno, (int) $validado['aula_id']);

        if (!$aula) {
            throw ValidationException::withMessages(['aula_id' => 'Aula não encontrada.']);
        }

        $jaExiste = Reagendamento::where('aluno_id', $idAluno)
            ->where('aula_id', $aula->id_aulas)
            ->where('status', 'pendente')
            ->exists();

        if ($jaExiste) {
            return response()->json([
                'success' => false,
                'message' => 'Você já possui uma solicitação pendente para essa aula.',
            ], 409);
        }

        Reagendamento::create([
            'aluno_id'             => $idAluno,
            'aula_id'              => $aula->id_aulas,
            'professor_id'         => $aula->id_professor,
            'data_original'        => $aula->data_aulas . ' ' . $aula->hora_aulas,
            'motivo'               => $validado['motivo'],
            'status'               => 'pendente',
            'notificado_professor' => false,
            'notificado_aluno'     => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Solicitação de reagendamento enviada! Aguarde a confirmação do professor.',
        ]);
    }

    /**
     * A aula, se pertencer a algum curso com matrícula ativa do aluno.
     */
    private function aulaDoAluno(int $idAluno, int $idAula): ?Aula
    {
        $matriculas = Matricula::where('id_aluno', $idAluno)
            ->where('status_matricula', 'ATIVO')
            ->get();

        foreach ($matriculas as $matricula) {
            $aula = CursoAtual::filtrar(Aula::query(), $matricula)->find($idAula);
            if ($aula) {
                return $aula;
            }
        }

        return null;
    }
}
