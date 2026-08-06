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
        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->foreign(['id_professor'], 'tbl_atividades_ibfk_1')->references(['id_professor'])->on('tbl_professor')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_curso'], 'tbl_atividades_ibfk_2')->references(['id_curso'])->on('tbl_cursos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->dropForeign('tbl_atividades_ibfk_1');
            $table->dropForeign('tbl_atividades_ibfk_2');
        });
    }
};
