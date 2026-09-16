<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'tbl_materiais';
    protected $primaryKey = 'id_materiais';
    const CREATED_AT = 'criado_em_materiais';
    const UPDATED_AT = 'atualizado_em_materiais';
    protected $guarded = [];
}
