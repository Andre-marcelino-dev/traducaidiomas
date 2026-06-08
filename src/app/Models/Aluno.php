<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
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
}