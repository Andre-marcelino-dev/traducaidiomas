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
        Schema::create('tbl_professor', function (Blueprint $table) {
            $table->integer('id_professor', true);
            $table->string('nome_professor', 100);
            $table->string('especialidade_professor', 100);
            $table->string('experiencia_professor', 50);
            $table->text('bio_professor');
            $table->string('foto_professor');
            $table->string('email_professor', 100);
            $table->string('curso_professor', 50);
            $table->string('nivel_professor', 20);
            $table->string('telefone_professor', 14);
            $table->string('senha_professor');
            $table->dateTime('criado_em_professor')->useCurrent();
            $table->dateTime('atualizado_em_professor')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_professor');
    }
};
