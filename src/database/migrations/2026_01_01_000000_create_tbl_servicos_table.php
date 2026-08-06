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
        Schema::create('tbl_servicos', function (Blueprint $table) {
            $table->integer('id_servico', true);
            $table->integer('id_professor')->index('fk_servicos_professor');
            $table->string('titulo_servico', 100);
            $table->string('subtitulo_servico', 100);
            $table->text('lista_beneficios_servico');
            $table->string('cta_titulo_servico');
            $table->string('cta_texto_servico');
            $table->string('link_whatsapp');
            $table->string('classe_estilo_servico', 50);
            $table->string('lingua_servico', 100);
            $table->string('titulo_professor_servico');
            $table->text('conteudo_servico');
            $table->string('preco_servico', 100);
            $table->string('contato_text_servico');
            $table->integer('ordenar_servico');
            $table->string('imagem_servico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_servicos');
    }
};
