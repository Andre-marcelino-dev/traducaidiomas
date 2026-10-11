<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SobreController extends Controller
{
    // tbl_niveis guarda os nomes sem acento
    private const NIVEIS = [
        'iniciante'     => 'Iniciante',
        'intermediario' => 'Intermediário',
        'avancado'      => 'Avançado',
    ];

    public function sobre()
    {
        $alunos = DB::table('tbl_alunos')
            ->join('tbl_matricula', 'tbl_alunos.id_aluno', '=', 'tbl_matricula.id_aluno')
            ->leftJoin('tbl_feedbacks', function ($join) {
                $join->on('tbl_alunos.id_aluno', '=', 'tbl_feedbacks.id_aluno')
                     ->on('tbl_matricula.id_curso', '=', 'tbl_feedbacks.id_curso');
            })
            ->leftJoin('tbl_professor', 'tbl_feedbacks.id_professor', '=', 'tbl_professor.id_professor')
            // Curso e nível vêm da matrícula: um aluno pode ter várias (ex.: Inglês e Italiano),
            // e tbl_alunos.curso_aluno/nivel_aluno guardam um só.
            ->leftJoin('tbl_cursos', 'tbl_matricula.id_curso', '=', 'tbl_cursos.id_curso')
            ->leftJoin('tbl_niveis', 'tbl_matricula.id_nivel', '=', 'tbl_niveis.id_nivel')
            ->where('tbl_matricula.status_matricula', 'ATIVO')
            // Página pública: só os campos exibidos no card (nunca e-mail, telefone ou senha).
            ->select(
                'tbl_alunos.id_aluno',
                'tbl_alunos.nome_aluno',
                'tbl_alunos.foto_aluno',
                'tbl_alunos.curso_aluno',
                'tbl_alunos.nivel_aluno',
                'tbl_cursos.nome_curso',
                'tbl_niveis.nome_nivel',
                'tbl_alunos.status_aluno',
                'tbl_matricula.id_matricula',
                'tbl_matricula.data_matricula',
                'tbl_feedbacks.nota',
                'tbl_feedbacks.comentario',
                'tbl_professor.nome_professor',
            )
            ->get()
            ->each(function ($aluno) {
                $aluno->curso_aluno = $aluno->nome_curso ?? $aluno->curso_aluno;
                $aluno->nivel_aluno = $aluno->nome_nivel
                    ? self::NIVEIS[$aluno->nome_nivel] ?? ucfirst($aluno->nome_nivel)
                    : $aluno->nivel_aluno;
            });

        return view('site.sobre.sobre', compact('alunos'));
    }
}