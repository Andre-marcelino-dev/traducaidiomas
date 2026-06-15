<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $table = 'tbl_servicos';
    protected $primaryKey = 'id_servico';

     public $timestamps = false; // 👈 ISSO AQUI CORRIGE O ERRO

    protected $fillable = [
        'id_professor',
        'titulo_servico',
        'subtitulo_servico',
        'lista_beneficios_servico',
        'cta_titulo_servico',
        'cta_texto_servico',
        'link_whatsapp',
        'classe_estilo_servico',
        'lingua_servico',
        'titulo_professor_servico',
        'conteudo_servico',
        'preco_servico',
        'contato_text_servico',
        'ordenar_servico',
        'imagem_servico',
    ];
}