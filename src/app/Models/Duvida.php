<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duvida extends Model
{
    protected $table = 'tbl_duvidas';
    protected $primaryKey = 'id_duvida';
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_aluno',
        'assunto_duvida',
        'mensagem_duvida',
        'resposta_professor',
        'status_duvida',
        'respondido_em',
        'ativo',
    ];

    protected $casts = [
        'criado_em' => 'datetime',
        'respondido_em' => 'datetime',
        'ativo' => 'boolean',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno');
    }
}
