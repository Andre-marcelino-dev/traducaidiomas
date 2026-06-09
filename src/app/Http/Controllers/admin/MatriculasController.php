<?php

namespace App\Http\Controllers\admin; // Mantido minúsculo conforme a sua pasta física

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
        // Carrega as matrículas com os relacionamentos de aluno e curso
        $matriculas = Matricula::with(['aluno', 'curso'])
            ->orderBy('data_matricula')
            ->orderBy('id_matricula')
            ->get();

        return view('admin.matriculas.matriculas', [
            'matriculas' => $matriculas,
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'cursos' => Curso::orderBy('nome_curso')->get(),
            'matriculaEdit' => null,
        ]);
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        Matricula::create($dados);

        return redirect()
            ->route('admin.alunos') // Redireciona para a sua rota padrão
            ->with('success', 'Matrícula cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $matriculas = Matricula::with(['aluno', 'curso'])
            ->orderBy('data_matricula')
            ->orderBy('id_matricula')
            ->get();

        return view('admin.matriculas.matriculas', [
            'matriculas' => $matriculas,
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'cursos' => Curso::orderBy('nome_curso')->get(),
            'matriculaEdit' => Matricula::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $dados = $this->validar($request);

        $matricula->update($dados);

        return redirect()
            ->route('admin.alunos')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Matricula::findOrFail($id)->delete();

        return redirect()
            ->route('admin.alunos')
            ->with('success', 'Matrícula removida com sucesso!');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_aluno' => 'required|integer',
            'id_curso' => 'required|integer',
            'id_nivel' => 'required|integer',
            'data_matricula' => 'required|date',
        ]);
    }
}