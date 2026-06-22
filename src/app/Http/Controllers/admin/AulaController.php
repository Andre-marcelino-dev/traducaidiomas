<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Professor;
use App\Models\Curso;
use Illuminate\Http\Request;

class AulaController extends Controller
{
public function index()
{
    $aulas = Aula::with('professor')
        ->orderBy('data_aulas', 'desc')
        ->orderBy('hora_aulas', 'desc')
        ->get();

    $cursos = Curso::all();

    $totalCursos = Curso::count();
    $totalAulas = $aulas->count();
    $aulasAtivas = $aulas->where('status_aulas', 'ATIVO')->count();
    $aulasHoje = $aulas->filter(fn($a) => $a->data_aulas === now()->toDateString())->count();

    return view('admin.aulas.index', compact(
        'aulas',
        'cursos',
        'totalCursos',
        'totalAulas',
        'aulasAtivas',
        'aulasHoje'
    ));
}

   public function create()
{
    $professores = Professor::orderBy('nome_professor')->get();
    $cursos = Curso::orderBy('nome_curso')->get();

   return view('admin.aulas.modal.create', compact('professores', 'cursos'));
}

public function store(Request $request)
{
    $request->validate([
        'titulo_aulas'    => 'required|string|max:100',
        'descricao_aulas' => 'required|string',
        'data_aulas'      => 'required|date',
        'hora_aulas'      => 'required',
        'id_professor'    => 'required|exists:tbl_professor,id_professor',
        'id_curso'        => 'required|exists:tbl_cursos,id_curso',
        'link_teams'      => 'nullable|url|max:500',
        'cursos_aulas'    => 'required|string|max:100',
        'status_aulas'    => 'required|in:ATIVO,INATIVO,CANCELADO',
    ]);

    Aula::create($request->only([
        'titulo_aulas', 'descricao_aulas', 'data_aulas', 'hora_aulas',
        'id_professor', 'id_curso', 'link_teams', 'cursos_aulas', 'status_aulas',
    ]));

    return redirect()
        ->route('admin.aulas.index')
        ->with('success', 'Aula cadastrada com sucesso!');
}

  public function edit($id)
{
    $aula = Aula::findOrFail($id);
    $professores = Professor::orderBy('nome_professor')->get();
    $cursos = Curso::orderBy('nome_curso')->get();

    return view('admin.aulas.modal.edit', compact(
        'aula',
        'professores',
        'cursos'
    ));
}

    public function update(Request $request, $id)
    {
       $aula = Aula::findOrFail($id);

    $request->validate([
        'titulo_aulas'    => 'required|string|max:100',
        'descricao_aulas' => 'required|string',
        'data_aulas'      => 'required|date',
        'hora_aulas'      => 'required',
        'id_professor'    => 'required|exists:tbl_professor,id_professor',
        'id_curso' => 'required|exists:tbl_cursos,id_curso',
        'link_teams'      => 'nullable|url|max:500',
        'cursos_aulas'    => 'required|string|max:100',
        'status_aulas'    => 'required|in:ATIVO,INATIVO,CANCELADO',
    ]);

    $aula->update($request->only([
        'titulo_aulas', 'descricao_aulas', 'data_aulas', 'hora_aulas',
        'id_professor', 'id_curso', 'link_teams', 'cursos_aulas', 'status_aulas',
    ]));

    return redirect()
        ->route('admin.aulas.index')
        ->with('success', 'Aula atualizada com sucesso!');
}

    public function destroy($id)
    {
        Aula::findOrFail($id)->delete();
        return redirect()->route('admin.aulas.index')->with('success', 'Aula removida com sucesso!');
    }
}
