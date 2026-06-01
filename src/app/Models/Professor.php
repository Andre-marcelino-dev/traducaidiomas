<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table      = 'tbl_professor';
    protected $primaryKey = 'id_professor';

    const CREATED_AT = 'criado_em_professor';
    const UPDATED_AT = 'atualizado_em_professor';

    protected $fillable = [
        'nome_professor',
        'especialidade_professor',
        'experiencia_professor',
        'bio_professor',
        'foto_professor',
        'email_professor',
        'curso_professor',
        'nivel_professor',
        'telefone_professor',
        'senha_professor',
    ];

    protected $hidden = [
        'senha_professor',
    ];
}
