<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Primeiro permitimos os valores antigos e os novos
         * para conseguir converter as atividades existentes.
         */
        DB::statement("
            ALTER TABLE tbl_atividades
            MODIFY tipo_atividade ENUM(
                'multipla_escolha',
                'texto',
                'misto',
                'conjugacao',
                'conversa',
                'pronuncia',
                'leitura'
            ) NULL DEFAULT 'conjugacao'
        ");

        /*
         * As atividades antigas usavam "misto".
         * Como conjugação mantém o comportamento base atual,
         * migramos essas atividades para conjugação.
         */
        DB::table('tbl_atividades')
            ->where('tipo_atividade', 'misto')
            ->update(['tipo_atividade' => 'conjugacao']);

        /*
         * Agora removemos os valores antigos do ENUM.
         */
        DB::statement("
            ALTER TABLE tbl_atividades
            MODIFY tipo_atividade ENUM(
                'conjugacao',
                'conversa',
                'pronuncia',
                'leitura'
            ) NULL DEFAULT 'conjugacao'
        ");

        /*
         * Áudio enviado pelo professor para a atividade.
         * Usado principalmente em Conversa e, opcionalmente, Leitura.
         */
        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->string('audio', 500)
                ->nullable()
                ->after('descricao_atividade');
        });
    }

    public function down(): void
    {
        /*
         * Primeiro permitimos novamente os valores antigos.
         */
        DB::statement("
            ALTER TABLE tbl_atividades
            MODIFY tipo_atividade ENUM(
                'conjugacao',
                'conversa',
                'pronuncia',
                'leitura',
                'multipla_escolha',
                'texto',
                'misto'
            ) NULL DEFAULT 'misto'
        ");

        /*
         * As atividades criadas com os novos estilos
         * voltam para o comportamento antigo.
         */
        DB::table('tbl_atividades')
            ->whereIn('tipo_atividade', [
                'conjugacao',
                'conversa',
                'pronuncia',
                'leitura'
            ])
            ->update(['tipo_atividade' => 'misto']);

        Schema::table('tbl_atividades', function (Blueprint $table) {
            $table->dropColumn('audio');
        });

        DB::statement("
            ALTER TABLE tbl_atividades
            MODIFY tipo_atividade ENUM(
                'multipla_escolha',
                'texto',
                'misto'
            ) NULL DEFAULT 'misto'
        ");
    }
};