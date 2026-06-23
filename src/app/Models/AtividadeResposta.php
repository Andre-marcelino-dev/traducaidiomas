<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AtividadeResposta extends Model
{
    protected $table = 'tbl_atividade_respostas';
    protected $primaryKey = 'id_resposta';
    public $timestamps = false;
    protected $fillable = [
        'id_atividade', 'id_aluno', 'status_resposta',
        'nota', 'feedback_professor', 'data_envio', 'data_correcao'
    ];
    public function atividade() { return $this->belongsTo(Atividade::class, 'id_atividade', 'id_atividade'); }
    public function aluno() { return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno'); }
    public function respostasQuestoes() { return $this->hasMany(AtividadeRespostaQuestao::class, 'id_resposta', 'id_resposta'); }
}
