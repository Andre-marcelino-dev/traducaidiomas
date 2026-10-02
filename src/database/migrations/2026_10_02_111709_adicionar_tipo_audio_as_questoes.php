<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE tbl_atividade_questoes
            MODIFY tipo_questao ENUM(
                'multipla_escolha',
                'texto',
                'audio'
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE tbl_atividade_questoes
            MODIFY tipo_questao ENUM(
                'multipla_escolha',
                'texto'
            )
        ");
    }
};