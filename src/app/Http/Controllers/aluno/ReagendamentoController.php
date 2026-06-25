<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Reagendamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReagendamentoController extends Controller
{
    /**
     * Lista as solicita??es do aluno logado.
     */
    public function meusSolicitados()
    {
        $aluno = Auth::guard('aluno')->user();

        $reagendamentos = Reagendamento::with(['aula', 'professor'])
            ->where('aluno_id', $aluno->id_aluno)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('aluno.reagendamentos.index', compact('aluno', 'reagendamentos'));
    }

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

        $jaExiste = Reagendamento::where('aluno_id', $aluno->id_aluno)
            ->where('aula_id', $aula->id_aulas)
            ->where('status', 'pendente')
            ->exists();

        if ($jaExiste) {
            return back()->with('error', 'Voc? j? possui uma solicita??o pendente para essa aula.');
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
            'Solicita??o de reagendamento enviada! Aguarde a confirma??o do professor.'
        );
    }

    public function contarNotificacoes()
    {
        $aluno = Auth::guard('aluno')->user();

        $count = Reagendamento::where('aluno_id', $aluno->id_aluno)
            ->where('notificado_aluno', false)
            ->whereIn('status', ['confirmado', 'recusado'])
            ->count();

        return response()->json(['count' => $count]);
    }
}