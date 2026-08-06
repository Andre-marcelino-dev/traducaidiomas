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
        Schema::create('tbl_materiais', function (Blueprint $table) {
            $table->integer('id_materiais', true);
            $table->integer('id_professor')->index('fk_materiais_professor');
            $table->string('titulo_materiais');
            $table->text('descricao_materiais');
            $table->string('arquivo_materiais');
            $table->string('curso_materiais')->nullable();
            $table->string('nivel_material', 50);
            $table->dateTime('criado_em_materiais')->useCurrent();
            $table->dateTime('atualizado_em_materiais')->useCurrentOnUpdate()->useCurrent();
            $table->integer('id_curso')->index('fk_materiais_curso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_materiais');
    }
};
