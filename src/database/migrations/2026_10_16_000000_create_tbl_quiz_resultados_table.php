<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Resultados anônimos do teste de nivelamento (não guarda nome, e-mail nem IP).
     */
    public function up(): void
    {
        Schema::create('tbl_quiz_resultados', function (Blueprint $table) {
            $table->integer('id_quiz_resultado', true);
            $table->string('idioma', 30)->index();
            $table->unsignedTinyInteger('pontuacao');
            $table->unsignedTinyInteger('total');
            $table->string('nivel_cefr', 2);
            $table->string('curso_recomendado', 100);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_quiz_resultados');
    }
};
