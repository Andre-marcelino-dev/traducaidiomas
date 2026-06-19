<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Reagendamento;
use Illuminate\Http\Request;

class ReagendamentoController extends Controller
{
    public function index()
    {
        $reagendamentos = Reagendamento::with(['aluno', 'aula', 'professor'])
            ->orderBy('created_at', 'desc')
            ->get();

        $alunos = Aluno::where('status_aluno', 'EM CURSO')
            ->orderBy('nome_aluno')
            ->get();

        $aulas = Aula::with('professor')
            ->orderBy('data_aulas', 'desc')
            ->get();

        return view('admin.reagendamentos.index', compact('reagendamentos', 'alunos', 'aulas'));
    }

    public function show(Reagendamento $reagendamento)
    {
        $reagendamento->load(['aluno', 'aula', 'professor']);

        return view('admin.reagendamentos.show', compact('reagendamento'));
    }

    public function alunos($id_aulas)
    {
        $aula = Aula::findOrFail($id_aulas);

        $alunos = Aluno::where('curso_aluno', $aula->cursos_aulas)
            ->where('status_aluno', 'EM CURSO')
            ->get();

        return view('admin.reagendamentos.alunos', compact('aula', 'alunos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:tbl_alunos,id_aluno',
            'aula_id' => 'required|exists:tbl_aulas,id_aulas',
            'motivo' => 'required|string|max:500',
        ]);

        $aula = Aula::findOrFail($request->aula_id);

        Reagendamento::create([
            'aluno_id' => $request->aluno_id,
            'aula_id' => $request->aula_id,
            'professor_id' => $aula->id_professor,
            'data_original' => $aula->data_aulas,
            'motivo' => $request->motivo,
            'status' => 'pendente',
            'notificado_professor' => false,
            'notificado_aluno' => false,
        ]);

        return redirect()
            ->route('admin.reagendamentos.index')
            ->with('success', 'Reagendamento solicitado com sucesso!');
    }

    public function aceitar(Request $request, Reagendamento $reagendamento)
    {
        $request->validate([
            'nova_data_aulas' => 'required|date',
            'nova_hora_aulas' => 'required',
        ]);

        $reagendamento->update([
            'status' => 'confirmado',
            'data_nova' => $request->nova_data_aulas . ' ' . $request->nova_hora_aulas,
            'notificado_aluno' => false,
        ]);

        return redirect()
            ->route('admin.reagendamentos.index')
            ->with('success', 'Reagendamento confirmado com sucesso!');
    }

    public function recusar(Request $request, Reagendamento $reagendamento)
    {
        $reagendamento->update([
            'status' => 'recusado',
            'resposta_professor' => $request->resposta_professor,
            'notificado_aluno' => false,
        ]);

        return redirect()
            ->route('admin.reagendamentos.index')
            ->with('success', 'Reagendamento recusado.');
    }

    public function destroy(Reagendamento $reagendamento)
    {
        $reagendamento->delete();

        return redirect()
            ->route('admin.reagendamentos.index')
            ->with('success', 'Reagendamento excluído com sucesso!');
    }

    public function contarNotificacoes()
    {
        $count = Reagendamento::where('status', 'pendente')
            ->where('notificado_professor', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}