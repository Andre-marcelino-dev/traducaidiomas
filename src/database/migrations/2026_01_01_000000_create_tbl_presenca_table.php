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
        Schema::create('tbl_presenca', function (Blueprint $table) {
            $table->integer('id_presenca', true);
            $table->integer('id_aulas')->index('fk_presenca_aulas');
            $table->integer('id_aluno')->index('fk_presenca_alunos');
            $table->string('status_presenca', 10)->default('PRESENTE');
            $table->dateTime('data_pregistro_presenca')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_presenca');
    }
};
