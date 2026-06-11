<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table      = 'tbl_notificacoes';
    protected $primaryKey = 'id_notificacoes';
    public $timestamps    = false;

    protected $fillable = [
        'id_aluno',
        'id_professor',
        'id_materiais',
        'mensagem_notificacoes',
        'link_notificacoes',
        'lida_notificacoes',
        'data_criacao_notificacoes',
    ];
}
