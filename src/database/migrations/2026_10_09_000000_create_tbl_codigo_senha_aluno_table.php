<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Código de 6 dígitos enviado por e-mail pra "Esqueci minha senha" (site e app).
 * Guarda por e-mail (não por id_aluno) porque o pedido pode ser feito antes de
 * confirmar se o e-mail existe mesmo (evita vazar quais e-mails estão cadastrados).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_codigo_senha_aluno', function (Blueprint $table) {
            $table->id('id_codigo');
            $table->string('email_aluno', 80)->index();
            $table->string('codigo', 6);
            $table->timestamp('expira_em');
            $table->boolean('usado')->default(false);
            $table->timestamp('criado_em')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_codigo_senha_aluno');
    }
};
