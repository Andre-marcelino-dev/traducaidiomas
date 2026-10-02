<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_atividade_resposta_questoes', function (Blueprint $table) {
            $table->string('audio_resposta', 500)
                ->nullable()
                ->after('resposta_aluno');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_atividade_resposta_questoes', function (Blueprint $table) {
            $table->dropColumn('audio_resposta');
        });
    }
};