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
        Schema::table('tbl_progresso_materiais', function (Blueprint $table) {
            $table->foreign(['id_aluno'], 'fk_progresso_materiais_aluno')->references(['id_aluno'])->on('tbl_alunos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_materiais'], 'fk_progresso_materiais_materiais')->references(['id_materiais'])->on('tbl_materiais')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_progresso_materiais', function (Blueprint $table) {
            $table->dropForeign('fk_progresso_materiais_aluno');
            $table->dropForeign('fk_progresso_materiais_materiais');
        });
    }
};
