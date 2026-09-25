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
            $table->integer('id_modulo')->nullable()->index('fk_atividades_modulo')->after('id_curso');
        });

        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->foreign(['id_modulo'], 'fk_atividades_modulo')->references(['id_modulo'])->on('tbl_modulos')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->dropForeign('fk_atividades_modulo');
            $table->dropColumn('id_modulo');
        });
    }
};
