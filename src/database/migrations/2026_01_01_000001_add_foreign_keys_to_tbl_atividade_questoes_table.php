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
        Schema::table('tbl_atividade_questoes', function (Blueprint $table) {
            $table->foreign(['id_atividade'], 'tbl_atividade_questoes_ibfk_1')->references(['id_atividade'])->on('tbl_atividades')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_atividade_questoes', function (Blueprint $table) {
            $table->dropForeign('tbl_atividade_questoes_ibfk_1');
        });
    }
};
