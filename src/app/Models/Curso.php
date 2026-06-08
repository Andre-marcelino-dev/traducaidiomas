<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'tbl_cursos';

    protected $primaryKey = 'id_curso';

    public $timestamps = false;
}
