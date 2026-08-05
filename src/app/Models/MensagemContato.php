<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensagemContato extends Model
{
    protected $table = 'mensagens_contato';

    protected $fillable = [
        'professor_id',
        'nome',
        'email',
        'assunto',
        'mensagem',
        'lida',
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }
}
