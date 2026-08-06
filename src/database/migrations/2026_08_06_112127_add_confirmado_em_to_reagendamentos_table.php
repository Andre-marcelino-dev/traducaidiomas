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
        if (Schema::hasColumn('reagendamentos', 'confirmado_em')) {
            return;
        }

        Schema::table('reagendamentos', function (Blueprint $table) {
            $table->dateTime('confirmado_em')->nullable()->after('notificado_aluno');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reagendamentos', function (Blueprint $table) {
            $table->dropColumn('confirmado_em');
        });
    }
};
