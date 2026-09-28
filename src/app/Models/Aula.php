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
        'id_modulo',
        'ordem_aula',
        'titulo_aulas',
        'descricao_aulas',
        'data_aulas',
        'hora_aulas',
        'duracao_minutos',
        'link_teams',
        'cursos_aulas',
        'status_aulas',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'id_professor', 'id_professor');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }

    public function presencas()
    {
        return $this->hasMany(Presenca::class, 'id_aulas', 'id_aulas');
    }
}