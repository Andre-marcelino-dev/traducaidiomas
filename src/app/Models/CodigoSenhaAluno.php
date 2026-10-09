<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodigoSenhaAluno extends Model
{
    protected $table = 'tbl_codigo_senha_aluno';
    protected $primaryKey = 'id_codigo';
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = ['email_aluno', 'codigo', 'expira_em', 'usado'];

    protected $casts = [
        'expira_em' => 'datetime',
        'usado' => 'boolean',
    ];
}
