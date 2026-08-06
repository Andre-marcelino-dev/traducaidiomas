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
        Schema::create('tbl_matricula', function (Blueprint $table) {
            $table->integer('id_matricula', true);
            $table->integer('id_aluno')->index('fk_matricula_aluno');
            $table->integer('id_curso')->index('fk_matricula_curso');
            $table->integer('id_nivel')->index('fk_matricula_nivel');
            $table->timestamp('data_matricula')->useCurrent();
            $table->enum('status_matricula', ['ATIVO', 'CONGELADO', 'CANCELADO'])->default('ATIVO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_matricula');
    }
};
