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
        Schema::create('tbl_atividades', function (Blueprint $table) {
            $table->integer('id_atividade', true);
            $table->integer('id_professor')->index('id_professor');
            $table->integer('id_curso')->index('id_curso');
            $table->string('titulo_atividade', 200);
            $table->text('descricao_atividade')->nullable();
            $table->enum('tipo_atividade', ['multipla_escolha', 'texto', 'misto'])->nullable()->default('misto');
            $table->date('data_entrega')->nullable();
            $table->enum('status_atividade', ['ATIVA', 'INATIVA'])->nullable()->default('ATIVA');
            $table->timestamp('criado_em')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_atividades');
    }
};
