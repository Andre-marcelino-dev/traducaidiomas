<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AtividadeQuestao extends Model
{
    protected $table = 'tbl_atividade_questoes';
    protected $primaryKey = 'id_questao';
    public $timestamps = false;
    protected $fillable = [
        'id_atividade', 'enunciado', 'tipo_questao',
        'opcao_a', 'opcao_b', 'opcao_c', 'opcao_d', 'resposta_correta', 'ordem'
    ];
    public function atividade() { return $this->belongsTo(Atividade::class, 'id_atividade', 'id_atividade'); }
}
