<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria; // Importa a model nova


class ServicosController extends Controller
{
public function servicos($id = null)
{
    // Se o usuário clicou em uma categoria específica no menu
    if ($id) {
        // Filtra os serviços que pertencem a essa categoria
        $servicos = Categoria::where('id_servico', $id)->get();
        $servicoSelecionado = Categoria::find($id);
    } else {
        // Se não clicou em nada, lista tudo
        $servicos = Categoria::all();
        $servicoSelecionado = null;
    }

    return view('site.servicos.servico', compact('servicos', 'servicoSelecionado'));
}
}
