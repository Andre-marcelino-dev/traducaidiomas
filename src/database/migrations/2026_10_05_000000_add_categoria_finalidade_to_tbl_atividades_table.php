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
            $table->enum('categoria_atividade', ['GRAMATICA', 'AUDIO', 'FALA', 'LEITURA', 'ESCRITA', 'VOCABULARIO'])
                ->nullable()->after('tipo_atividade');
            $table->enum('finalidade_atividade', ['FIXACAO', 'REVISAO', 'AVALIACAO'])
                ->nullable()->default('FIXACAO')->after('categoria_atividade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->dropColumn(['categoria_atividade', 'finalidade_atividade']);
        });
    }
};
