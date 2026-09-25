<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\ForumResposta;
use App\Models\ForumTopico;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumTopico::with(['curso', 'aluno', 'respostas']);

        if ($request->filled('id_curso')) {
            $query->where('id_curso', $request->id_curso);
        }

        $topicos = $query->latest('criado_em')->paginate(15);
        $cursos  = Curso::orderBy('nome_curso')->get();

        return view('admin.forum.index', compact('topicos', 'cursos'));
    }

    public function show($id)
    {
        $topico = ForumTopico::with(['curso', 'aluno', 'respostas.aluno'])->findOrFail($id);

        return view('admin.forum.show', compact('topico'));
    }

    public function destroyTopico($id)
    {
        $topico = ForumTopico::findOrFail($id);

        if ($topico->anexo_topico && file_exists(public_path($topico->anexo_topico))) {
            unlink(public_path($topico->anexo_topico));
        }

        $topico->respostas()->delete();
        $topico->delete();

        return redirect()->route('admin.forum.index')
            ->with('success', 'Tópico removido com sucesso!');
    }

    public function destroyResposta($id)
    {
        $resposta = ForumResposta::findOrFail($id);
        $idTopico = $resposta->id_topico;
        $resposta->delete();

        return redirect()->route('admin.forum.show', $idTopico)
            ->with('success', 'Resposta removida com sucesso!');
    }
}
