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
        Schema::create('tbl_forum_topicos', function (Blueprint $table) {
            $table->integer('id_topico', true);
            $table->integer('id_curso')->index('fk_forum_topicos_curso');
            $table->integer('id_aluno')->index('fk_forum_topicos_aluno');
            $table->string('titulo_topico', 200);
            $table->text('descricao_topico');
            $table->string('anexo_topico')->nullable();
            $table->timestamp('criado_em')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_forum_topicos');
    }
};
