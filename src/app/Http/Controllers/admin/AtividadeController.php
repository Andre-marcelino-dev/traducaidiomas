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
    /**
     * Lista as atividades.
     */
    public function index()
    {
        $atividades = Atividade::with([
            'curso',
            'respostas'
        ])
            ->orderBy('criado_em', 'desc')
            ->get();

        $cursos = Curso::orderBy('nome_curso')->get();

        return view(
            'admin.atividades.index',
            compact('atividades', 'cursos')
        );
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create()
    {
        $cursos = Curso::orderBy('nome_curso')->get();

        $modulos = Modulo::with([
            'curso',
            'nivel'
        ])
            ->orderBy('id_curso')
            ->orderBy('ordem_modulo')
            ->get();

        return view(
            'admin.atividades.create',
            compact('cursos', 'modulos')
        );
    }

    /**
     * Salva uma nova atividade.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'titulo_atividade' =>
            'required|string|max:255',

            'id_curso' =>
            'required',

            'id_modulo' =>
            'nullable|exists:tbl_modulos,id_modulo',

            'data_entrega' =>
            'required|date',

            /*
             * Estilo da atividade.
             *
             * Esse valor será utilizado posteriormente
             * pelo aplicativo do aluno para definir o
             * ícone e o visual da atividade.
             */
            'tipo_atividade' =>
            'required|in:conjugacao,conversa,pronuncia,leitura',

            /*
             * Áudio da atividade.
             *
             * É opcional porque nem toda atividade
             * precisa possuir áudio.
             */
            'audio' =>
            'nullable|file|mimes:mp3,wav,ogg,m4a,webm|max:20480',

            /*
             * Pelo menos uma questão precisa existir.
             */
            'enunciado' =>
            'required|array|min:1',

            'enunciado.*' =>
            'required|string',

            /*
             * Tipo de cada questão.
             */
            'tipo_questao' =>
            'nullable|array',

            'tipo_questao.*' =>
            'nullable|in:multipla_escolha,texto,audio',

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD DO ÁUDIO DA ATIVIDADE
        |--------------------------------------------------------------------------
        */

        $audio = null;

        if ($request->hasFile('audio')) {
            $audio = $request->file('audio')->store(
                'atividades/audios',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CRIA A ATIVIDADE
        |--------------------------------------------------------------------------
        */

        $atividade = Atividade::create([

            /*
             * Professor atualmente logado.
             */
            'id_professor' =>
            auth('admin')->id(),

            /*
             * Curso selecionado.
             */
            'id_curso' =>
            $request->id_curso,

            /*
             * Módulo selecionado.
             */
            'id_modulo' =>
            $request->id_modulo,

            /*
             * Informações da atividade.
             */
            'titulo_atividade' =>
            $request->titulo_atividade,

            'descricao_atividade' =>
            $request->descricao_atividade,

            /*
             * Estilo escolhido pelo professor.
             *
             * Exemplos:
             *
             * conjugacao
             * conversa
             * pronuncia
             * leitura
             */
            'tipo_atividade' =>
            $request->tipo_atividade,

            /*
             * Caminho do áudio salvo.
             */
            'audio' =>
            $audio,

            /*
             * Data de entrega.
             */
            'data_entrega' =>
            $request->data_entrega,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CRIA AS QUESTÕES
        |--------------------------------------------------------------------------
        */

        foreach ($request->enunciado as $i => $enunciado) {

            /*
             * Caso não venha o tipo da questão,
             * utiliza "texto" como padrão.
             */
            $tipo =
                $request->tipo_questao[$i]
                ?? 'texto';

            AtividadeQuestao::create([

                /*
                 * Relacionamento com a atividade.
                 */
                'id_atividade' =>
                $atividade->id_atividade,

                /*
                 * Enunciado.
                 */
                'enunciado' =>
                $enunciado,

                /*
                 * Tipo da questão:
                 *
                 * multipla_escolha
                 * texto
                 * audio
                 */
                'tipo_questao' =>
                $tipo,

                /*
                 * Alternativas.
                 *
                 * Para questões dissertativas
                 * e de áudio, esses campos
                 * ficarão null.
                 */
                'opcao_a' =>
                $request->opcao_a[$i]
                    ?? null,

                'opcao_b' =>
                $request->opcao_b[$i]
                    ?? null,

                'opcao_c' =>
                $request->opcao_c[$i]
                    ?? null,

                'opcao_d' =>
                $request->opcao_d[$i]
                    ?? null,

                /*
                 * Resposta correta.
                 */
                'resposta_correta' =>
                $request->resposta_correta[$i]
                    ?? null,

                /*
                 * Ordem da questão.
                 */
                'ordem' =>
                $i + 1,

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECIONAMENTO
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.atividades.index')
            ->with(
                'success',
                'Atividade criada com sucesso!'
            );
    }

    /**
     * Exibe uma atividade.
     */
    public function show($id)
    {
        $atividade = Atividade::with([
            'questoes',
            'respostas.aluno',
            'respostas.respostasQuestoes.questao'
        ])->findOrFail($id);

        return view(
            'admin.atividades.show',
            compact('atividade')
        );
    }

    /**
     * Corrige uma resposta de aluno.
     */
    public function corrigir(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | LOCALIZA A RESPOSTA
        |--------------------------------------------------------------------------
        */

        $resposta =
            AtividadeResposta::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'nota' =>
            'required|numeric|min:0|max:10',

            'feedback_professor' =>
            'nullable|string|max:2000',

        ]);

        /*
        |--------------------------------------------------------------------------
        | ATUALIZA A RESPOSTA
        |--------------------------------------------------------------------------
        */

        $resposta->update([

            'nota' =>
            $request->nota,

            'feedback_professor' =>
            $request->feedback_professor,

            'status_resposta' =>
            'CORRIGIDA',

            'data_correcao' =>
            now(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | RETORNA
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'success',
                'Atividade corrigida com sucesso!'
            );
    }

    /**
     * Remove uma atividade.
     */
    public function destroy($id)
    {
        /*
        |--------------------------------------------------------------------------
        | LOCALIZA A ATIVIDADE
        |--------------------------------------------------------------------------
        */

        $atividade =
            Atividade::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | EXCLUSÃO EM TRANSAÇÃO
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($atividade) {

            /*
             * Primeiro pegamos as respostas
             * relacionadas à atividade.
             */
            $respostaIds =
                $atividade
                ->respostas()
                ->pluck('id_resposta');

            /*
             * Remove as respostas das questões.
             */
            AtividadeRespostaQuestao::whereIn(
                'id_resposta',
                $respostaIds
            )->delete();

            /*
             * Remove as respostas dos alunos.
             */
            AtividadeResposta::whereIn(
                'id_resposta',
                $respostaIds
            )->delete();

            /*
             * Remove as questões da atividade.
             */
            $atividade
                ->questoes()
                ->delete();

            /*
             * Finalmente remove a atividade.
             */
            $atividade->delete();
        });

        /*
        |--------------------------------------------------------------------------
        | RETORNA
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.atividades.index')
            ->with(
                'success',
                'Atividade removida!'
            );
    }
}
