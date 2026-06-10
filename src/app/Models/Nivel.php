<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'tbl_niveis'; 
    protected $primaryKey = 'id_nivel';
    public $timestamps = false;
    protected $fillable = [
        'nome_nivel',
    ];
}
