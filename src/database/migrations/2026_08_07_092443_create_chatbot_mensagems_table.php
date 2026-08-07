<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_mensagens', function (Blueprint $table) {

            $table->id();

            // usuário da conversa
            $table->integer('id_usuario');

            // user ou ai
            $table->string('tipo');

            // texto da mensagem
            $table->text('mensagem');

            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('chatbot_mensagens');
    }
};