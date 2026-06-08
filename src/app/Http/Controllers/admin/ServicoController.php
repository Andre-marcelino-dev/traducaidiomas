<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use App\Models\Professor;

use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::orderBy('ordenar_servico')->get();

       
        return view('admin.servicos.index', compact('servicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_servico' => 'required|string|max:100',
            'subtitulo_servico' => 'required|string|max:100',
            'lista_beneficios_servico' => 'required|string',
            'cta_titulo_servico' => 'required|string|max:100',
            'cta_texto_servico' => 'required|string|max:100',
            'link_whatsapp' => 'required|string|max:100',
            'classe_estilo_servico' => 'required|string|max:100',
            'lingua_servico' => 'required|string|max:100',
            'titulo_professor_servico' => 'required|string|max:100',
            'conteudo_servico' => 'required|string',
            'preco_servico' => 'required|numeric|min:0',
            'contato_text_servico' => 'required|string',
            'ordenar_servico' => 'required|integer',
            'imagem_servico' => 'required|string',
            'status_servico' => 'required|string|max:50'
        ]);

        Servico::create([
            'id_servico' => $request->id_servico,
            'id_professor' => $request->id_professor,
            'titulo_servico' => $request->titulo_servico,
            'subtitulo_servico' => $request->subtitulo_servico,
            'lista_beneficios_servico' => $request->lista_beneficios_servico,
            'cta_titulo_servico' => $request->cta_titulo_servico,
            'cta_texto_servico' => $request->cta_texto_servico,
            'link_whatsapp' => $request->link_whatsapp,
            'classe_estilo_servico' => $request->classe_estilo_servico,
            'lingua_servico' => $request->lingua_servico,
            'titulo_professor_servico' => $request->titulo_professor_servico,
            'conteudo_servico' => $request->conteudo_servico,
            'preco_servico' => $request->preco_servico,
            'contato_text_servico' => $request->contato_text_servico,
            'ordenar_servico' => $request->ordenar_servico,
            'imagem_servico' => $request->imagem_servico,
            'status_servico' => $request->status_servico
        ]);

        return redirect()
        ->route('admin.servicos.index')
        ->with('success', 'Serviço cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo_servico' => 'required|string|max:100',
            'subtitulo_servico' => 'required|string|max:100',
            'lista_beneficios_servico' => 'required|string',
            'cta_titulo_servico' => 'required|string|max:100',
            'cta_texto_servico' => 'required|string|max:100',
            'link_whatsapp' => 'required|string|max:100',
            'classe_estilo_servico' => 'required|string|max:100',
            'lingua_servico' => 'required|string|max:100',
            'titulo_professor_servico' => 'required|string|max:100',
            'conteudo_servico' => 'required|string',
            'preco_servico' => 'required|numeric|min:0',
            'contato_text_servico' => 'required|string',
            'ordenar_servico' => 'required|integer',
            'imagem_servico' => 'required|string',
            'status_servico' => 'required|string|max:50'
        ]);

        $servico = Servico::findOrFail($id);

        

        $servico->update([
            'id_servico' => $request->id_servico,
            'id_professor' => $request->id_professor,
            'titulo_servico' => $request->titulo_servico,
            'subtitulo_servico' => $request->subtitulo_servico,
            'lista_beneficios_servico' => $request->lista_beneficios_servico,
            'cta_titulo_servico' => $request->cta_titulo_servico,
            'cta_texto_servico' => $request->cta_texto_servico,
            'link_whatsapp' => $request->link_whatsapp,
            'classe_estilo_servico' => $request->classe_estilo_servico,
            'lingua_servico' => $request->lingua_servico,
            'titulo_professor_servico' => $request->titulo_professor_servico,
            'conteudo_servico' => $request->conteudo_servico,
            'preco_servico' => $request->preco_servico,
            'contato_text_servico' => $request->contato_text_servico,
            'ordenar_servico' => $request->ordenar_servico,
            'imagem_servico' => $request->imagem_servico,
            'status_servico' => $request->status_servico
        ]);

        return redirect()
        ->route('admin.servicos.index')
        ->with('success', 'Serviço atualizada com sucesso!');
    }

     public function destroy($id)
    {
        $servico = Servico::find($id);

        if (!$servico) {
            return redirect()->route('admin.servicos.index')
                ->with('error', 'Serviço não encontrado.');
        }

        if ($servico->imagem_servico && file_exists(public_path($servico->imagem_servico))) {
            @unlink(public_path($servico->imagem_servico));
        }

        $servico->delete();


        
        return redirect()->route('admin.servicos.index')
            ->with('success', 'Serviço removido com sucesso!');
    }
    
}
