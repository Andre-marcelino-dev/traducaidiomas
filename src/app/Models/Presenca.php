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

  // A justificativa mais recente decide o que o aluno vê (em análise, recusada...).
  public function ultimaJustificativa() { return $this->hasOne(JustificativaFalta::class, 'id_presenca', 'id_presenca')->latestOfMany('id_justificativa'); }

  public function ehFalta(): bool { return strtolower((string) $this->status_presenca) === 'falta'; }
}
