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
        Schema::create('tbl_agenda', function (Blueprint $table) {
            $table->integer('id_agenda', true);
            $table->integer('id_aluno')->index('fk_agenda_aluno');
            $table->integer('id_professor')->index('fk_agenda_professor');
            $table->string('titulo_agenda', 100);
            $table->text('descricao_agenda');
            $table->date('data_evento_agenda');
            $table->time('hora_inicio_agenda');
            $table->time('hora_fim_agenda');
            $table->string('status_agenda', 50);
            $table->boolean('solicitacao_reagendamento')->nullable();
            $table->string('link_aula_agenda', 500)->nullable();
            $table->dateTime('criado_em_agenda')->useCurrent();
            $table->dateTime('atualizado_em_agenda')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_agenda');
    }
};
