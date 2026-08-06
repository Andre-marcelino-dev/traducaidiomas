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
        Schema::table('tbl_atividade_resposta_questoes', function (Blueprint $table) {
            $table->foreign(['id_resposta'], 'tbl_atividade_resposta_questoes_ibfk_1')->references(['id_resposta'])->on('tbl_atividade_respostas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_questao'], 'tbl_atividade_resposta_questoes_ibfk_2')->references(['id_questao'])->on('tbl_atividade_questoes')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_atividade_resposta_questoes', function (Blueprint $table) {
            $table->dropForeign('tbl_atividade_resposta_questoes_ibfk_1');
            $table->dropForeign('tbl_atividade_resposta_questoes_ibfk_2');
        });
    }
};
