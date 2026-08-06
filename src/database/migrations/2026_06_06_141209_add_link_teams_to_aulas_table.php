<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tbl_aulas', 'link_teams')) {
            return;
        }

        Schema::table('tbl_aulas', function (Blueprint $table) {
            $table->string('link_teams', 500)->nullable()->after('hora_aulas');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_aulas', function (Blueprint $table) {
            $table->dropColumn('link_teams');
        });
    }
};
