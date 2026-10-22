<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodigoSenhaProfessor extends Model
{
    protected $table = 'tbl_codigo_senha_professor';
    protected $primaryKey = 'id_codigo';
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = ['email_professor', 'codigo', 'expira_em', 'usado', 'tentativas'];

    protected $casts = [
        'expira_em' => 'datetime',
        'usado' => 'boolean',
    ];
}
