<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Acrescenta 'audio' ao enum tipo_questao (pergunta onde o aluno grava a
 * resposta com o microfone, em vez de escrever/escolher). Sem doctrine/dbal
 * no projeto, ->change() não está disponível — por isso o SQL é escrito à
 * mão por driver: MODIFY direto no MySQL, recriação da tabela no SQLite
 * (onde enum() vira "varchar check (...)", e SQLite não altera um CHECK
 * existente sem recriar a tabela).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::create('tbl_atividade_questoes_tmp', function (Blueprint $table) {
                $table->integer('id_questao', true);
                $table->integer('id_atividade')->index('tbl_atividade_questoes_tmp_id_atividade_index');
                $table->text('enunciado');
                $table->enum('tipo_questao', ['multipla_escolha', 'texto', 'audio']);
                $table->string('opcao_a', 300)->nullable();
                $table->string('opcao_b', 300)->nullable();
                $table->string('opcao_c', 300)->nullable();
                $table->string('opcao_d', 300)->nullable();
                $table->char('resposta_correta', 1)->nullable();
                $table->integer('ordem')->nullable()->default(1);
            });

            DB::statement('INSERT INTO tbl_atividade_questoes_tmp SELECT * FROM tbl_atividade_questoes');
            Schema::drop('tbl_atividade_questoes');
            Schema::rename('tbl_atividade_questoes_tmp', 'tbl_atividade_questoes');

            return;
        }

        DB::statement(
            "ALTER TABLE tbl_atividade_questoes MODIFY tipo_questao ENUM('multipla_escolha', 'texto', 'audio') NOT NULL"
        );
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::create('tbl_atividade_questoes_tmp', function (Blueprint $table) {
                $table->integer('id_questao', true);
                $table->integer('id_atividade')->index('tbl_atividade_questoes_tmp_id_atividade_index');
                $table->text('enunciado');
                $table->enum('tipo_questao', ['multipla_escolha', 'texto']);
                $table->string('opcao_a', 300)->nullable();
                $table->string('opcao_b', 300)->nullable();
                $table->string('opcao_c', 300)->nullable();
                $table->string('opcao_d', 300)->nullable();
                $table->char('resposta_correta', 1)->nullable();
                $table->integer('ordem')->nullable()->default(1);
            });

            DB::statement(
                "INSERT INTO tbl_atividade_questoes_tmp SELECT * FROM tbl_atividade_questoes WHERE tipo_questao != 'audio'"
            );
            Schema::drop('tbl_atividade_questoes');
            Schema::rename('tbl_atividade_questoes_tmp', 'tbl_atividade_questoes');

            return;
        }

        DB::statement(
            "ALTER TABLE tbl_atividade_questoes MODIFY tipo_questao ENUM('multipla_escolha', 'texto') NOT NULL"
        );
    }
};
