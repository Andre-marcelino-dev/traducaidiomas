<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SobreController extends Controller
{
    public function sobre()
    {
        // Busca todos os registros da tabela correta: 'tbl_alunos'
        $alunos = DB::table('tbl_alunos')->get();

        // Retorna a view enviando a variável dos alunos
        return view('site.sobre.sobre', compact('alunos'));
    }
}