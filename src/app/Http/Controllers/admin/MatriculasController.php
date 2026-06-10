<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Matriculas;
use App\Models\Nivel;
use Illuminate\Http\Request;

class MatriculasController extends Controller
{
    private function dadosComuns(): array
    {
        return [
            'alunos'           => Aluno::orderBy('nome_aluno')->get(),
            'cursos'           => Curso::orderBy('nome_curso')->get(),
            'niveis'           => Nivel::orderBy('nome_nivel')->get(),
            'totalMatriculas'  => Matriculas::count(),
            'matriculasHoje'   => Matriculas::whereDate('data_matricula', now())->count(),
            'alunosAtivos'  => Aluno::where('status_aluno', 'EM CURSO')->count(),
            'alunosInativos' => Aluno::where('status_aluno', 'INATIVO')->count(),
        ];
    }

    public function index()
    {
        $matriculas = Matriculas::with(['aluno', 'curso', 'nivel'])
            ->orderBy('data_matricula', 'desc')
            ->get();

        return view('admin.matriculas.index', array_merge($this->dadosComuns(), [
            'matriculas'    => $matriculas,
            'matriculaEdit' => null,
        ]));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        Matriculas::create($dados);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $matriculas = Matriculas::with(['aluno', 'curso', 'nivel'])
            ->orderBy('data_matricula', 'desc')
            ->get();

        return view('admin.matriculas.index', array_merge($this->dadosComuns(), [
            'matriculas'    => $matriculas,
            'matriculaEdit' => Matriculas::findOrFail($id),
        ]));
    }

    public function update(Request $request, $id)
    {
        $matricula = Matriculas::findOrFail($id);
        $dados = $this->validar($request);

        $matricula->update($dados);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function destroy($id)
    {
        Matriculas::findOrFail($id)->delete();

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula removida com sucesso!');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_aluno'       => 'required|integer',
            'id_curso'       => 'required|integer',
            'id_nivel'       => 'required|integer',
            'data_matricula' => 'required|date',
        ]);
    }
}