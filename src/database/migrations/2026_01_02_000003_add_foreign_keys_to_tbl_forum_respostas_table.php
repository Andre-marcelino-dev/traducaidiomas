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
        Schema::table('tbl_forum_respostas', function (Blueprint $table) {
            $table->foreign(['id_topico'], 'fk_forum_respostas_topico')->references(['id_topico'])->on('tbl_forum_topicos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_aluno'], 'fk_forum_respostas_aluno')->references(['id_aluno'])->on('tbl_alunos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_forum_respostas', function (Blueprint $table) {
            $table->dropForeign('fk_forum_respostas_topico');
            $table->dropForeign('fk_forum_respostas_aluno');
        });
    }
};
