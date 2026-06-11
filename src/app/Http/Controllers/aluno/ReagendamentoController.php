<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Reagendamento;
use App\Models\Aula;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReagendamentoController extends Controller
{
    /**
     * Aluno solicita um reagendamento.
     */
public function solicitar(Request $request)
{
    $request->validate([
        'aula_id'       => 'required|exists:tbl_aulas,id_aulas',
        'motivo'        => 'required|string|min:10|max:500',
        'data_sugerida' => 'nullable|date|after:now',
    ], [
        'aula_id.required'    => 'Selecione uma aula.',
        'motivo.required'     => 'Informe o motivo do reagendamento.',
        'motivo.min'          => 'O motivo deve ter ao menos 10 caracteres.',
        'data_sugerida.after' => 'A data sugerida deve ser futura.',
    ]);

    $aluno = Auth::guard('aluno')->user();
    $aula  = Aula::findOrFail($request->aula_id);

    // Verifica se já existe solicitação pendente
    $jaExiste = Reagendamento::where('aluno_id', $aluno->id_aluno)
        ->where('aula_id', $aula->id_aulas)
        ->where('status', 'pendente')
        ->exists();

    if ($jaExiste) {
        return back()->with('error', 'Você já possui uma solicitação pendente para essa aula.');
    }

    Reagendamento::create([
        'aluno_id'             => $aluno->id_aluno,
        'aula_id'              => $aula->id_aulas,
        'professor_id'         => $aula->id_professor,
        'data_original'        => $aula->data_aulas . ' ' . $aula->hora_aulas,
        'data_sugerida'        => $request->data_sugerida,
        'motivo'               => $request->motivo,
        'status'               => 'pendente',
        'notificado_professor' => true,
        'notificado_aluno'     => false,
    ]);

    return back()->with(
        'success',
        'Solicitação de reagendamento enviada! Aguarde a confirmação do professor.'
    );
}
}
