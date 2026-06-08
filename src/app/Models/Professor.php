<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Professor extends Authenticatable
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

    // Diz ao Laravel que o campo de senha se chama senha_professor
    public function getAuthPassword()
    {
        return $this->senha_professor;
    }
}