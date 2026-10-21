<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\Reagendamento;
use App\Support\CursoAtual;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReagendamentoController extends Controller
{
    /** Quantos dias pra frente procurar horário livre do professor. */
    const DIAS_BUSCA_SUGESTAO = 30;
    const MAX_SUGESTOES = 3;

    /**
     * Aluno solicita o reagendamento de uma aula. Mesma regra do site
     * (aluno/ReagendamentoController@solicitar), adaptada pra não depender
     * da matrícula "atual" da sessão: aceita aula de qualquer curso
     * matriculado ativo do aluno.
     */
    public function solicitar(Request $request): JsonResponse
    {
        $validado = $request->validate([
            'aula_id'       => 'required|integer',
            'motivo'        => 'required|string|min:10|max:500',
            'data_sugerida' => 'nullable|date|after:now',
        ], [
            'aula_id.required'    => 'Selecione uma aula.',
            'motivo.required'     => 'Informe o motivo do reagendamento.',
            'motivo.min'          => 'O motivo deve ter ao menos 10 caracteres.',
            'data_sugerida.after' => 'A data sugerida deve ser futura.',
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
            'data_sugerida'        => $validado['data_sugerida'] ?? null,
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
     * Até 3 horários livres do professor dessa aula, pra oferecer como
     * sugestão ao aluno: mesmo horário da aula original, em dias onde o
     * professor não tem nenhuma outra aula marcada nesse mesmo horário.
     * Não existe um "horário de trabalho" cadastrado no sistema — a única
     * informação disponível é a agenda de aulas já marcadas.
     */
    public function sugestoes(Request $request, int $idAula): JsonResponse
    {
        $idAluno = (int) $request->user()->id_aluno;
        $aula = $this->aulaDoAluno($idAluno, $idAula);

        if (!$aula) {
            return response()->json(['success' => false, 'message' => 'Aula não encontrada.'], 404);
        }

        if (!$aula->hora_aulas) {
            return response()->json(['success' => true, 'data' => ['sugestoes' => []]]);
        }

        $duracao = (int) ($aula->duracao_minutos ?: 60);
        $horaOriginal = substr((string) $aula->hora_aulas, 0, 5);

        $outrasAulas = Aula::where('id_professor', $aula->id_professor)
            ->where('status_aulas', 'ATIVO')
            ->where('id_aulas', '!=', $aula->id_aulas)
            ->whereNotNull('data_aulas')
            ->whereNotNull('hora_aulas')
            ->whereDate('data_aulas', '>=', now()->toDateString())
            ->get(['data_aulas', 'hora_aulas', 'duracao_minutos'])
            ->map(function ($outra) {
                $inicio = Carbon::parse(substr((string) $outra->data_aulas, 0, 10) . ' ' . substr((string) $outra->hora_aulas, 0, 5));
                return ['inicio' => $inicio, 'fim' => $inicio->copy()->addMinutes((int) ($outra->duracao_minutos ?: 60))];
            });

        $sugestoes = [];
        $dia = now()->startOfDay()->addDay();

        for ($tentativa = 0; $tentativa < self::DIAS_BUSCA_SUGESTAO && count($sugestoes) < self::MAX_SUGESTOES; $tentativa++) {
            $inicioCandidato = Carbon::parse($dia->toDateString() . ' ' . $horaOriginal);
            $fimCandidato = $inicioCandidato->copy()->addMinutes($duracao);

            $temChoque = $outrasAulas->contains(
                fn ($outra) => $inicioCandidato->lt($outra['fim']) && $fimCandidato->gt($outra['inicio'])
            );

            if (!$temChoque) {
                $sugestoes[] = ['data' => $inicioCandidato->toDateString(), 'hora' => $inicioCandidato->format('H:i')];
            }

            $dia = $dia->copy()->addDay();
        }

        return response()->json(['success' => true, 'data' => ['sugestoes' => $sugestoes]]);
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
