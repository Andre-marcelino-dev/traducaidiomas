<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Justificativa que o aluno manda para uma falta lançada pelo professor.
 * Aluno e aula vêm da presença; se a presença for apagada (aluno ou aula
 * excluídos), a justificativa vai junto.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_justificativa_falta', function (Blueprint $table) {
            $table->id('id_justificativa');
            $table->foreignId('id_presenca')->constrained('presenca', 'id_presenca')->cascadeOnDelete();
            $table->text('motivo_justificativa');
            $table->string('status_justificativa', 10)->default('pendente'); // pendente, aceita, recusada
            $table->text('resposta_professor')->nullable();
            $table->dateTime('respondido_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_justificativa_falta');
    }
};
