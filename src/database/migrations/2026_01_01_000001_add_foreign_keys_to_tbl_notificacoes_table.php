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
        Schema::table('tbl_notificacoes', function (Blueprint $table) {
            $table->foreign(['id_aluno'], 'fk_notificacoes_aluno')->references(['id_aluno'])->on('tbl_alunos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_materiais'], 'fk_notificacoes_materiais')->references(['id_materiais'])->on('tbl_materiais')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_professor'], 'fk_notificacoes_professor')->references(['id_professor'])->on('tbl_professor')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_notificacoes', function (Blueprint $table) {
            $table->dropForeign('fk_notificacoes_aluno');
            $table->dropForeign('fk_notificacoes_materiais');
            $table->dropForeign('fk_notificacoes_professor');
        });
    }
};
