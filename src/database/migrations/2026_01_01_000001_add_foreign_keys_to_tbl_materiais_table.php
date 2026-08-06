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
        Schema::table('tbl_materiais', function (Blueprint $table) {
            $table->foreign(['id_curso'], 'fk_materiais_curso')->references(['id_curso'])->on('tbl_cursos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_professor'], 'fk_materiais_professor')->references(['id_professor'])->on('tbl_professor')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_materiais', function (Blueprint $table) {
            $table->dropForeign('fk_materiais_curso');
            $table->dropForeign('fk_materiais_professor');
        });
    }
};
