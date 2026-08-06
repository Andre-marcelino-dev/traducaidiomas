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
        Schema::table('tbl_forum_topicos', function (Blueprint $table) {
            $table->foreign(['id_curso'], 'fk_forum_topicos_curso')->references(['id_curso'])->on('tbl_cursos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_aluno'], 'fk_forum_topicos_aluno')->references(['id_aluno'])->on('tbl_alunos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_forum_topicos', function (Blueprint $table) {
            $table->dropForeign('fk_forum_topicos_curso');
            $table->dropForeign('fk_forum_topicos_aluno');
        });
    }
};
