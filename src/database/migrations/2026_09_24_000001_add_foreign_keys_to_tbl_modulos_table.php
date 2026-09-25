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
        Schema::table('tbl_modulos', function (Blueprint $table) {
            $table->foreign(['id_curso'], 'fk_modulos_curso')->references(['id_curso'])->on('tbl_cursos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_nivel'], 'fk_modulos_nivel')->references(['id_nivel'])->on('tbl_niveis')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_modulos', function (Blueprint $table) {
            $table->dropForeign('fk_modulos_curso');
            $table->dropForeign('fk_modulos_nivel');
        });
    }
};
