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
            ->leftJoin('tbl_cursos', 'tbl_matricula.id_curso', '=', 'tbl_cursos.id_curso')
            ->leftJoin('tbl_niveis', 'tbl_matricula.id_nivel', '=', 'tbl_niveis.id_nivel')
            ->select(
                'tbl_alunos.*', 
                'tbl_matricula.id_matricula', 
                'tbl_matricula.data_matricula',
                'tbl_cursos.nome_curso as curso_aluno',  // Apelidado para bater com sua blade
                'tbl_niveis.nome_nivel as nivel_aluno'   // Apelidado para bater com sua blade
            )
            ->get();

        return view('site.sobre.sobre', compact('alunos'));
    }
}