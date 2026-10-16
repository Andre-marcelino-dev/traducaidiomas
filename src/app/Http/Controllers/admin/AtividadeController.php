<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeQuestao;
use App\Models\AtividadeResposta;
use App\Models\AtividadeAnexo;
use App\Models\Curso;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            'titulo_atividade'   => 'required|string|max:200',
            'id_curso'           => 'required|exists:tbl_cursos,id_curso',
            'data_entrega'       => 'required|date',
            'descricao_atividade'=> 'nullable|string',
            'enunciado'          => 'required|array',
            'anexo_pdf'          => 'nullable|file|mimes:pdf|max:10240',
            'anexo_audio'        => 'nullable|file|mimes:mp3,wav,m4a,ogg|max:10240',
        ]);

        DB::beginTransaction();
        try {
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

            $this->processarAnexos($request, $atividade);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.atividades.index')->with('success', 'Atividade criada com sucesso!');
    }

    public function show($id)
    {
        $atividade = Atividade::with(['questoes', 'anexos', 'respostas.aluno', 'respostas.respostasQuestoes.questao'])->findOrFail($id);
        return view('admin.atividades.show', compact('atividade'));
    }

    public function edit($id)
    {
        $atividade = Atividade::with('questoes', 'anexos')->findOrFail($id);
        $this->authorizeProfessor($atividade);
        $cursos = Curso::orderBy('nome_curso')->get();
        return view('admin.atividades.edit', compact('atividade', 'cursos'));
    }

    public function update(Request $request, $id)
    {
        $atividade = Atividade::with('questoes', 'anexos')->findOrFail($id);
        $this->authorizeProfessor($atividade);

        $request->validate([
            'titulo_atividade'   => 'required|string|max:200',
            'id_curso'           => 'required|exists:tbl_cursos,id_curso',
            'data_entrega'       => 'required|date',
            'descricao_atividade'=> 'nullable|string',
            'enunciado'          => 'required|array',
            'anexo_pdf'          => 'nullable|file|mimes:pdf|max:10240',
            'anexo_audio'        => 'nullable|file|mimes:mp3,wav,m4a,ogg|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $atividade->update([
                'id_curso'           => $request->id_curso,
                'titulo_atividade'   => $request->titulo_atividade,
                'descricao_atividade'=> $request->descricao_atividade,
                'data_entrega'       => $request->data_entrega,
            ]);

            if ($request->filled('remover_anexos')) {
                $this->removerAnexos($atividade, $request->remover_anexos);
            }

            $this->processarAnexos($request, $atividade);
            DB::commit();
        } catch (xception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('admin.atividades.show', $atividade->id_atividade)->with('success', 'Atividade atualizada com sucesso!');
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
        $atividade = Atividade::findOrFail($id);
        $this->authorizeProfessor($atividade);

        foreach ($atividade->anexos as $anexo) {
            if ($anexo->arquivo && Storage::disk('public')->exists($anexo->arquivo)) {
                Storage::disk('public')->delete($anexo->arquivo);
            }
        }

        $atividade->delete();
        return redirect()->route('admin.atividades.index')->with('success', 'Atividade removida!');
    }

    public function anexoDownload($anexoId)
    {
        $anexo = AtividadeAnexo::with('atividade')->findOrFail($anexoId);
        $atividade = $anexo->atividade;

        if (auth('admin')->check()) {
            return $this->servirAnexo($anexo);
        }

        if (auth('aluno')->check()) {
            $aluno = auth('aluno')->user();
            $matriculado = DB::table('tbl_matricula')
                ->where('id_aluno', $aluno->id_aluno)
                ->where('id_curso', $atividade->id_curso)
                ->where('status_matricula', 'ATIVO')
                ->exists();

            if (!$matriculado) {
                abort(403, 'Voce nao tem permissao para acessar este arquivo.');
            }

            return $this->servirAnexo($anexo);
        }

        abort(403, 'Nao autorizado.');
    }

    /**
     * Verifica se o professor logado e o dono da atividade.
     */
    private function authorizeProfessor(Atividade $atividade): void
    {
        if (auth('admin')->id() !== (int) $atividade->id_professor) {
            abort(403, 'Voce nao tem permissao para administrar esta atividade.');
        }
    }

    /**
     * Processa upload de PDF e audio.
     */
    private function processarAnexos(Request $request, Atividade $atividade): void
    {
        $tipos = [
            'anexo_pdf'   => 'pdf',
            'anexo_audio' => 'audio',
        ];

        foreach ($tipos as $field => $tipo) {
            if ($request->hasFile($field)) {
                $anexoExistente = $atividade->anexos()->where('tipo', $tipo)->first();
                if ($anexoExistente) {
                    if ($anexoExistente->arquivo && Storage::disk('public')->exists($anexoExistente->arquivo)) {
                        Storage::disk('public')->delete($anexoExistente->arquivo);
                    }
                    $anexoExistente->delete();
                }

                $file = $request->file($field);
                $nomeArquivo = $atividade->id_atividade . '_' . $tipo . '_' . time() . '.' . $file->getClientOriginalExtension();
                $caminho = $file->storeAs(
                    'atividades/' . $atividade->id_atividade . '/' . $tipo,
                    $nomeArquivo,
                    'public'
                );

                $duracao = null;
                if ($tipo === 'audio') {
                    $duracao = $this->obterDuracaoAudio($file);
                }

                AtividadeAnexo::create([
                    'id_atividade'  => $atividade->id_atividade,
                    'tipo'          => $tipo,
                    'nome_arquivo'  => $file->getClientOriginalName(),
                    'arquivo'       => $caminho,
                    'mime_type'     => $file->getMimeType(),
                    'tamanho'       => $file->getSize(),
                    'duracao'       => $duracao,
                ]);
            }
        }
    }

    /**
     * Remove anexos selecionados.
     */
    private function removerAnexos(Atividade $atividade, array $ids): void
    {
        $anexos = $atividade->anexos()->whereIn('id_anexo', $ids)->get();
        foreach ($anexos as $anexo) {
            if ($anexo->arquivo && Storage::disk('public')->exists($anexo->arquivo)) {
                Storage::disk('public')->delete($anexo->arquivo);
            }
            $anexo->delete();
        }
    }

    /**
     * Serve o arquivo para download ou streaming.
     */
    private function servirAnexo(AtividadeAnexo $anexo, $download = false)
    {
        if (!$anexo->arquivo || !Storage::disk('public')->exists($anexo->arquivo)) {
            abort(404, 'Arquivo nao encontrado.');
        }

        if ($download) {
            return Storage::disk('public')->download($anexo->arquivo, $anexo->nome_arquivo);
        }

        return Storage::disk('public')->response($anexo->arquivo);
    }

    /**
     * Tenta obter a duracao de audio em segundos.
     */
    private function obterDuracaoAudio($file): ?int
    {
        $caminhoTemp = $file->getRealPath();

        $output = [];
        $exitCode = 0;
        @exec('ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ' . escapeshellarg($caminhoTemp) . ' 2>&1', $output, $exitCode);

        if ($exitCode === 0 && !empty($output[0]) && is_numeric($output[0])) {
            return (int) round((float) $output[0]);
        }

        if (class_exists('getID3')) {
            try {
                $getID3 = new \getID3();
                $analise = $getID3->analyze($caminhoTemp);
                if (isset($analise['playtime_seconds']) && is_numeric($analise['playtime_seconds'])) {
                    return (int) round((float) $analise['playtime_seconds']);
                }
            } catch (\Exception $e) {
                // ignore
            }
        }

        return null;
    }
}
