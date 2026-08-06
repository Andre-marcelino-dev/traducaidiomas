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
        Schema::create('tbl_aulas', function (Blueprint $table) {
            $table->integer('id_aulas', true);
            $table->integer('id_professor')->index('fk_aulas_professor');
            $table->string('titulo_aulas', 100);
            $table->text('descricao_aulas');
            $table->date('data_aulas');
            $table->time('hora_aulas');
            $table->string('link_teams', 500)->nullable();
            $table->string('cursos_aulas', 100);
            $table->string('status_aulas', 10)->default('ATIVO');
            $table->dateTime('criado_em_aulas')->useCurrent();
            $table->dateTime('atualizado_em_aulas')->useCurrent();
            $table->integer('id_curso')->index('fk_aulas_curso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_aulas');
    }
};
