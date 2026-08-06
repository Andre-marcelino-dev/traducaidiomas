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
        Schema::table('tbl_agenda', function (Blueprint $table) {
            $table->foreign(['id_aluno'], 'fk_agenda_aluno')->references(['id_aluno'])->on('tbl_alunos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_professor'], 'fk_agenda_professor')->references(['id_professor'])->on('tbl_professor')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_agenda', function (Blueprint $table) {
            $table->dropForeign('fk_agenda_aluno');
            $table->dropForeign('fk_agenda_professor');
        });
    }
};
