<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Professor extends Authenticatable
{
    use HasApiTokens;

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

    // is_admin fica de fora do $fillable de propósito: só muda via forceFill no ProfessorController.
    protected $hidden = [
        'senha_professor',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    // Diz ao Laravel que o campo de senha se chama senha_professor
    public function getAuthPassword()
    {
        return $this->senha_professor;
    }
}