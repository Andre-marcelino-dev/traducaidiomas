<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Aluno;
use App\Models\Professor;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::with(['aluno', 'professor'])
            ->orderBy('data_evento_agenda')
            ->get();

        return view('admin.agenda.index', [ // era admin.agendas.index
            'agendas' => $agendas,
            'totalAgendamentos' => Agenda::count(),
            'agendamentosPendentes' => Agenda::where('status_agenda', 'pendente')->count(),
            'reagendamentos' => Agenda::where('status_agenda', 'reagendado')->count(),
            'agendamentosHoje' => Agenda::whereDate('data_evento_agenda', now())->count(),
        ]);
    }

    public function create()
    {
        return view('admin.agenda.model.create', [ // era admin.agendas.create
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'professores' => Professor::orderBy('nome_professor')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_aluno' => 'required|integer',
            'id_professor' => 'required|integer',
            'titulo_agenda' => 'required|string',
            'descricao_agenda' => 'nullable|string',
            'data_evento_agenda' => 'required|date',
            'hora_inicio_agenda' => 'required',
            'hora_fim_agenda' => 'required',
            'status_agenda' => 'required',
            'link_aula_agenda' => 'nullable|string',
        ]);

        Agenda::create($data);

        return redirect()
            ->route('admin.agenda.index')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);

        return view('admin.agenda.model.edit', [ // ← faltou o .model.
            'agenda' => $agenda,
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'professores' => Professor::orderBy('nome_professor')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $agenda = Agenda::findOrFail($id);

        $data = $request->validate([
            'id_aluno' => 'required|integer',
            'id_professor' => 'required|integer',
            'titulo_agenda' => 'required|string',
            'descricao_agenda' => 'nullable|string',
            'data_evento_agenda' => 'required|date',
            'hora_inicio_agenda' => 'required',
            'hora_fim_agenda' => 'required',
            'status_agenda' => 'required',
            'link_aula_agenda' => 'nullable|string',
        ]);

        $agenda->update($data);

        return redirect()
            ->route('admin.agenda.index')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();

        return redirect()
            ->route('admin.agenda.index')
            ->with('success', 'Agendamento removido com sucesso!');
    }
}
