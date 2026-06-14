<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Atividade extends Model
{
    protected $table = 'tbl_atividades';
    protected $primaryKey = 'id_atividade';
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;
    protected $fillable = [
        'id_professor', 'id_curso', 'titulo_atividade',
        'descricao_atividade', 'tipo_atividade', 'data_entrega', 'status_atividade'
    ];
    public function professor() { return $this->belongsTo(Professor::class, 'id_professor', 'id_professor'); }
    public function curso() { return $this->belongsTo(Curso::class, 'id_curso', 'id_curso'); }
    public function questoes() { return $this->hasMany(AtividadeQuestao::class, 'id_atividade', 'id_atividade'); }
    public function respostas() { return $this->hasMany(AtividadeResposta::class, 'id_atividade', 'id_atividade'); }
}
