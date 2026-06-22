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

        return view('admin.agendas.index', [
            'agendas' => $agendas,
            'totalAgendamentos' => Agenda::count(),
            'agendamentosPendentes' => Agenda::where('status_agenda', 'pendente')->count(),
            'reagendamentos' => Agenda::where('status_agenda', 'reagendado')->count(),
            'agendamentosHoje' => Agenda::whereDate('data_evento_agenda', now())->count(),
        ]);
    }

    public function create()
    {
        return view('admin.agendas.model.create', [
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
            'descricao_agenda' => 'required|string',
            'data_evento_agenda' => 'required|date',
            'hora_inicio_agenda' => 'required',
            'hora_fim_agenda' => 'required',
            'status_agenda' => 'required',
            'link_aula_agenda' => 'nullable|string',
        ]);

        Agenda::create($data);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);

        return view('admin.agendas.model.edit', [
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
            'descricao_agenda' => 'required|string',
            'data_evento_agenda' => 'required|date',
            'hora_inicio_agenda' => 'required',
            'hora_fim_agenda' => 'required',
            'status_agenda' => 'required',
            'link_aula_agenda' => 'nullable|string',
        ]);

        $agenda->update($data);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Agenda::findOrFail($id)->delete();

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agendamento removido com sucesso!');
    }

    /**
     * Retorna os agendamentos em formato JSON para o FullCalendar.
     */
    public function eventos()
    {
        $agendas = Agenda::with(['aluno', 'professor'])->get();

        $eventos = $agendas->map(function ($agenda) {
            $cores = [
                'pendente'   => '#f59e0b',
                'confirmado' => '#22c55e',
                'cancelado'  => '#ef4444',
                'reagendado' => '#6366f1',
            ];

            $status = strtolower($agenda->status_agenda ?? '');
            $cor = $cores[$status] ?? '#94a3b8';

            $data = \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('Y-m-d');
            $inicio = \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i:s');
            $fim = \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i:s');

            return [
                'id'              => $agenda->id_agenda,
                'title'           => ($agenda->aluno?->nome_aluno ?? 'Sem aluno') . ' - ' . $agenda->titulo_agenda,
                'start'           => $data . 'T' . $inicio,
                'end'             => $data . 'T' . $fim,
                'color'           => $cor,
                'extendedProps'   => [
                    'foto'       => $agenda->aluno?->foto_aluno,
                    'aluno'      => $agenda->aluno?->nome_aluno ?? '—',
                    'curso'      => $agenda->aluno?->curso_aluno ?? '—',
                    'nivel'      => $agenda->aluno?->nivel_aluno ?? '—',
                    'professor'  => $agenda->professor?->nome_professor ?? '—',
                    'titulo'     => $agenda->titulo_agenda,
                    'data'       => \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y'),
                    'inicio'     => \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i'),
                    'fim'        => \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i'),
                    'status'     => $agenda->status_agenda ?? '—',
                    'editUrl'    => route('admin.agendas.edit', $agenda->id_agenda),
                ],
            ];
        });

        return response()->json($eventos);
    }
}
