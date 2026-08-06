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
        Schema::create('tbl_notificacoes', function (Blueprint $table) {
            $table->integer('id_notificacoes', true);
            $table->integer('id_aluno')->index('fk_notificacoes_aluno');
            $table->integer('id_professor')->index('fk_notificacoes_professor');
            $table->integer('id_materiais')->nullable()->index('fk_notificacoes_materiais');
            $table->text('mensagem_notificacoes');
            $table->string('link_notificacoes');
            $table->boolean('lida_notificacoes');
            $table->timestamp('data_criacao_notificacoes')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_notificacoes');
    }
};
