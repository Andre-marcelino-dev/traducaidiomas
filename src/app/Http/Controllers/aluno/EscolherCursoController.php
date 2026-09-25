<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Support\CursoAtual;
use Illuminate\Http\Request;

class EscolherCursoController extends Controller
{
    public function index()
    {
        $aluno = auth('aluno')->user();

        $matriculas = Matricula::with(['curso', 'nivel'])
            ->where('id_aluno', $aluno->id_aluno)
            ->where('status_matricula', 'ATIVO')
            ->get();

        foreach ($matriculas as $mat) {
            $mat->total_modulos = Modulo::where('id_curso', $mat->id_curso)
                ->where('id_nivel', $mat->id_nivel)
                ->where('status_modulo', 'ATIVO')
                ->count();
        }

        $selecionada = session(CursoAtual::SESSAO);

        return view('aluno.curso.escolher', compact('aluno', 'matriculas', 'selecionada'));
    }

    public function selecionar(Request $request)
    {
        $request->validate(['id_matricula' => 'required|integer']);

        $matricula = Matricula::where('id_matricula', $request->id_matricula)
            ->where('id_aluno', auth('aluno')->id())
            ->where('status_matricula', 'ATIVO')
            ->first();

        if (!$matricula) {
            return redirect()->route('aluno.cursos.escolher')
                ->with('error', 'Matrícula não encontrada ou inativa.');
        }

        CursoAtual::definir($matricula);

        return redirect()->route('aluno.dash');
    }
}
