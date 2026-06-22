<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
public function index()
{
    $professores      = Professor::all();
    $totalCursos      = $professores->pluck('curso_professor')->unique()->filter()->count();
    $mediaExperiencia = $professores->avg(fn($p) => (int) filter_var($p->experiencia_professor, FILTER_SANITIZE_NUMBER_INT)) ?? 0;

    $alunos         = \App\Models\Aluno::all();
    $totalAlunos    = $alunos->count();
    $iniciantes     = $alunos->where('nivel_aluno', 'Iniciante')->count();
    $intermediarios = $alunos->where('nivel_aluno', 'Intermediário')->count();
    $avancados      = $alunos->where('nivel_aluno', 'Avançado')->count();

    return view('admin.professores.index', compact(
        'professores', 'totalCursos', 'mediaExperiencia',
        'totalAlunos', 'iniciantes', 'intermediarios', 'avancados'
    ));
}

    public function create()
    {
        return view('admin.professores.create');
    }

public function store(Request $request)
{
    $request->validate([
        'nome_professor'          => 'required|string|max:100',
        'especialidade_professor' => 'required|string|max:100',
        'experiencia_professor'   => 'required|string|max:50',
        'bio_professor'           => 'required|string',
        'foto_professor'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'email_professor'         => 'required|email|max:100|unique:tbl_professor,email_professor',
        'curso_professor'         => 'required|string|max:50',
        'nivel_professor'         => 'required|string|max:20',
        'telefone_professor'      => 'required|string|max:14',
        'senha_professor'         => 'required|string|min:6|confirmed',
    ]);

    $dados = $request->except(['foto_professor', '_token', 'senha_professor_confirmation']);
    $dados['senha_professor'] = Hash::make($request->senha_professor);

    if ($request->hasFile('foto_professor')) {
        $arquivo = $request->file('foto_professor');
        $nomeFoto = time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
        $diretorioDestino = public_path('traducaidiomas/professor/');

        if (!file_exists($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }

        $arquivo->move($diretorioDestino, $nomeFoto);
        $dados['foto_professor'] = $nomeFoto;
    } else {
        $dados['foto_professor'] = '';
    }

    Professor::create($dados);

return redirect()
    ->route('admin.professores.index')
    ->with('success', 'Professor cadastrado com sucesso!');  
}
    public function show($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return redirect()->route('admin.professores.index')
                ->with('error', 'Professor não encontrado.');
        }

        return view('admin.professores.show', compact('professor'));
    }

    public function edit($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return redirect()->route('admin.professores.index')
                ->with('error', 'Professor não encontrado.');
        }

        return view('admin.professores.edit', compact('professor'));
    }

    public function update(Request $request, $id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return redirect()->route('admin.professores.index')
                ->with('error', 'Professor não encontrado.');
        }

        $request->validate([
            'nome_professor'           => 'required|string|max:100',
            'especialidade_professor'  => 'required|string|max:100',
            'experiencia_professor'    => 'required|string|max:50',
            'bio_professor'            => 'required|string',
            'foto_professor'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'email_professor'          => 'required|email|max:100|unique:tbl_professor,email_professor,' . $professor->id_professor . ',id_professor',
            'curso_professor'          => 'required|string|max:50',
            'nivel_professor'          => 'required|string|max:20',
            'telefone_professor'       => 'required|string|max:14',
            'senha_professor' => 'nullable|string|min:6|confirmed',
        ]);

        $dados = $request->except(['foto_professor', 'senha_professor']);

        if ($request->filled('senha_professor')) {
            $dados['senha_professor'] = Hash::make($request->senha_professor);
        }

        if ($request->hasFile('foto_professor')) {
            $fotoAntiga = public_path('traducaidiomas/professor/' . $professor->foto_professor);
            if ($professor->foto_professor && file_exists($fotoAntiga)) {
                @unlink($fotoAntiga);
            }

            $arquivo = $request->file('foto_professor');
            $nomeFoto = time() . '_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
            $diretorioDestino = public_path('traducaidiomas/professor/');

            if (!file_exists($diretorioDestino)) {
                @mkdir($diretorioDestino, 0777, true);
            }
                // dd($request->file('foto_professor'));

           $arquivo->move($diretorioDestino, $nomeFoto);
            $dados['foto_professor'] = $nomeFoto;
        }

        $professor->update($dados);

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return redirect()->route('admin.professores.index')
                ->with('error', 'Professor não encontrado.');
        }

        $fotoPath = public_path('traducaidiomas/professor/' . $professor->foto_professor);
        if ($professor->foto_professor && file_exists($fotoPath)) {
            @unlink($fotoPath);
        }

        $professor->delete();

        return redirect()->route('admin.professores.index')
            ->with('success', 'Professor removido com sucesso!');
    }
}
