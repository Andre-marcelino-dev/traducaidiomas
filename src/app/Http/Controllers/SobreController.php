<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SobreController extends Controller
{
    public function sobre()
    {
        $alunos = DB::table('tbl_alunos')
<<<<<<< HEAD
            ->leftJoin('tbl_matricula', 'tbl_alunos.id_aluno', '=', 'tbl_matricula.id_aluno')
=======
            ->join('tbl_matricula', 'tbl_alunos.id_aluno', '=', 'tbl_matricula.id_aluno')
            ->leftJoin('tbl_feedbacks', function ($join) {
                $join->on('tbl_alunos.id_aluno', '=', 'tbl_feedbacks.id_aluno')
                     ->on('tbl_matricula.id_curso', '=', 'tbl_feedbacks.id_curso');
            })
            ->leftJoin('tbl_professor', 'tbl_feedbacks.id_professor', '=', 'tbl_professor.id_professor')
            ->where('tbl_matricula.status_matricula', 'ATIVO')
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
            ->select(
                'tbl_alunos.*',
                'tbl_matricula.id_matricula',
                'tbl_matricula.data_matricula',
<<<<<<< HEAD
=======
                'tbl_feedbacks.nota',
                'tbl_feedbacks.comentario',
                'tbl_professor.nome_professor',
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
            )
            ->get();

        return view('site.sobre.sobre', compact('alunos'));
    }
}