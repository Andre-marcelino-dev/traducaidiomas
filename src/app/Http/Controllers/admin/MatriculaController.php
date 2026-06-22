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
    private function dadosComuns(): array
    {
        return [
            'alunos'           => Aluno::orderBy('nome_aluno')->get(),
            'cursos'           => Curso::orderBy('nome_curso')->get(),
            'niveis'           => Nivel::orderBy('nome_nivel')->get(),
            'totalMatriculas'  => Matricula::count(),
            'matriculasHoje'   => Matricula::whereDate('data_matricula', now())->count(),
            'alunosAtivos'  => Aluno::where('status_aluno', 'EM CURSO')->count(),
            'alunosInativos' => Aluno::where('status_aluno', 'INATIVO')->count(),
            'matriculasCongeladas' => Matricula::where('status_matricula', 'CONGELADO')->count(),
        ];
    }

    public function index()
    {
        $matriculas = Matricula::with(['aluno', 'curso', 'nivel'])
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

        $jaMatriculado = Matricula::where('id_aluno', $dados['id_aluno'])
            ->where('id_curso', $dados['id_curso'])
            ->exists();

        if ($jaMatriculado) {
            return back()->withInput()->withErrors([
                'id_aluno' => 'Este aluno já possui matrícula neste curso.',
            ]);
        }

        Matricula::create($dados);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula cadastrada com sucesso!');
    }

    public function edit($id)
    {
        $matriculas = Matricula::with(['aluno', 'curso', 'nivel'])
            ->orderBy('data_matricula', 'desc')
            ->get();

        return view('admin.matriculas.index', array_merge($this->dadosComuns(), [
            'matriculas'    => $matriculas,
            'matriculaEdit' => Matricula::findOrFail($id),
        ]));
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $dados = $this->validar($request);

        $jaMatriculado = Matricula::where('id_aluno', $dados['id_aluno'])
            ->where('id_curso', $dados['id_curso'])
            ->where('id_matricula', '!=', $id)
            ->exists();

        if ($jaMatriculado) {
            return back()->withInput()->withErrors([
                'id_aluno' => 'Este aluno já possui matrícula neste curso.',
            ]);
        }

        if ($request->has('status_matricula')) {
            $dados['status_matricula'] = $request->validate([
                'status_matricula' => 'required|in:ATIVO,CONGELADO,CANCELADO',
            ])['status_matricula'];
        }

        $matricula->update($dados);

        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Matrícula atualizada com sucesso!');
    }

    public function updateStatus(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->update(['status_matricula' => $request->status_matricula]);
        return redirect()
            ->route('admin.matriculas.index')
            ->with('success', 'Status atualizado com sucesso!');
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
            'id_aluno'       => 'required|integer',
            'id_curso'       => 'required|integer',
            'id_nivel'       => 'required|integer',
            'data_matricula' => 'required|date',
        ]);
    }
}
