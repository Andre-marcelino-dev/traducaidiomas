<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Materiais;
use App\Models\Modulo;
use App\Support\CursoAtual;
use Illuminate\Http\Request;

class MateriaisController extends Controller
{
    public function index(Request $request)
    {
        $matriculaAtual = CursoAtual::matricula();

        $query = CursoAtual::filtrar(Materiais::with(['professor', 'curso', 'modulo']), $matriculaAtual);

        if ($request->filled('nivel')) {
            $query->where('nivel_material', $request->nivel);
        }
        if ($request->filled('id_curso')) {
            $query->where('id_curso', $request->id_curso);
        }
        if ($request->filled('id_modulo')) {
            $query->where('id_modulo', $request->id_modulo);
        }
        if ($request->filled('busca')) {
            $query->where('titulo_materiais', 'like', '%' . $request->busca . '%');
        }

        $materiais = $query->latest('criado_em_materiais')->paginate(12);

        $modulos = Modulo::with('curso')
            ->where('id_curso', $matriculaAtual->id_curso)
            ->where('id_nivel', $matriculaAtual->id_nivel)
            ->orderBy('ordem_modulo')
            ->get();

        return view('admin.materiais.alunoindex', compact('materiais', 'modulos'));
    }

    public function show($id)
    {
        $materiais = $this->materialDoAluno($id, ['professor', 'curso']);

        return view('admin.materiais.modal.showaluno', compact('materiais'));
    }

    public function verArquivo($id)
    {
        $material = $this->materialDoAluno($id);
        $caminho = public_path($material->arquivo_materiais);

        if ($material->arquivo_materiais && file_exists($caminho)) {
            return response()->file($caminho);
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
    }

    public function download($id)
    {
        $material = $this->materialDoAluno($id);
        $caminho = public_path($material->arquivo_materiais);

        if ($material->arquivo_materiais && file_exists($caminho)) {
            $ext = pathinfo($caminho, PATHINFO_EXTENSION);
            return response()->download($caminho, $material->titulo_materiais . '.' . $ext);
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
    }

    /**
     * Só devolve o material se ele for do curso/nível que o aluno escolheu;
     * senão 404 (o aluno não pode abrir material de outro curso trocando o id na URL).
     */
    private function materialDoAluno($id, array $with = []): Materiais
    {
        return CursoAtual::filtrar(Materiais::with($with), CursoAtual::matricula())
            ->findOrFail($id);
    }
}
