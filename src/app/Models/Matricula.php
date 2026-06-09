<?php

namespace App\Models;
use App\Models\Nivel;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $table = 'tbl_matricula';

    protected $primaryKey = 'id_matricula';

    public $timestamps = false;

    protected $fillable = [
        'id_aluno',
        'id_curso',
        'id_nivel',
        'data_matricula',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');

    }
}
