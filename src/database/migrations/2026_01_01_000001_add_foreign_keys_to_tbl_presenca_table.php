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
        Schema::table('tbl_presenca', function (Blueprint $table) {
            $table->foreign(['id_aluno'], 'fk_presenca_alunos')->references(['id_aluno'])->on('tbl_alunos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_aulas'], 'fk_presenca_aulas')->references(['id_aulas'])->on('tbl_aulas')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_presenca', function (Blueprint $table) {
            $table->dropForeign('fk_presenca_alunos');
            $table->dropForeign('fk_presenca_aulas');
        });
    }
};
