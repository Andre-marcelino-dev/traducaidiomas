<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class Aluno extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /** Únicos valores aceitos em status_aluno. */
    const STATUS = ['EM CURSO', 'CONCLUIDO', 'INATIVO'];

    /** Mensagem mostrada no site e no app quando o aluno inativo tenta entrar. */
    const MENSAGEM_INATIVO = 'Seu cadastro está inativo. Fale com a escola para reativar o acesso.';

    protected $table = 'tbl_alunos';
    protected $primaryKey = 'id_aluno';
    public $timestamps = false;

    protected $fillable = [
        'nome_aluno',
        'email_aluno',
        'senha_aluno',
        'telefone_aluno',
        'curso_aluno',
        'data_nasc_aluno',
        'nivel_aluno',
        'foto_aluno',
        'status_aluno',
    ];

    protected $hidden = [
        'senha_aluno',
    ];

    public function getAuthPassword()
    {
        return $this->senha_aluno;
    }

    /**
     * Aluno desativado pela escola não entra no site nem no app.
     * (CONCLUIDO continua entrando, para ver o histórico.)
     */
    public function estaInativo(): bool
    {
        return $this->status_aluno === 'INATIVO';
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_aluno', 'id_aluno');
    }

    /**
     * Exclui o aluno e tudo que aponta para ele (as FKs não têm cascade,
     * então sem isso o delete falhava com erro 500). Usado pelo painel e pela API.
     */
    public function excluirComDependencias(): void
    {
        $id = $this->id_aluno;

        DB::transaction(function () use ($id) {
            $respostaIds = AtividadeResposta::where('id_aluno', $id)->pluck('id_resposta');
            AtividadeRespostaQuestao::whereIn('id_resposta', $respostaIds)->delete();
            AtividadeResposta::where('id_aluno', $id)->delete();

            // Fórum: respostas do aluno e tópicos dele (com as respostas de outros e o anexo).
            ForumResposta::where('id_aluno', $id)->delete();
            foreach (ForumTopico::where('id_aluno', $id)->get() as $topico) {
                if ($topico->anexo_topico && file_exists(public_path($topico->anexo_topico))) {
                    @unlink(public_path($topico->anexo_topico));
                }
                $topico->respostas()->delete();
                $topico->delete();
            }

            Duvida::where('id_aluno', $id)->delete();
            Notificacao::where('id_aluno', $id)->delete();
            Reagendamento::where('aluno_id', $id)->delete();
            Agenda::where('id_aluno', $id)->delete();
            Matricula::where('id_aluno', $id)->delete();
            Feedback::where('id_aluno', $id)->delete();
            Presenca::where('id_aluno', $id)->delete();
            DB::table('tbl_presenca')->where('id_aluno', $id)->delete();
            DB::table('tbl_progresso_materiais')->where('id_aluno', $id)->delete();
            $this->tokens()->delete();

            $this->delete();
        });
    }
}
