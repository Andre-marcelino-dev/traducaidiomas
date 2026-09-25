<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Professor;
use App\Models\Curso;
use App\Support\CursoAtual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        $professores = Professor::all();
        $cursos      = Curso::all();

        return view('admin.materiais.modal.create', compact('professores', 'cursos'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'id_professor'        => 'required|exists:tbl_professor,id_professor',
            'titulo_materiais'    => 'required|string|max:255',
            'descricao_materiais' => 'nullable|string',
            'arquivo_materiais'   => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
            'curso_materiais'     => 'nullable|string|max:255',
            'nivel_material'      => 'nullable|string|max:100',
            'id_curso'            => 'nullable|exists:tbl_cursos,id_curso',
        ]);

        if ($request->file('arquivo_materiais')) {
            $dados['arquivo_materiais'] = $request->file('arquivo_materiais')->store('materiais', 'public');
        } else {
            $dados['arquivo_materiais'] = null;
        }

        Materiais::create($dados);

        return redirect()->route('admin.materiais.index')
            ->with('success', 'Material criado com sucesso!');
    }

    public function show($id)
    {
        $materiais = Materiais::with(['professor', 'curso'])->findOrFail($id);

        return view('admin.materiais.modal.showaluno', compact('materiais'));
    }

    public function edit($id)
    {
        $materiais = Materiais::findOrFail($id);
        $professores = Professor::all();
        $cursos      = Curso::all();

        return view('admin.materiais.modal.edit', compact('materiais', 'professores', 'cursos'));
    }

    public function update(Request $request, $id)
    {
        $dados = $request->validate([
            'id_professor'        => 'required|exists:tbl_professor,id_professor',
            'titulo_materiais'    => 'required|string|max:255',
            'descricao_materiais' => 'nullable|string',
            'arquivo_materiais'   => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
            'curso_materiais'     => 'nullable|string|max:255',
            'nivel_material'      => 'nullable|string|max:100',
            'id_curso'            => 'nullable|exists:tbl_cursos,id_curso',
        ]);

        $materiais = Materiais::findOrFail($id);

        if ($request->file('arquivo_materiais')) {
            if ($materiais->arquivo_materiais) {
                Storage::disk('public')->delete($materiais->arquivo_materiais);
            }
            $dados['arquivo_materiais'] = $request->file('arquivo_materiais')->store('materiais', 'public');
        } else {
            $dados['arquivo_materiais'] = $materiais->arquivo_materiais;
        }

        $materiais->update($dados);

        return redirect()->route('admin.materiais.index')
            ->with('success', 'Material actualizado com sucesso!');
    }

    public function destroy($id)
    {
        $materiais = Materiais::findOrFail($id);

        if ($materiais->arquivo_materiais) {
            Storage::disk('public')->delete($materiais->arquivo_materiais);
        }

        $materiais->delete();

        return redirect()->route('admin.materiais.index')
            ->with('success', 'Material removido com sucesso!');
    }


    public function verArquivo($id)
    {
        $material = Materiais::findOrFail($id);

        $caminho = base_path($material->arquivo_materiais);

        if ($material->arquivo_materiais && file_exists($caminho)) {
            return response()->file($caminho);
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
    }

    public function download($id)
    {
        $material = Materiais::findOrFail($id);

        $caminho = base_path($material->arquivo_materiais);

        if ($material->arquivo_materiais && file_exists($caminho)) {
            $ext = pathinfo($caminho, PATHINFO_EXTENSION);
            return response()->download($caminho, $material->titulo_materiais . '.' . $ext);
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
    }
}
