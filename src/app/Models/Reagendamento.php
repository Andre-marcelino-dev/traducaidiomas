<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reagendamento extends Model
{
    protected $table = 'reagendamentos';

    protected $fillable = [
        'aluno_id',
        'aula_id',
        'professor_id',
        'data_original',
        'data_sugerida',
        'data_nova',
        'motivo',
        'resposta_professor',
        'status',
        'notificado_professor',
        'notificado_aluno',
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