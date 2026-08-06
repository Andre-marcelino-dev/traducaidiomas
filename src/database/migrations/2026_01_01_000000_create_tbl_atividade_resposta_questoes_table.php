<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_atividade_resposta_questoes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_resposta')->index('id_resposta');
            $table->integer('id_questao')->index('id_questao');
            $table->text('resposta_aluno')->nullable();
            $table->boolean('correta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_atividade_resposta_questoes');
    }
};
