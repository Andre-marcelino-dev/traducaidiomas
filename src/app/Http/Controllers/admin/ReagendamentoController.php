<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reagendamento;
use App\Models\Aula;
use App\Models\Notificacao;
use App\Notifications\ReagendamentoSolicitado;
use App\Notifications\AulaReagendada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReagendamentoController extends Controller
{
    public function index()
    {
        $reagendamentos = Reagendamento::with(['aluno', 'aula'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $alunos = \App\Models\Aluno::orderBy('nome_aluno')->get();
        $aulas  = \App\Models\Aula::orderBy('titulo_aulas')->get();

        return view('admin.reagendamentos.index', compact('reagendamentos', 'alunos', 'aulas'));
    }

    public function show($id)
    {
        $reagendamento = Reagendamento::with(['aluno', 'aula'])->findOrFail($id);
        return view('admin.reagendamentos.show', compact('reagendamento'));
    }

    // ================================================================
    // ADMIN — Aceita um reagendamento com nova data e hora
    // PUT /admin/reagendamentos/{id}/aceitar
    // ================================================================
    public function aceitar(Request $request, $id)
    {
        $request->validate([
            'nova_data_aulas' => 'required|date|after:today',
            'nova_hora_aulas' => 'required',
        ]);

        $reagendamento = Reagendamento::with(['aluno', 'aula'])->findOrFail($id);

        // Atualiza a data e hora da aula
        $reagendamento->aula->update([
            'data_aulas' => $request->nova_data_aulas,
            'hora_aulas' => $request->nova_hora_aulas . ':00',
        ]);

        // Salva data_nova e confirma
        $reagendamento->update([
            'status'        => 'confirmado',
            'data_nova'     => $request->nova_data_aulas . ' ' . $request->nova_hora_aulas . ':00',
            'confirmado_em' => now(),
        ]);

        $reagendamento->aluno->notify(new AulaReagendada($reagendamento));

        Notificacao::create([
            'id_aluno'                  => $reagendamento->aluno_id,
            'id_professor'              => $reagendamento->professor_id,
            'mensagem_notificacoes'     => 'Sua aula foi reagendada: ' . ($reagendamento->aula->titulo_aulas ?? 'N/A') . ' — Nova data: ' . $request->nova_data_aulas . ' às ' . $request->nova_hora_aulas,
            'link_notificacoes'         => '/aluno',
            'lida_notificacoes'         => 0,
            'data_criacao_notificacoes' => now(),
            'id_materiais'              => null,
        ]);

        return redirect()->route('admin.reagendamentos.index')
            ->with('success', 'Reagendamento confirmado e aluno notificado!');
    }

    public function recusar(Request $request, $id)
    {
        $reagendamento = Reagendamento::findOrFail($id);
        $reagendamento->update(['status' => 'recusado']);

        return redirect()->route('admin.reagendamentos.index')
            ->with('error', 'Reagendamento recusado.');
    }

    public function contarNotificacoes()
    {
        $total = Reagendamento::where('status', 'pendente')->count();
        return response()->json(['total' => $total]);
    }

    public function solicitar(Request $request)
    {
        $request->validate([
            'id_aulas' => 'required|exists:tbl_aulas,id_aulas',
            'motivo'   => 'required|string|max:500',
        ]);

        $aula  = Aula::findOrFail($request->id_aulas);
        $aluno = Auth::user();

        $jaPendente = Reagendamento::where('aula_id', $aula->id_aulas)
            ->where('aluno_id', $aluno->id)
            ->where('status', 'pendente')
            ->exists();

        if ($jaPendente) {
            return response()->json([
                'success' => false,
                'message' => 'Já existe uma solicitação pendente para esta aula.',
            ], 422);
        }

        $reagendamento = Reagendamento::create([
            'aula_id'      => $aula->id_aulas,
            'aluno_id'     => $aluno->id,
            'professor_id' => $aula->id_professor,
            'motivo'       => $request->motivo,
            'status'       => 'pendente',
        ]);

        $professor = \App\Models\User::find($aula->id_professor);
        if ($professor) {
            $professor->notify(new ReagendamentoSolicitado($reagendamento, $aluno, $aula));
        }

        $totalPendentes = Reagendamento::where('aluno_id', $aluno->id)
            ->where('status', 'pendente')
            ->count();

        return response()->json([
            'success'         => true,
            'message'         => 'Solicitação enviada com sucesso.',
            'total_pendentes' => $totalPendentes,
        ]);
    }

    public function listarParaProfessor()
    {
        $professor = Auth::user();

        $pendentes = Reagendamento::with(['aluno', 'aula'])
            ->where('professor_id', $professor->id)
            ->where('status', 'pendente')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'id_reagendamento' => $r->id,
                'aluno_nome'       => $r->aluno->nome_aluno ?? '—',
                'titulo_aulas'     => $r->aula->titulo_aulas ?? '—',
                'cursos_aulas'     => $r->aula->cursos_aulas ?? '—',
                'data_aulas'       => $r->aula->data_aulas
                                        ? \Carbon\Carbon::parse($r->aula->data_aulas)->format('d/m/Y')
                                        : '—',
                'hora_aulas'       => $r->aula->hora_aulas
                                        ? \Carbon\Carbon::parse($r->aula->hora_aulas)->format('H:i')
                                        : '—',
                'motivo'           => $r->motivo,
                'criado_em'        => $r->created_at->diffForHumans(),
            ]);

        return response()->json(['pendentes' => $pendentes]);
    }

    public function confirmar(Request $request, $id)
    {
        $request->validate([
            'nova_data_aulas' => 'required|date|after:today',
            'nova_hora_aulas' => 'required',
        ]);

        $reagendamento = Reagendamento::with(['aula', 'aluno'])->findOrFail($id);

        if ($reagendamento->professor_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 403);
        }

        $reagendamento->aula->update([
            'data_aulas' => $request->nova_data_aulas,
            'hora_aulas' => $request->nova_hora_aulas . ':00',
        ]);

        $reagendamento->update([
            'status'        => 'confirmado',
            'data_nova'     => $request->nova_data_aulas . ' ' . $request->nova_hora_aulas . ':00',
            'confirmado_em' => now(),
        ]);

        $reagendamento->aluno->notify(new AulaReagendada($reagendamento));

        return response()->json(['success' => true, 'message' => 'Aula reagendada com sucesso.']);
    }

    public function destroy($id)
    {
        $reagendamento = Reagendamento::findOrFail($id);
        $reagendamento->delete();

        return redirect()->route('admin.reagendamentos.index')
            ->with('success', 'Reagendamento deletado com sucesso.');
    }
}
