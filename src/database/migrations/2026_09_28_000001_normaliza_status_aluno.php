<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * status_aluno passa a ter só 3 valores: EM CURSO, CONCLUIDO, INATIVO.
 * Antes o banco usava ATIVO como padrão e o formulário gravava CONCLUÍDO (com acento),
 * então esses alunos sumiam das telas que filtram por EM CURSO (presença, reagendamento).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('tbl_alunos')->where('status_aluno', 'ATIVO')->update(['status_aluno' => 'EM CURSO']);
        DB::table('tbl_alunos')->where('status_aluno', 'CONCLUÍDO')->update(['status_aluno' => 'CONCLUIDO']);

        Schema::table('tbl_alunos', function (Blueprint $table) {
            $table->string('status_aluno', 10)->default('EM CURSO')->change();
        });
    }

    public function down(): void
    {
        Schema::table('tbl_alunos', function (Blueprint $table) {
            $table->string('status_aluno', 10)->default('ATIVO')->change();
        });
    }
};
