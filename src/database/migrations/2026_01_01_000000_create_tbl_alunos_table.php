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
        Schema::create('tbl_alunos', function (Blueprint $table) {
            $table->integer('id_aluno', true);
            $table->string('nome_aluno', 100);
            $table->string('email_aluno', 80)->unique('email_aluno');
            $table->string('senha_aluno');
            $table->string('telefone_aluno', 14);
            $table->string('curso_aluno', 100);
            $table->date('data_nasc_aluno');
            $table->string('nivel_aluno', 50);
            $table->string('foto_aluno', 80);
            $table->string('status_aluno', 10)->default('ATIVO');
            $table->dateTime('criado_em_aluno')->useCurrent();
            $table->dateTime('atualizado_em_aluno')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_alunos');
    }
};
