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
        Schema::table('tbl_cursos', function (Blueprint $table) {
            // String(100) para manter consistência com tbl_servicos.preco_servico.
            // Nullable para não quebrar registros existentes.
            $table->string('preco_curso', 100)->nullable()->after('descricao_curso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_cursos', function (Blueprint $table) {
            $table->dropColumn('preco_curso');
        });
    }
};
