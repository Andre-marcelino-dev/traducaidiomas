<?php

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
    public function recusar($id)
    {
        $reagendamento = Reagendamento::findOrFail($id);

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