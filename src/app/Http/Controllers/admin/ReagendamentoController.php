<?php

<<<<<<< HEAD
namespace App\Http\Controllers\admin;

use App\Models\Reagendamento;
use App\Models\Aula;           // seu model de tbl_aulas
use App\Notifications\ReagendamentoSolicitado;
use App\Notifications\AulaReagendada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReagendamentoController extends Controller
{
    // ================================================================
    // ALUNO — Envia solicitação de reagendamento
    // POST /reagendamento/solicitar
    // ================================================================
    public function solicitar(Request $request)
    {
        $request->validate([
            'id_aulas'       => 'required|exists:tbl_aulas,id_aulas',
            'motivo'         => 'required|string|max:100',
            'disponibilidade'=> 'nullable|string|max:500',
        ]);

        $aula  = Aula::findOrFail($request->id_aulas);
        $aluno = Auth::user();

        // Evita duplicata pendente para a mesma aula
        $jaPendente = Reagendamento::where('id_aulas', $aula->id_aulas)
            ->where('id_aluno', $aluno->id)
            ->where('status_reagendamento', 'pendente')
            ->exists();

        if ($jaPendente) {
            return response()->json([
                'success' => false,
                'message' => 'Já existe uma solicitação pendente para esta aula.',
            ], 422);
        }

        // Cria o reagendamento
        $reagendamento = Reagendamento::create([
            'id_aulas'             => $aula->id_aulas,
            'id_aluno'             => $aluno->id,
            'id_professor'         => $aula->id_professor,
            'motivo'               => $request->motivo,
            'disponibilidade'      => $request->disponibilidade,
            'status_reagendamento' => 'pendente',
        ]);

        // Notifica o professor (salva em notifications, aparece no dashboard)
        // O professor precisa usar o trait Notifiable no model User
        $professor = \App\Models\User::find($aula->id_professor);
        if ($professor) {
            $professor->notify(new ReagendamentoSolicitado($reagendamento, $aluno, $aula));
        }

        // Total de pendentes do aluno (para atualizar o card no dashboard)
        $totalPendentes = Reagendamento::where('id_aluno', $aluno->id)
            ->where('status_reagendamento', 'pendente')
            ->count();

        return response()->json([
            'success'         => true,
            'message'         => 'Solicitação enviada com sucesso.',
            'total_pendentes' => $totalPendentes,
        ]);
    }

    // ================================================================
    // PROFESSOR — Lista solicitações pendentes (carregado via AJAX)
    // GET /reagendamento/professor/listar
    // ================================================================
    public function listarParaProfessor()
    {
        $professor = Auth::user();

        $pendentes = Reagendamento::with(['aluno', 'aula'])
            ->where('id_professor', $professor->id)
            ->where('status_reagendamento', 'pendente')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'id_reagendamento' => $r->id_reagendamento,
                'aluno_nome'       => $r->aluno->name ?? '—',
                'titulo_aulas'     => $r->aula->titulo_aulas ?? '—',
                'cursos_aulas'     => $r->aula->cursos_aulas ?? '—',
                'data_aulas'       => $r->aula->data_aulas
                                        ? \Carbon\Carbon::parse($r->aula->data_aulas)->format('d/m/Y')
                                        : '—',
                'hora_aulas'       => $r->aula->hora_aulas
                                        ? \Carbon\Carbon::parse($r->aula->hora_aulas)->format('H:i')
                                        : '—',
                'motivo'           => $r->motivo,
                'disponibilidade'  => $r->disponibilidade,
                'criado_em'        => $r->created_at->diffForHumans(),
            ]);

        return response()->json(['pendentes' => $pendentes]);
    }

    // ================================================================
    // PROFESSOR — Confirma reagendamento com nova data e hora
    // POST /reagendamento/{id}/confirmar
    // ================================================================
    public function confirmar(Request $request, $id)
    {
        $request->validate([
            'nova_data_aulas' => 'required|date|after:today',
            'nova_hora_aulas' => 'required|date_format:H:i',
        ]);

        $reagendamento = Reagendamento::with(['aula', 'aluno'])->findOrFail($id);

        // Garante que o professor logado é o dono da aula
        if ($reagendamento->id_professor !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 403);
        }

        // Atualiza a aula com nova data e hora
        $reagendamento->aula->update([
            'data_aulas' => $request->nova_data_aulas,
            'hora_aulas' => $request->nova_hora_aulas . ':00',
        ]);

        // Atualiza o reagendamento
        $reagendamento->update([
            'status_reagendamento' => 'confirmado',
            'nova_data_aulas'      => $request->nova_data_aulas,
            'nova_hora_aulas'      => $request->nova_hora_aulas . ':00',
            'confirmado_em'        => now(),
        ]);

        // Notifica o aluno que a aula foi reagendada
        $reagendamento->aluno->notify(new AulaReagendada($reagendamento));

        return response()->json(['success' => true, 'message' => 'Aula reagendada com sucesso.']);
    }

    // ================================================================
    // PROFESSOR — Recusa solicitação
    // POST /reagendamento/{id}/recusar
    // ================================================================
=======
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reagendamento;
use App\Models\Aluno;
use App\Models\Aula;
use Illuminate\Http\Request;

class ReagendamentoController extends Controller
{
    /**
     * Exibe a listagem de reagendamentos.
     */
   /**
     * Exibe a listagem de reagendamentos.
     */
    public function index()
    {
        // 1. Zera o badge de notificação para o professor
        Reagendamento::where('notificado_professor', true)
            ->where('status', 'pendente')
            ->update(['notificado_professor' => false]);

        // 2. Busca os reagendamentos carregando os relacionamentos necessários
        $reagendamentos = Reagendamento::with(['aluno', 'aula'])->get();

        // 3. Busca alunos e aulas com base na estrutura real informada
        $alunos = Aluno::orderBy('nome_aluno', 'asc')->get();
        
        // ALTERAÇÃO AQUI: Mudamos de 'nome_aula' para 'titulo' para validar o nome correto da coluna
        $aulas = Aula::orderBy('titulo', 'asc')->get();

        // 4. Retorna a view correta injetando as variáveis corretas
        return view('admin.reagendamentos.index', compact('reagendamentos', 'alunos', 'aulas'));
    }

    /**
     * Aceitar/Aprovar uma solicitação de reagendamento.
     */
    public function aceitar($id)
    {
        $reagendamento = Reagendamento::findOrFail($id);

        $reagendamento->update([
            'status'           => 'aceito',
            'notificado_aluno' => false,
        ]);

        if ($reagendamento->aula && $reagendamento->data_sugerida) {
            $reagendamento->aula->update(['data_hora' => $reagendamento->data_sugerida]);
        }

        return redirect()->back()->with('success', 'Solicitação de reagendamento aceita com sucesso!');
    }

    /**
     * Recusar uma solicitação de reagendamento.
     */
>>>>>>> ee1760028d20d642e57486c416cf4089845a8c3d
    public function recusar($id)
    {
        $reagendamento = Reagendamento::findOrFail($id);

<<<<<<< HEAD
        if ($reagendamento->id_professor !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Não autorizado.'], 403);
        }

        $reagendamento->update([
            'status_reagendamento' => 'recusado',
            'recusado_em'          => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // ================================================================
    // ALUNO — Lista aulas reagendadas confirmadas
    // Usado para popular o card/partial no dashboard do aluno
    // GET /reagendamento/aluno/confirmados
    // ================================================================
    public function listarConfirmadosAluno()
    {
        $aluno = Auth::user();

        $confirmados = Reagendamento::with('aula')
            ->where('id_aluno', $aluno->id)
            ->where('status_reagendamento', 'confirmado')
            ->orderByDesc('confirmado_em')
            ->get()
            ->map(fn($r) => [
                'id_reagendamento' => $r->id_reagendamento,
                'titulo_aulas'     => $r->aula->titulo_aulas ?? '—',
                'cursos_aulas'     => $r->aula->cursos_aulas ?? '—',
                'nova_data_fmt'    => $r->nova_data_aulas
                                        ? \Carbon\Carbon::parse($r->nova_data_aulas)->format('d/m/Y')
                                        : '—',
                'nova_hora_fmt'    => $r->nova_hora_aulas
                                        ? \Carbon\Carbon::parse($r->nova_hora_aulas)->format('H:i')
                                        : '—',
                'confirmado_em'    => $r->confirmado_em
                                        ? $r->confirmado_em->format('d/m/Y H:i')
                                        : '—',
            ]);

        return response()->json(['confirmados' => $confirmados]);
    }
}

=======
        $reagendamento->update([
            'status'           => 'recusado',
            'notificado_aluno' => false,
        ]);

        return redirect()->back()->with('success', 'Solicitação de reagendamento recusada.');
    }

    /**
     * Retorna contagem de notificações para o badge do menu.
     */
    public function contarNotificacoes()
    {
        $count = Reagendamento::where('status', 'pendente')
            ->where('notificado_professor', true)
            ->count();

        return response()->json(['count' => $count]);
    }
}
>>>>>>> ee1760028d20d642e57486c416cf4089845a8c3d
