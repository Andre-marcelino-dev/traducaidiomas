<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'tbl_cursos';

    protected $primaryKey = 'id_curso';

    public $timestamps = false;

    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'id_curso', 'id_curso');
    }
}
