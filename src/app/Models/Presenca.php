<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $table = 'presenca';
    protected $primaryKey = 'id_presenca';
    public $timestamps = false;

    protected $fillable = ['id_aulas', 'id_aluno', 'status_presenca', 'data_registro_presenca'];

  public function aula() { return $this->belongsTo(Aula::class, 'id_aulas', 'id_aulas'); }
  public function aluno() { return $this->belongsTo(Aluno::class, 'id_aluno', 'id_aluno'); }
}
