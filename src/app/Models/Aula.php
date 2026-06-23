<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $table = 'tbl_aulas';

    protected $primaryKey = 'id_aulas';

    public $timestamps = false;

    protected $fillable = [
        'id_professor',
        'id_curso',
        'titulo_aulas',
        'descricao_aulas',
        'data_aulas',
        'hora_aulas',
        'link_teams',
        'cursos_aulas',
        'status_aulas',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'id_professor', 'id_professor');
    }
}