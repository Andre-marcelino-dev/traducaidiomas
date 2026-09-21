<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Duvida;
use Illuminate\Http\Request;

class DuvidaController extends Controller
{
    public function index(Request $request)
    {
        $filtroAtivo = $request->get('ativo', 'ativas');

        $query = Duvida::with('aluno');

        if ($filtroAtivo === 'ativas') {
            $query->where('ativo', true);
        } elseif ($filtroAtivo === 'desativadas') {
            $query->where('ativo', false);
        }

        if ($request->filled('status_duvida')) {
            $query->where('status_duvida', $request->status_duvida);
        }

        $duvidas = $query->latest('criado_em')->paginate(15)->withQueryString();

        $totalDuvidas = Duvida::where('ativo', true)->count();
        $totalPendentes = Duvida::where('ativo', true)->where('status_duvida', 'pendente')->count();
        $totalRespondidas = Duvida::where('ativo', true)->where('status_duvida', 'respondida')->count();

        return view('admin.duvidas.index', compact(
            'duvidas',
            'totalDuvidas',
            'totalPendentes',
            'totalRespondidas',
            'filtroAtivo'
        ));
    }

    public function show($id)
    {
        $duvida = Duvida::with('aluno')->findOrFail($id);

        return view('admin.duvidas.show', compact('duvida'));
    }

    public function responder(Request $request, $id)
    {
        $duvida = Duvida::findOrFail($id);

        $dados = $request->validate([
            'resposta_professor' => 'required|string|max:2000',
        ]);

        $duvida->update([
            'resposta_professor' => $dados['resposta_professor'],
            'status_duvida'      => 'respondida',
            'respondido_em'      => now(),
        ]);

        return redirect()->route('admin.duvidas.show', $duvida->id_duvida)
            ->with('success', 'Resposta enviada ao aluno!');
    }

    public function desativar($id)
    {
        $duvida = Duvida::findOrFail($id);
        $duvida->update(['ativo' => false]);

        return redirect()->back()
            ->with('success', 'Dúvida desativada com sucesso!');
    }

    public function ativar($id)
    {
        $duvida = Duvida::findOrFail($id);
        $duvida->update(['ativo' => true]);

        return redirect()->back()
            ->with('success', 'Dúvida ativada com sucesso!');
    }
}
