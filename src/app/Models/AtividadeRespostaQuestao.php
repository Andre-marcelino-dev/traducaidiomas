<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AtividadeRespostaQuestao extends Model
{
    protected $table = 'tbl_atividade_resposta_questoes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id_resposta', 'id_questao', 'resposta_aluno', 'correta'];
    public function questao() { return $this->belongsTo(AtividadeQuestao::class, 'id_questao', 'id_questao'); }
}
