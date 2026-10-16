<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Resultado anônimo de um teste de nivelamento concluído (sem dados pessoais).
 */
class QuizResultado extends Model
{
    protected $table = 'tbl_quiz_resultados';

    protected $primaryKey = 'id_quiz_resultado';

    // Só existe created_at (o resultado nunca é alterado depois de gravado).
    const UPDATED_AT = null;

    protected $fillable = [
        'idioma',
        'pontuacao',
        'total',
        'nivel_cefr',
        'curso_recomendado',
    ];
}
