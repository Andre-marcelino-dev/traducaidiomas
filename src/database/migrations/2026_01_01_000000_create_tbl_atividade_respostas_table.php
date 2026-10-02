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
        Schema::create('tbl_atividade_respostas', function (Blueprint $table) {
            $table->integer('id_resposta', true);
            $table->integer('id_atividade')->index('tbl_atividade_respostas_id_atividade_index');
            $table->integer('id_aluno')->index('tbl_atividade_respostas_id_aluno_index');
            $table->enum('status_resposta', ['PENDENTE', 'ENVIADA', 'CORRIGIDA'])->nullable()->default('PENDENTE');
            $table->decimal('nota', 4, 1)->nullable();
            $table->text('feedback_professor')->nullable();
            $table->timestamp('data_envio')->nullable();
            $table->timestamp('data_correcao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_atividade_respostas');
    }
};
