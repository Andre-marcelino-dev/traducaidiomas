<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'tbl_feedbacks';
    protected $primaryKey = 'id_feedback';

    protected $fillable = [
        'id_aluno',
        'id_professor',
        'id_curso',
        'nota',
        'comentario',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno');
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'id_professor', 'id_professor');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
