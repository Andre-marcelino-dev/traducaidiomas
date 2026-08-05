<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MensagemContato;

class MensagemContatoController extends Controller
{
    public function index()
    {
        $mensagens = MensagemContato::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.mensagens.index', compact('mensagens'));
    }

    public function show(MensagemContato $mensagem)
    {
        $mensagem->update(['lida' => true]);

        return view('admin.mensagens.show', compact('mensagem'));
    }

    public function destroy(MensagemContato $mensagem)
    {
        $mensagem->delete();

        return redirect()
            ->route('admin.mensagens.index')
            ->with('success', 'Mensagem excluída com sucesso!');
    }
}
