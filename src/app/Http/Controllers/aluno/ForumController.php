<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\ForumResposta;
use App\Models\ForumTopico;
use App\Models\Matricula;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $aluno = auth('aluno')->user();
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');

        $query = ForumTopico::with(['curso', 'aluno', 'respostas'])->whereIn('id_curso', $idCursos);

        if ($request->filled('id_curso')) {
            $query->where('id_curso', $request->id_curso);
        }
        if ($request->filled('busca')) {
            $query->where('titulo_topico', 'like', '%' . $request->busca . '%');
        }

        $topicos = $query->latest('criado_em')->paginate(12)->withQueryString();
        $cursos  = Curso::whereIn('id_curso', $idCursos)->get();

        return view('aluno.forum.index', compact('topicos', 'cursos'));
    }

    public function create()
    {
        $aluno = auth('aluno')->user();
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');
        $cursos = Curso::whereIn('id_curso', $idCursos)->get();

        return view('aluno.forum.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $aluno = auth('aluno')->user();
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');

        $dados = $request->validate([
            'id_curso'         => 'required|exists:tbl_cursos,id_curso',
            'titulo_topico'    => 'required|string|max:200',
            'descricao_topico' => 'required|string',
            'anexo_topico'     => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png|max:20480',
        ]);

        abort_unless($idCursos->contains((int) $dados['id_curso']), 403);

        if ($request->hasFile('anexo_topico')) {
            $arquivo     = $request->file('anexo_topico');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $destino     = public_path('traducaidiomas/forum');

            if (!file_exists($destino)) {
                mkdir($destino, 0755, true);
            }

            $arquivo->move($destino, $nomeArquivo);
            $dados['anexo_topico'] = 'traducaidiomas/forum/' . $nomeArquivo;
        }

        $dados['id_aluno'] = $aluno->id_aluno;
        $topico = ForumTopico::create($dados);

        return redirect()->route('aluno.forum.show', $topico->id_topico)
            ->with('success', 'Tópico criado com sucesso!');
    }

    public function show($id)
    {
        $aluno = auth('aluno')->user();
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');

        $topico = ForumTopico::with(['curso', 'aluno', 'respostas' => function ($q) {
            $q->with('aluno')->orderBy('criado_em');
        }])->findOrFail($id);

        abort_unless($idCursos->contains($topico->id_curso), 403);

        return view('aluno.forum.show', compact('topico'));
    }

    public function storeResposta(Request $request, $id)
    {
        $aluno = auth('aluno')->user();
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');

        $topico = ForumTopico::findOrFail($id);
        abort_unless($idCursos->contains($topico->id_curso), 403);

        $request->validate([
            'conteudo_resposta' => 'required|string|max:2000',
        ]);

        ForumResposta::create([
            'id_topico'         => $topico->id_topico,
            'id_aluno'          => $aluno->id_aluno,
            'conteudo_resposta' => $request->conteudo_resposta,
        ]);

        return redirect()->route('aluno.forum.show', $topico->id_topico)
            ->with('success', 'Resposta enviada!');
    }

    public function download($id)
    {
        $aluno = auth('aluno')->user();
        $idCursos = Matricula::where('id_aluno', $aluno->id_aluno)->pluck('id_curso');

        $topico = ForumTopico::findOrFail($id);
        abort_unless($idCursos->contains($topico->id_curso), 403);

        if ($topico->anexo_topico && file_exists(public_path($topico->anexo_topico))) {
            return response()->download(public_path($topico->anexo_topico));
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
    }
}
