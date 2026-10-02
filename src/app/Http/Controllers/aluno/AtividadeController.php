<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\AtividadeRespostaQuestao;
use App\Support\CursoAtual;
use Illuminate\Http\Request;

class AtividadeController extends Controller
{
    /**
     * Lista as atividades disponíveis para o aluno.
     */
    public function index()
    {
        $aluno = auth('aluno')->user();

        $matriculaAtual = CursoAtual::matricula();

        $atividades = CursoAtual::filtrar(
            Atividade::with([
                'respostas' => function ($q) use ($aluno) {
                    $q->where('id_aluno', $aluno->id_aluno);
                }
            ]),
            $matriculaAtual
        )
            ->where('status_atividade', 'ATIVA')
            ->orderBy('data_entrega')
            ->get();

        $pendentes = $atividades
            ->filter(function ($atividade) {
                return $atividade->respostas->isEmpty()
                    || $atividade->respostas->first()->status_resposta === 'PENDENTE';
            })
            ->count();

        $concluidas = $atividades
            ->filter(function ($atividade) {
                return $atividade->respostas->isNotEmpty()
                    && in_array(
                        $atividade->respostas->first()->status_resposta,
                        ['ENVIADA', 'CORRIGIDA']
                    );
            })
            ->count();

        return view(
            'aluno.atividades.index',
            compact(
                'atividades',
                'aluno',
                'pendentes',
                'concluidas'
            )
        );
    }

    /**
     * Exibe uma atividade.
     */
    public function show($id)
    {
        $aluno = auth('aluno')->user();

        $atividade = $this->atividadeDoAluno($id);

        $resposta = AtividadeResposta::with('respostasQuestoes')
            ->where('id_atividade', $id)
            ->where('id_aluno', $aluno->id_aluno)
            ->first();

        return view(
            'aluno.atividades.show',
            compact(
                'atividade',
                'aluno',
                'resposta'
            )
        );
    }

    /**
     * Recebe as respostas do aluno.
     *
     * Questões:
     * - multipla_escolha -> resposta em texto
     * - texto            -> resposta em texto
     * - audio            -> arquivo de áudio gravado pelo aluno
     */
    public function responder(Request $request, $id)
    {
        $aluno = auth('aluno')->user();

        $atividade = $this->atividadeDoAluno($id);

        /*
        |--------------------------------------------------------------------------
        | Validação dinâmica das questões
        |--------------------------------------------------------------------------
        */

        $regras = [];

        foreach ($atividade->questoes as $questao) {

            if ($questao->tipo_questao === 'audio') {

                $regras[
                    'audio_resposta_' . $questao->id_questao
                ] = 'required|file|mimes:mp3,wav,ogg,m4a,webm|max:20480';

            } else {

                $regras[
                    'questao_' . $questao->id_questao
                ] = 'required|string';
            }
        }

        $request->validate(
            $regras,
            [
                'required' => 'Este campo é obrigatório.',

                'file' => 'O arquivo enviado é inválido.',

                'mimes' => 'O áudio precisa estar em um formato válido.',

                'max' => 'O arquivo de áudio não pode ultrapassar 20 MB.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Procura ou cria a resposta principal da atividade
        |--------------------------------------------------------------------------
        */

        $resposta = AtividadeResposta::firstOrCreate(
            [
                'id_atividade' => $atividade->id_atividade,
                'id_aluno' => $aluno->id_aluno,
            ],
            [
                'status_resposta' => 'PENDENTE',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Impede reenvio de atividade já corrigida
        |--------------------------------------------------------------------------
        */

        if ($resposta->status_resposta === 'CORRIGIDA') {

            return redirect()
                ->route(
                    'aluno.atividades.show',
                    $atividade->id_atividade
                )
                ->with(
                    'error',
                    'Esta atividade já foi corrigida e não pode ser reenviada.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Atualiza status da resposta
        |--------------------------------------------------------------------------
        */

        $resposta->update([
            'status_resposta' => 'ENVIADA',
            'data_envio' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Remove respostas anteriores
        |--------------------------------------------------------------------------
        |
        | Isso permite que uma atividade ainda não corrigida seja reenviada.
        |
        */

        $resposta->respostasQuestoes()->delete();

        /*
        |--------------------------------------------------------------------------
        | Salva cada questão
        |--------------------------------------------------------------------------
        */

        foreach ($atividade->questoes as $questao) {

            $respostaAluno = null;
            $audioResposta = null;
            $correta = null;

            /*
            |--------------------------------------------------------------------------
            | QUESTÃO DE ÁUDIO
            |--------------------------------------------------------------------------
            |
            | O aluno gravou o áudio pelo navegador.
            |
            | O Blade envia:
            |
            | audio_resposta_ID
            |
            */

            if ($questao->tipo_questao === 'audio') {

                $nomeCampo = 'audio_resposta_' . $questao->id_questao;

                $arquivo = $request->file($nomeCampo);

                if ($arquivo) {

                    $audioResposta = $arquivo->store(
                        'respostas/audios',
                        'public'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | QUESTÃO DE TEXTO OU MÚLTIPLA ESCOLHA
            |--------------------------------------------------------------------------
            */

            else {

                $respostaAluno = $request->input(
                    'questao_' . $questao->id_questao
                );

                /*
                |--------------------------------------------------------------------------
                | Corrige automaticamente múltipla escolha
                |--------------------------------------------------------------------------
                */

                if ($questao->tipo_questao === 'multipla_escolha') {

                    $correta = $respostaAluno === $questao->resposta_correta
                        ? 1
                        : 0;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Cria a resposta da questão
            |--------------------------------------------------------------------------
            */

            AtividadeRespostaQuestao::create([
                'id_resposta' => $resposta->id_resposta,

                'id_questao' => $questao->id_questao,

                'resposta_aluno' => $respostaAluno,

                'audio_resposta' => $audioResposta,

                'correta' => $correta,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Retorna para a lista de atividades
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('aluno.atividades.index')
            ->with(
                'success',
                'Atividade enviada com sucesso!'
            );
    }

    /**
     * Retorna somente atividades:
     *
     * - do curso atual do aluno;
     * - ativas;
     * - pertencentes à matrícula atual.
     */
    private function atividadeDoAluno($id): Atividade
    {
        return CursoAtual::filtrar(
            Atividade::with('questoes'),
            CursoAtual::matricula()
        )
            ->where('status_atividade', 'ATIVA')
            ->findOrFail($id);
    }
}