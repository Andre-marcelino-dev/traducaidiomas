<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
        protected $table = 'tbl_servicos';
    protected $primaryKey = 'id_servico';


    public $timestamps = true;



    protected $fillable = [
        'titulo_servico',
        'subtitulo_servico'

 ];


}
