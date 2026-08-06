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
        Schema::create('tbl_forum_respostas', function (Blueprint $table) {
            $table->integer('id_resposta_forum', true);
            $table->integer('id_topico')->index('fk_forum_respostas_topico');
            $table->integer('id_aluno')->index('fk_forum_respostas_aluno');
            $table->text('conteudo_resposta');
            $table->timestamp('criado_em')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_forum_respostas');
    }
};
