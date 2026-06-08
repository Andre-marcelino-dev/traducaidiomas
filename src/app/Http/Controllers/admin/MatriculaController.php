<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nivel;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['aluno', 'curso', 'nivel'])
            ->orderBy('data_matricula')
            ->orderBy('id_matricula')
            ->get();

        return view('admin.matriculas.matriculas', [
            'matriculas' => $matriculas,
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'cursos' => Curso::orderBy('nome_curso')->get(),
            'niveis' => Nivel::orderBy('nome_nivel')->get(),
            'matriculaEdit' => null,
        ]);
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        Matricula::create($dados);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $matriculas = Matricula::with(['aluno', 'curso', 'nivel'])
            ->orderBy('data_matricula')
            ->orderBy('id_matricula')
            ->get();

        return view('admin.matriculas.matriculas', [
            'matriculas' => $matriculas,
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'cursos' => Curso::orderBy('nome_curso')->get(),
            'niveis' => Nivel::orderBy('nome_nivel')->get(),
            'matriculaEdit' => Matricula::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $dados = $this->validar($request);

        $matricula->update($dados);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Matricula::findOrFail($id)->delete();

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula removida com sucesso!');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_aluno' => 'required|exists:tbl_alunos,id_aluno',
            'id_curso' => 'required|exists:tbl_cursos,id_curso',
            'id_nivel' => 'required|exists:tbl_niveis,id_nivel',
            'data_matricula' => 'required|date',
        ]);
    }
}
