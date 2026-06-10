<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiais extends Model
{
    use HasFactory;

  protected $table = 'tbl_materiais';
    protected $primaryKey = 'id_materiais';

    protected $fillable = [
        'id_professor',
        'titulo_materiais',
        'descricao_materiais',
        'arquivo_materiais',
        'curso_materiais',
        'nivel_material',
        'id_curso',
    ];

    protected $casts = [
        'criado_em_materiais'     => 'datetime',
        'atualizado_em_materiais' => 'datetime',
    ];

    // Timestamps personalizados
    const CREATED_AT  = 'criado_em_materiais';
    const UPDATED_AT  = 'atualizado_em_materiais';

    // ──────────────── Relacionamentos ────────────────

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'id_professor', 'id_professor');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
