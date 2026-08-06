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
        Schema::create('tbl_progresso_materiais', function (Blueprint $table) {
            $table->integer('id_progresso', true);
            $table->integer('id_aluno')->index('fk_progresso_materiais_aluno');
            $table->integer('id_materiais')->index('fk_progresso_materiais_materiais');
            $table->string('status_progresso', 15)->default('EM ANDAMENTO');
            $table->integer('progresso_materiais');
            $table->timestamp('data_acesso_progresso_materiais')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_progresso_materiais');
    }
};
