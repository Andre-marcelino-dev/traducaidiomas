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
        Schema::table('tbl_servicos', function (Blueprint $table) {
            $table->foreign(['id_professor'], 'fk_servicos_professor')->references(['id_professor'])->on('tbl_professor')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_servicos', function (Blueprint $table) {
            $table->dropForeign('fk_servicos_professor');
        });
    }
};
