
<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Models\AtividadeAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AtividadeController extends Controller
{
    /**
     * Lista as atividades do curso do aluno.
     */
    public function index()
    {
        $aluno = auth('aluno')->user();

        if (!$aluno) {
            abort(401, 'Aluno não autenticado.');
        }

        $matricula = DB::table('tbl_matricula')
            ->where('id_aluno', $aluno->id_aluno)
            ->first();

        $idCurso = $matricula ? $matricula->id_curso : null;

        $atividades = Atividade::with([
            'respostas' => function ($q) use ($aluno) {
                $q->where('id_aluno', $aluno->id_aluno);
            },
        ])
            ->where('id_curso', $idCurso)
            ->where('status_atividade', 'ATIVA')
            ->orderBy('data_entrega')
            ->get();

        $pendentes = $atividades->filter(function ($atividade) {
            return $atividade->respostas->isEmpty()
                || $atividade->respostas->first()->status_resposta === 'PENDENTE';
        })->count();

        $concluidas = $atividades->filter(function ($atividade) {
            return $atividade->respostas->isNotEmpty()
                && in_array(
                    $atividade->respostas->first()->status_resposta,
                    ['ENVIADA', 'CORRIGIDA']
                );
        })->count();

        return view(
            'aluno.atividades.index',
            compact('atividades', 'aluno', 'pendentes', 'concluidas')
        );
    }

    /**
     * Exibe uma atividade e suas questões.
     */
    public function show($id)
    {
        $aluno = auth('aluno')->user();

        if (!$aluno) {
            abort(401, 'Aluno não autenticado.');
        }

        $atividade = Atividade::with('questoes')->findOrFail($id);

        $matriculado = DB::table('tbl_matricula')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('id_curso', $atividade->id_curso)
            ->where('status_matricula', 'ATIVO')
            ->exists();

        if (!$matriculado) {
            abort(403, 'Você não tem permissão para acessar esta atividade.');
        }

        $resposta = AtividadeResposta::with('respostasQuestoes')
            ->where('id_atividade', $id)
            ->where('id_aluno', $aluno->id_aluno)
            ->first();

        // Diagnóstico temporário: remova depois de verificar os dados.
        dd([
            'atividade' => $atividade->id_atividade,
            'titulo' => $atividade->titulo_atividade,
            'quantidade_questoes' => $atividade->questoes->count(),
            'questoes' => $atividade->questoes->pluck(
                'tipo_questao',
                'id_questao'
            ),
            'resposta' => $resposta?->status_resposta,
        ]);

        return view(
            'aluno.atividades.show',
            compact('atividade', 'aluno', 'resposta')
        );
    }

    /**
     * Salva as respostas em texto ou áudio.
     */
    public function responder(Request $request, $id)
    {
        $aluno = auth('aluno')->user();

        if (!$aluno) {
            abort(401, 'Aluno não autenticado.');
        }

        $atividade = Atividade::with('questoes')->findOrFail($id);

        // Confere se o aluno possui matrícula ativa no curso.
        $matriculado = DB::table('tbl_matricula')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('id_curso', $atividade->id_curso)
            ->where('status_matricula', 'ATIVO')
            ->exists();

        if (!$matriculado) {
            abort(403, 'Você não tem permissão para responder esta atividade.');
        }

        // Valida cada resposta conforme o tipo da questão.
        $regras = [];

        foreach ($atividade->questoes as $questao) {
            if ($questao->tipo_questao === 'audio') {
                $campo = 'audio_resposta_' . $questao->id_questao;

                $regras[$campo] = [
                    'required',
                    'file',
                    'mimes:mp3,wav,ogg,m4a,webm',
                    'max:20480',
                ];
            } else {
                $campo = 'questao_' . $questao->id_questao;

                $regras[$campo] = [
                    'required',
                    'string',
                ];
            }
        }

        $request->validate($regras, [
            'required' => 'Responda todas as questões antes de enviar.',
            'file' => 'O arquivo de áudio enviado é inválido.',
            'mimes' => 'O formato do áudio não é aceito.',
            'max' => 'O áudio não pode ultrapassar 20 MB.',
        ]);

        if ($atividade->questoes->isEmpty()) {
            return back()->with(
                'error',
                'Esta atividade ainda não possui questões cadastradas.'
            );
        }

        $resposta = AtividadeResposta::firstOrCreate(
            [
                'id_atividade' => $id,
                'id_aluno' => $aluno->id_aluno,
            ],
            [
                'status_resposta' => 'PENDENTE',
            ]
        );

        if ($resposta->status_resposta === 'CORRIGIDA') {
            return redirect()
                ->route('aluno.atividades.show', $id)
                ->with(
                    'error',
                    'Esta atividade já foi corrigida e não pode ser reenviada.'
                );
        }

        DB::beginTransaction();

        try {
            $resposta->update([
                'status_resposta' => 'ENVIADA',
                'data_envio' => now(),
            ]);

            // Remove as respostas anteriores antes de salvar novamente.
            $resposta->respostasQuestoes()->delete();

            foreach ($atividade->questoes as $questao) {
                $respostaAluno = null;
                $audioResposta = null;
                $correta = null;

                if ($questao->tipo_questao === 'audio') {
                    $campo = 'audio_resposta_' . $questao->id_questao;
                    $arquivo = $request->file($campo);

                    $audioResposta = $arquivo->store(
                        'respostas/audios',
                        'public'
                    );
                } else {
                    $campo = 'questao_' . $questao->id_questao;

                    $respostaAluno = $request->input($campo);

                    if ($questao->tipo_questao === 'multipla_escolha') {
                        $correta = (string) $respostaAluno
                            === (string) $questao->resposta_correta
                            ? 1
                            : 0;
                    }
                }

                AtividadeRespostaQuestao::create([
                    'id_resposta' => $resposta->id_resposta,
                    'id_questao' => $questao->id_questao,
                    'resposta_aluno' => $respostaAluno,
                    'audio_resposta' => $audioResposta,
                    'correta' => $correta,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }

        return redirect()
            ->route('aluno.atividades.index')
            ->with('success', 'Atividade enviada com sucesso!');
    }

    /**
     * Exibe um anexo da atividade, como PDF ou áudio.
     */
    public function anexoStream($anexoId)
    {
        $aluno = auth('aluno')->user();

        if (!$aluno) {
            abort(401, 'Aluno não autenticado.');
        }

        $anexo = AtividadeAnexo::with('atividade')->findOrFail($anexoId);

        $matriculado = DB::table('tbl_matricula')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('id_curso', $anexo->atividade->id_curso)
            ->where('status_matricula', 'ATIVO')
            ->exists();

        if (!$matriculado) {
            abort(403, 'Você não tem permissão para acessar este arquivo.');
        }

        if (
            !$anexo->arquivo
            || !Storage::disk('public')->exists($anexo->arquivo)
        ) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::disk('public')->response($anexo->arquivo);
    }

    /**
     * Faz o download de um anexo da atividade.
     */
    public function anexoDownload($anexoId)
    {
        $aluno = auth('aluno')->user();

        if (!$aluno) {
            abort(401, 'Aluno não autenticado.');
        }

        $anexo = AtividadeAnexo::with('atividade')->findOrFail($anexoId);

        $matriculado = DB::table('tbl_matricula')
            ->where('id_aluno', $aluno->id_aluno)
            ->where('id_curso', $anexo->atividade->id_curso)
            ->where('status_matricula', 'ATIVO')
            ->exists();

        if (!$matriculado) {
            abort(403, 'Você não tem permissão para acessar este arquivo.');
        }

        if (
            !$anexo->arquivo
            || !Storage::disk('public')->exists($anexo->arquivo)
        ) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::disk('public')->download(
            $anexo->arquivo,
            $anexo->nome_arquivo
        );
    }
}
