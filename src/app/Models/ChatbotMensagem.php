<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMensagem extends Model
{
    protected $table = 'chatbot_mensagens';


    protected $fillable = [
        'id_usuario',
        'tipo',
        'mensagem'
    ];
}