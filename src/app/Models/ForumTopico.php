<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumTopico extends Model
{
    protected $table = 'tbl_forum_topicos';
    protected $primaryKey = 'id_topico';
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = ['id_curso', 'id_aluno', 'titulo_topico', 'descricao_topico', 'anexo_topico'];

    protected $casts = [
        'criado_em' => 'datetime',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno');
    }

    public function respostas()
    {
        return $this->hasMany(ForumResposta::class, 'id_topico', 'id_topico');
    }
}
