<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class JustificativaFalta extends Model
{
    protected $table = 'tbl_justificativa_falta';
    protected $primaryKey = 'id_justificativa';

    protected $fillable = [
        'id_presenca',
        'motivo_justificativa',
        'status_justificativa',
        'resposta_professor',
        'respondido_em',
    ];

    protected $casts = [
        'respondido_em' => 'datetime',
    ];

    public function presenca()
    {
        return $this->belongsTo(Presenca::class, 'id_presenca', 'id_presenca');
    }

    /**
     * Admin vê todas; professor comum só as das próprias aulas.
     */
    public function scopeVisivelPara(Builder $query, Professor $professor): Builder
    {
        if ($professor->is_admin) {
            return $query;
        }

        return $query->whereHas('presenca.aula', fn ($q) => $q->where('id_professor', $professor->id_professor));
    }

    public function scopePendentes(Builder $query): Builder
    {
        return $query->where('status_justificativa', 'pendente');
    }
}
