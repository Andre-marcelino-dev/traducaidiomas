<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Duvida;
use Illuminate\Http\Request;

class DuvidaController extends Controller
{
    public function index(Request $request)
    {
        $query = Duvida::with('aluno');

        if ($request->filled('status_duvida')) {
            $query->where('status_duvida', $request->status_duvida);
        }

        $duvidas = $query->latest('criado_em')->paginate(15)->withQueryString();

        $totalDuvidas = Duvida::count();
        $totalPendentes = Duvida::where('status_duvida', 'pendente')->count();
        $totalRespondidas = Duvida::where('status_duvida', 'respondida')->count();

        return view('admin.duvidas.index', compact(
            'duvidas',
            'totalDuvidas',
            'totalPendentes',
            'totalRespondidas'
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

    public function destroy($id)
    {
        Duvida::findOrFail($id)->delete();

        return redirect()->route('admin.duvidas.index')
            ->with('success', 'Dúvida removida com sucesso!');
    }
}
