<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_professor', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('senha_professor');
        });

        // Quem já existe continua com acesso total (ninguém fica trancado para fora).
        // Depois, pelo painel, o admin desmarca quem não deve ser admin.
        DB::table('tbl_professor')->update(['is_admin' => true]);
    }

    public function down(): void
    {
        Schema::table('tbl_professor', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
