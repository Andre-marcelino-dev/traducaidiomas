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
        Schema::create('tbl_configuracoes_painel', function (Blueprint $table) {
            $table->integer('id_configuracoes_painel', true);
            $table->string('chave_configuracoes_painel', 100);
            $table->text('valor_configuracoes_painel');
            $table->timestamp('update_at_configuracoes_painel')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_configuracoes_painel');
    }
};
