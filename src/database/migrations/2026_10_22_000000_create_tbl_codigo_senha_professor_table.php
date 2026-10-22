<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Código de 6 dígitos enviado por e-mail pra "Esqueci minha senha" do painel
 * (admin e professores). Mesmo modelo de tbl_codigo_senha_aluno, mais um
 * contador de tentativas erradas: depois de 5, o código deixa de valer.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_codigo_senha_professor', function (Blueprint $table) {
            $table->id('id_codigo');
            $table->string('email_professor', 100)->index();
            $table->string('codigo', 6);
            $table->timestamp('expira_em');
            $table->boolean('usado')->default(false);
            $table->unsignedTinyInteger('tentativas')->default(0);
            $table->timestamp('criado_em')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_codigo_senha_professor');
    }
};
