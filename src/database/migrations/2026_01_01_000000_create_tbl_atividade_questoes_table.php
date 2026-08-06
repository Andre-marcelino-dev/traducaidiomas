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
        Schema::create('tbl_atividade_questoes', function (Blueprint $table) {
            $table->integer('id_questao', true);
            $table->integer('id_atividade')->index('id_atividade');
            $table->text('enunciado');
            $table->enum('tipo_questao', ['multipla_escolha', 'texto']);
            $table->string('opcao_a', 300)->nullable();
            $table->string('opcao_b', 300)->nullable();
            $table->string('opcao_c', 300)->nullable();
            $table->string('opcao_d', 300)->nullable();
            $table->char('resposta_correta', 1)->nullable();
            $table->integer('ordem')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_atividade_questoes');
    }
};
