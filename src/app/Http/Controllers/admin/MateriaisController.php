<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Materiais;
use App\Models\Professor;
use App\Models\Curso;
use Illuminate\Http\Request;

class MateriaisController extends Controller
{
    public function index()
    {
        $materiais = Materiais::with(['professor', 'curso'])->latest('criado_em_materiais')->paginate(15);

        return view('admin.materiais.index', compact('materiais'));
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
            'descricao_materiais' => 'required|string',
            'arquivo_materiais'   => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
            'curso_materiais'     => 'nullable|string|max:255',
            'nivel_material'      => 'required|string|max:100',
            'id_curso'            => 'required|exists:tbl_cursos,id_curso',
        ]);

        if ($request->hasFile('arquivo_materiais')) {
            $arquivo     = $request->file('arquivo_materiais');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $destino     = public_path('traducaidiomas/materiais');

            if (!file_exists($destino)) {
                mkdir($destino, 0755, true);
            }

            $arquivo->move($destino, $nomeArquivo);
            $dados['arquivo_materiais'] = 'traducaidiomas/materiais/' . $nomeArquivo;
        } else {
            $dados['arquivo_materiais'] = '';
        }

        Materiais::create($dados);

        return redirect()->route('admin.materiais.index')
            ->with('success', 'Material criado com sucesso!');
    }

    public function show($id)
    {
        $materiais = Materiais::with(['professor', 'curso'])->findOrFail($id);

        return view('admin.materiais.modal.show', compact('materiais'));
    }

    public function edit($id)
    {
        $materiais   = Materiais::findOrFail($id);
        $professores = Professor::all();
        $cursos      = Curso::all();

        return view('admin.materiais.modal.edit', compact('materiais', 'professores', 'cursos'));
    }

    public function update(Request $request, $id)
    {
        $dados = $request->validate([
            'id_professor'        => 'required|exists:tbl_professor,id_professor',
            'titulo_materiais'    => 'required|string|max:255',
            'descricao_materiais' => 'required|string',
            'arquivo_materiais'   => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
            'curso_materiais'     => 'nullable|string|max:255',
            'nivel_material'      => 'required|string|max:100',
            'id_curso'            => 'required|exists:tbl_cursos,id_curso',
        ]);

        $materiais = Materiais::findOrFail($id);

        if ($request->hasFile('arquivo_materiais')) {
            if ($materiais->arquivo_materiais && file_exists(public_path($materiais->arquivo_materiais))) {
                unlink(public_path($materiais->arquivo_materiais));
            }

            $arquivo     = $request->file('arquivo_materiais');
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $destino     = public_path('traducaidiomas/materiais');

            if (!file_exists($destino)) {
                mkdir($destino, 0755, true);
            }

            $arquivo->move($destino, $nomeArquivo);
            $dados['arquivo_materiais'] = 'traducaidiomas/materiais/' . $nomeArquivo;
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

        if ($materiais->arquivo_materiais && file_exists(public_path($materiais->arquivo_materiais))) {
            unlink(public_path($materiais->arquivo_materiais));
        }

        $materiais->delete();

        return redirect()->route('admin.materiais.index')
            ->with('success', 'Material removido com sucesso!');
    }

    public function download($id)
    {
        $material = Materiais::findOrFail($id);

        if ($material->arquivo_materiais && file_exists(public_path($material->arquivo_materiais))) {
            return response()->download(public_path($material->arquivo_materiais));
        }

        return redirect()->back()->with('error', 'Arquivo não encontrado no servidor.');
    }
}
