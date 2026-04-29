<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria; // Importa a model nova

class ServicosController extends Controller
{
public function servicos($id = null)
{
    // Buscamos as categorias para o dropdown (caso não use o ViewComposer)
    $categorias = Categoria::all();

    // Se um ID foi passado, buscamos o serviço específico
    $servicoSelecionado = $id ? Categoria::find($id) : null;

    return view('site.servicos.servico', compact('categorias', 'servicoSelecionado'));
}
}
