<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servico;  // ← essa linha precisa estar aqui

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::all();
        return view('admin.servicos.index', compact('servicos'));
    }

    public function create()
    {
        return view('admin.servicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_servico'          => 'required|string|max:100',
            'subtitulo_servico'       => 'required|string|max:100',
            'lista_beneficios_servico'=> 'required|string',
            'cta_titulo_servico'      => 'required|string|max:255',
            'cta_texto_servico'       => 'required|string|max:255',
            'link_whatsapp'           => 'required|string|max:255',
            'classe_estilo_servico'   => 'required|string|max:50',
            'lingua_servico'          => 'required|string|max:100',
            'titulo_professor_servico'=> 'required|string|max:255',
            'conteudo_servico'        => 'required|string',
            'preco_servico'           => 'required|string|max:100',
            'contato_text_servico'    => 'required|string|max:255',
            'ordenar_servico'         => 'required|integer',
            'imagem_servico'          => 'nullable|image|max:2048',
            'status_servico'         =>  'required|string|max:10',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagem_servico')) {
            $data['imagem_servico'] = $request->file('imagem_servico')->store('servicos', 'public');
        }

        Servico::create($data);

        return redirect()->route('admin.servicos.index')->with('success', 'Serviço criado com sucesso!');
    }

    public function edit($id)
    {
        $servico = Servico::findOrFail($id);
        return view('admin.servicos.edit', compact('servico'));
    }

  public function update(Request $request, $id)
{
    $servico = Servico::findOrFail($id);

    $request->validate([
        'titulo_servico'          => 'required|string|max:100',
        'subtitulo_servico'       => 'required|string|max:100',
        'lista_beneficios_servico'=> 'required|string',
        'cta_titulo_servico'      => 'required|string|max:255',
        'cta_texto_servico'       => 'required|string|max:255',
        'link_whatsapp'           => 'required|string|max:255',
        'classe_estilo_servico'   => 'required|string|max:50',
        'lingua_servico'          => 'required|string|max:100',
        'titulo_professor_servico'=> 'required|string|max:255',
        'conteudo_servico'        => 'required|string',
        'preco_servico'           => 'required|string|max:100',
        'contato_text_servico'    => 'required|string|max:255',
        'ordenar_servico'         => 'required|integer',
        'imagem_servico'          => 'nullable|image|max:2048',
        'status_servico'          => 'required|string|max:10',
    ]);

    // 👇 AQUI É A MUDANÇA PRINCIPAL
    $data = $request->except('imagem_servico');

    if ($request->hasFile('imagem_servico')) {
        $data['imagem_servico'] = $request->file('imagem_servico')->store('servicos', 'public');
    }

    $servico->update($data);

    return redirect()
        ->route('admin.servicos.index')
        ->with('success', 'Serviço atualizado com sucesso!');
}

    public function destroy($id)
    {
        $servico = Servico::findOrFail($id);
        $servico->delete();

        return redirect()->route('admin.servicos.index')->with('success', 'Serviço excluído com sucesso!');
    }
}