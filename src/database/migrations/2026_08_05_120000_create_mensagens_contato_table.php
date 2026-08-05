<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensagens_contato', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('professor_id')->nullable();

            $table->string('nome');
            $table->string('email');
            $table->string('assunto');
            $table->text('mensagem');

            $table->boolean('lida')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensagens_contato');
    }
};
