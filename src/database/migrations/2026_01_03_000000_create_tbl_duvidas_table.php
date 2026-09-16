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
        Schema::create('tbl_duvidas', function (Blueprint $table) {
            $table->integer('id_duvida', true);
            $table->integer('id_aluno')->index('fk_duvidas_aluno');
            $table->string('assunto_duvida', 150);
            $table->text('mensagem_duvida');
            $table->text('resposta_professor')->nullable();
            $table->string('status_duvida', 20)->default('pendente');
            $table->timestamp('criado_em')->nullable()->useCurrent();
            $table->timestamp('respondido_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_duvidas');
    }
};
