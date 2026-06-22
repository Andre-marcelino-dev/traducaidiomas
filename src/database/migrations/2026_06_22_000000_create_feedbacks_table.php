<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_feedbacks', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->unsignedBigInteger('id_aluno');
            $table->unsignedBigInteger('id_professor');
            $table->unsignedBigInteger('id_curso');
            $table->tinyInteger('nota')->unsigned();
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->unique(['id_aluno', 'id_curso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_feedbacks');
    }
};
