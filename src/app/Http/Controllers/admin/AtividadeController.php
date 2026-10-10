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
use App\Support\Notificar;
use App\Support\Upload;
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
            'arquivo_audio'      => 'nullable|file|mimes:' . implode(',', Upload::AUDIOS) . '|max:20480',
            'enunciado'          => 'required|array',
        ], [
            'arquivo_audio.mimes' => 'O áudio deve ser MP3, WAV, OGG, M4A, AAC ou WEBM.',
            'arquivo_audio.max'   => 'O áudio pode ter no máximo 20 MB.',
        ]);

        $arquivoAudio = null;
        if ($request->hasFile('arquivo_audio')) {
            $arquivoAudio = 'traducaidiomas/atividades/'
                . Upload::salvar($request->file('arquivo_audio'), 'atividades', Upload::AUDIOS, $request->titulo_atividade);
        }

        $atividade = Atividade::create([
            'id_professor'       => auth('admin')->id(),
            'id_curso'           => $request->id_curso,
            'id_modulo'          => $request->id_modulo,
            'titulo_atividade'   => $request->titulo_atividade,
            'descricao_atividade'=> $request->descricao_atividade,
            'arquivo_audio'      => $arquivoAudio,
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

        // Avisa no sino do app os alunos do curso (depois das questões criadas).
        Notificar::atividadeNova($atividade->fresh());

        return redirect()->route('admin.atividades.index')->with('success', 'Atividade criada com sucesso!');
    }

    public function show($id)
    {
        $atividade = Atividade::with(['questoes', 'respostas.aluno', 'respostas.respostasQuestoes.questao'])->findOrFail($id);
        return view('admin.atividades.show', compact('atividade'));
    }

    public function audio($id)
    {
        $atividade = Atividade::findOrFail($id);
        $caminho = public_path((string) $atividade->arquivo_audio);
        abort_unless($atividade->arquivo_audio && is_file($caminho), 404);

        return response()->file($caminho);
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

        if ($resposta->atividade) {
            Notificar::atividadeCorrigida(
                $resposta->atividade,
                (int) $resposta->id_aluno,
                $request->nota,
                (int) (auth('admin')->id() ?? $resposta->atividade->id_professor)
            );
        }

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

        if ($atividade->arquivo_audio && is_file(public_path($atividade->arquivo_audio))) {
            @unlink(public_path($atividade->arquivo_audio));
        }
        return redirect()->route('admin.atividades.index')->with('success', 'Atividade removida!');
    }
}
