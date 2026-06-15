<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presenca', function (Blueprint $table) {
            $table->id('id_presenca');
            $table->integer('id_aulas');
            $table->integer('id_aluno');
            $table->string('status_presenca');
            $table->date('data_registro_presenca');

            $table->foreign('id_aulas')->references('id_aulas')->on('tbl_aulas')->onDelete('cascade');
            $table->foreign('id_aluno')->references('id_aluno')->on('tbl_alunos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presenca');
    }
};
