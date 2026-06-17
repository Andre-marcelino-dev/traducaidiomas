<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SobreController extends Controller
{
    public function sobre()
    {
        $alunos = DB::table('tbl_alunos')
            ->leftJoin('tbl_matricula', 'tbl_alunos.id_aluno', '=', 'tbl_matricula.id_aluno')
            ->select(
                'tbl_alunos.*',
                'tbl_matricula.id_matricula',
                'tbl_matricula.data_matricula',
            )
            ->get();

        return view('site.sobre.sobre', compact('alunos'));
    }
}