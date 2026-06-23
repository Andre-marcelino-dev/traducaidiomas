<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reagendamentos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('aula_id');
            $table->unsignedBigInteger('professor_id');

            $table->dateTime('data_original')->nullable();
            $table->dateTime('data_sugerida')->nullable();
            $table->dateTime('data_nova')->nullable();

            $table->text('motivo');
            $table->text('resposta_professor')->nullable();

            $table->string('status')->default('pendente');

            $table->boolean('notificado_professor')->default(false);
            $table->boolean('notificado_aluno')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reagendamentos');
    }
};