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
        Schema::create('tbl_modulos', function (Blueprint $table) {
            $table->integer('id_modulo', true);
            $table->integer('id_curso')->index('fk_modulos_curso');
            $table->integer('id_nivel')->index('fk_modulos_nivel');
            $table->unsignedSmallInteger('ordem_modulo');
            $table->string('nome_modulo', 100);
            $table->text('descricao_modulo')->nullable();
            $table->unsignedInteger('carga_horaria_minutos')->default(0);
            $table->enum('status_modulo', ['ATIVO', 'INATIVO'])->default('ATIVO');
            $table->dateTime('criado_em_modulo')->useCurrent();
            $table->dateTime('atualizado_em_modulo')->useCurrentOnUpdate()->useCurrent();

            $table->unique(['id_curso', 'id_nivel', 'ordem_modulo'], 'uq_modulos_curso_nivel_ordem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_modulos');
    }
};
