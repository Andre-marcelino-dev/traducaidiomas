<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reagendamento extends Model
{
    protected $table = 'tbl_reagendamentos';

    protected $fillable = [
        'id_aluno',
        'id_aulas',
        'id_professor',
        'data_original_reagendamento',
        'data_sugerida_reagendamento',
        'data_nova_reagendamento',
        'motivo_reagendamento',
        'resposta_professor_reagendamento',
        'status_reagendamento',
        'notificado_professor_reagendamento',
        'notificado_aluno_reagendamento',
    ];

   public function aluno()
{
    // Adicionamos 'id_aluno' no final para o Laravel saber como vincular as tabelas
    return $this->belongsTo(Aluno::class, 'aluno_id', 'id_aluno');
}

public function aula()
{
    // O terceiro parâmetro indica a coluna de chave primária correta na tbl_aulas
    return $this->belongsTo(Aula::class, 'aula_id', 'id_aulas');
}
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }
}