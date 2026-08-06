<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumResposta extends Model
{
    protected $table = 'tbl_forum_respostas';
    protected $primaryKey = 'id_resposta_forum';
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = null;

    protected $fillable = ['id_topico', 'id_aluno', 'conteudo_resposta'];

    protected $casts = [
        'criado_em' => 'datetime',
    ];

    public function topico()
    {
        return $this->belongsTo(ForumTopico::class, 'id_topico', 'id_topico');
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno');
    }
}
