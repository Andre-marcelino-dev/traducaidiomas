<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AtividadeRespostaQuestao extends Model
{
    protected $table = 'tbl_atividade_resposta_questoes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id_resposta', 'id_questao', 'resposta_aluno', 'correta'];

    /** Pasta (dentro de public/) onde ficam as gravações dos alunos. */
    const PASTA_GRAVACOES = 'traducaidiomas/atividades-respostas/';

    public function questao() { return $this->belongsTo(AtividadeQuestao::class, 'id_questao', 'id_questao'); }

    /**
     * Caminho completo da gravação, ou null.
     * resposta_aluno de questão de texto é o que o aluno digitou: só vale como
     * arquivo se a questão for de áudio E o nome for um arquivo direto da pasta
     * de gravações (sem "../"), senão um texto como "../.env" viraria download.
     */
    public function caminhoGravacao(): ?string
    {
        $nome = (string) $this->resposta_aluno;

        if ($this->questao?->tipo_questao !== 'audio'
            || !str_starts_with($nome, self::PASTA_GRAVACOES)
            || basename($nome) !== substr($nome, strlen(self::PASTA_GRAVACOES))) {
            return null;
        }

        $caminho = public_path($nome);

        return is_file($caminho) ? $caminho : null;
    }
}
