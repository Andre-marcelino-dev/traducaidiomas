<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'tbl_modulos';

    protected $primaryKey = 'id_modulo';

    protected $fillable = [
        'id_curso',
        'id_nivel',
        'ordem_modulo',
        'nome_modulo',
        'descricao_modulo',
        'carga_horaria_minutos',
        'status_modulo',
    ];

    protected $casts = [
        'criado_em_modulo'     => 'datetime',
        'atualizado_em_modulo' => 'datetime',
    ];

    const CREATED_AT = 'criado_em_modulo';
    const UPDATED_AT = 'atualizado_em_modulo';

    // ──────────────── Relacionamentos ────────────────

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id_nivel');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class, 'id_modulo', 'id_modulo');
    }

    public function materiais()
    {
        return $this->hasMany(Materiais::class, 'id_modulo', 'id_modulo');
    }

    public function atividades()
    {
        return $this->hasMany(Atividade::class, 'id_modulo', 'id_modulo');
    }
}
