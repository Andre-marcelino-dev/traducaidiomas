<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matricula; // Alterado para o singular para alinhar com o arquivo do Model
use App\Models\Nivel;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        // Busca as matrículas trazendo os relacionamentos do aluno, curso e nível
        $matriculas = Matricula::with(['aluno', 'curso', 'nivel'])
            ->orderBy('data_matricula')
            ->orderBy('id_matricula')
            ->get();

        return view('admin.matriculas.matriculas', [
            'matriculas' => $matriculas,
            'alunos' => Aluno::orderBy('nome_aluno')->get(),
            'cursos' => Curso::orderBy('nome_curso')->get(),
            // 'niveis' => Nivel::orderBy('nome_nivel')->get(),
            'matriculaEdit' => null,
        ]);
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        // Cria a matrícula usando o Model corrigido no singular
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
            // 'niveis' => Nivel::orderBy('nome_nivel')->get(),
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
        // Validação tipada para garantir integridade antes de salvar no banco
        return $request->validate([
            'id_aluno' => 'required|integer',
            'id_curso' => 'required|integer',
            'id_nivel' => 'required|integer',
            'data_matricula' => 'required|date',
        ]);
    }
}