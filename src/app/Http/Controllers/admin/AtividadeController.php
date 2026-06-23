<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeQuestao;
use App\Models\AtividadeResposta;
use App\Models\Curso;
use App\Models\Aluno;
use Illuminate\Http\Request;
class AtividadeController extends Controller
{
    public function index()
    {
        $atividades = Atividade::with(['curso', 'respostas'])->orderBy('criado_em', 'desc')->get();
        $cursos = Curso::orderBy('nome_curso')->get();
        return view('admin.atividades.index', compact('atividades', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('nome_curso')->get();
        return view('admin.atividades.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_atividade'   => 'required',
            'id_curso'           => 'required',
            'data_entrega'       => 'required|date',
            'enunciado'          => 'required|array',
        ]);

        $atividade = Atividade::create([
            'id_professor'       => auth('admin')->id(),
            'id_curso'           => $request->id_curso,
            'titulo_atividade'   => $request->titulo_atividade,
            'descricao_atividade'=> $request->descricao_atividade,
            'tipo_atividade'     => 'misto',
            'data_entrega'       => $request->data_entrega,
        ]);

        foreach ($request->enunciado as $i => $enunciado) {
            $tipo = $request->tipo_questao[$i] ?? 'texto';
            AtividadeQuestao::create([
                'id_atividade'    => $atividade->id_atividade,
                'enunciado'       => $enunciado,
                'tipo_questao'    => $tipo,
                'opcao_a'         => $request->opcao_a[$i] ?? null,
                'opcao_b'         => $request->opcao_b[$i] ?? null,
                'opcao_c'         => $request->opcao_c[$i] ?? null,
                'opcao_d'         => $request->opcao_d[$i] ?? null,
                'resposta_correta'=> $request->resposta_correta[$i] ?? null,
                'ordem'           => $i + 1,
            ]);
        }

        return redirect()->route('admin.atividades.index')->with('success', 'Atividade criada com sucesso!');
    }

    public function show($id)
    {
        $atividade = Atividade::with(['questoes', 'respostas.aluno', 'respostas.respostasQuestoes.questao'])->findOrFail($id);
        return view('admin.atividades.show', compact('atividade'));
    }

    public function corrigir(Request $request, $id)
    {
        $resposta = AtividadeResposta::findOrFail($id);
        $resposta->update([
            'nota'                => $request->nota,
            'feedback_professor'  => $request->feedback_professor,
            'status_resposta'     => 'CORRIGIDA',
            'data_correcao'       => now(),
        ]);
        return redirect()->back()->with('success', 'Atividade corrigida com sucesso!');
    }

    public function destroy($id)
    {
        Atividade::findOrFail($id)->delete();
        return redirect()->route('admin.atividades.index')->with('success', 'Atividade removida!');
    }
}
