<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Número da aula dentro do módulo ("AULA 01") e duração ("22 min"), usados no app.
 * Ficam opcionais: sem ordem, o app numera pela data/hora; sem duração, não mostra.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_aulas', function (Blueprint $table) {
            $table->unsignedSmallInteger('ordem_aula')->nullable()->after('id_modulo');
            $table->unsignedSmallInteger('duracao_minutos')->nullable()->after('hora_aulas');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_aulas', function (Blueprint $table) {
            $table->dropColumn(['ordem_aula', 'duracao_minutos']);
        });
    }
};
