<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Aluno extends Authenticatable
{
    protected $table = 'tbl_alunos';
    protected $primaryKey = 'id_aluno';
    public $timestamps = false;

    protected $fillable = [
        'nome_aluno',
        'email_aluno',
        'senha_aluno',
        'telefone_aluno',
        'curso_aluno',
        'data_nasc_aluno',
        'nivel_aluno',
        'foto_aluno',
        'status_aluno',
    ];

    protected $hidden = [
        'senha_aluno',
    ];

    public function getAuthPassword()
    {
        return $this->senha_aluno;
    }
}