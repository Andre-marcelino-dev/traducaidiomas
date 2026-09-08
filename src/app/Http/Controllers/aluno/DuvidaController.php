<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Duvida;
use Illuminate\Http\Request;

class DuvidaController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();

        $duvidas = Duvida::where('id_aluno', $aluno->id_aluno)
            ->latest('criado_em')
            ->paginate(10);

        return view('aluno.duvidas.index', compact('duvidas'));
    }

    public function create()
    {
        return view('aluno.duvidas.create');
    }

    public function store(Request $request)
    {
        $aluno = auth('aluno')->user();

        $dados = $request->validate([
            'assunto_duvida'  => 'required|string|max:150',
            'mensagem_duvida' => 'required|string|max:2000',
        ]);

        Duvida::create([
            'id_aluno'        => $aluno->id_aluno,
            'assunto_duvida'  => $dados['assunto_duvida'],
            'mensagem_duvida' => $dados['mensagem_duvida'],
            'status_duvida'   => 'pendente',
        ]);

        return redirect()->route('aluno.duvidas.index')
            ->with('success', 'Dúvida enviada ao professor!');
    }
}
