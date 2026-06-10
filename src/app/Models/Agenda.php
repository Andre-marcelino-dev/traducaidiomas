<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Aluno;
use App\Models\Professor;

class Agenda extends Model
{
    protected $table = 'tbl_agenda';
    protected $primaryKey = 'id_agenda';
    
    public $timestamps = true;

    const CREATED_AT = 'criado_em_agenda';
    const UPDATED_AT = 'atualizado_em_agenda';

    protected $fillable = [
        'id_aluno',
        'id_professor',
        'titulo_agenda',
        'descricao_agenda',
        'data_evento_agenda',
        'hora_inicio_agenda',
        'hora_fim_agenda',
        'status_agenda',
        'solicitacao_reagendamento',
        'link_aula_agenda',
    ];

    /**
     * Relacionamento com o Aluno
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno');
    }

    /**
     * Relacionamento com o Professor
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'id_professor', 'id_professor');
    }
}
