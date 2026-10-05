<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeQuestao;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\Curso;
use App\Models\Aluno;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AtividadeController extends Controller
{
    public function index(Request $request)
    {
        $idCurso = $request->query('curso');
        $atividades = Atividade::with(['curso', 'professor', 'respostas'])
            ->when($idCurso, fn($q) => $q->where('id_curso', $idCurso))
            ->orderBy('criado_em', 'desc')->get();
        $cursos = Curso::orderBy('nome_curso')->get();
        return view('admin.atividades.index', compact('atividades', 'cursos', 'idCurso'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('nome_curso')->get();
        $modulos = Modulo::with(['curso', 'nivel'])->orderBy('id_curso')->orderBy('ordem_modulo')->get();
        return view('admin.atividades.create', compact('cursos', 'modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo_atividade'   => 'required',
            'id_curso'           => 'required',
            'id_modulo'          => 'nullable|exists:tbl_modulos,id_modulo',
            'data_entrega'       => 'required|date',
            'categoria_atividade'  => 'nullable|in:' . implode(',', array_keys(Atividade::CATEGORIAS)),
            'finalidade_atividade' => 'nullable|in:' . implode(',', array_keys(Atividade::FINALIDADES)),
            'enunciado'          => 'required|array',
        ]);

        $atividade = Atividade::create([
            'id_professor'       => auth('admin')->id(),
            'id_curso'           => $request->id_curso,
            'id_modulo'          => $request->id_modulo,
            'titulo_atividade'   => $request->titulo_atividade,
            'descricao_atividade'=> $request->descricao_atividade,
            'tipo_atividade'     => 'misto',
            'categoria_atividade'  => $request->categoria_atividade,
            'finalidade_atividade' => $request->finalidade_atividade ?: 'FIXACAO',
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
        $request->validate([
            'nota'               => 'required|numeric|min:0|max:10',
            'feedback_professor' => 'nullable|string|max:2000',
        ]);
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
        $atividade = Atividade::findOrFail($id);

        // As respostas dos alunos não têm cascade no banco: apaga antes (senão erro 500).
        DB::transaction(function () use ($atividade) {
            $respostaIds = $atividade->respostas()->pluck('id_resposta');
            AtividadeRespostaQuestao::whereIn('id_resposta', $respostaIds)->delete();
            AtividadeResposta::whereIn('id_resposta', $respostaIds)->delete();
            $atividade->questoes()->delete();
            $atividade->delete();
        });
        return redirect()->route('admin.atividades.index')->with('success', 'Atividade removida!');
    }
}
