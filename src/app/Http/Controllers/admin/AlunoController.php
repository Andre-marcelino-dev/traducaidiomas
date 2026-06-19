<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Aluno;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function index()
    {
        $alunos = Aluno::all();
        return view('admin.alunos.index', compact('alunos'));
    }

    public function create()
    {
        return view('admin.alunos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome_aluno'               => 'required|string|max:255',
            'email_aluno'              => 'required|email|unique:tbl_alunos,email_aluno',
            'senha_aluno'              => 'required|string|min:6|confirmed',
            'telefone_aluno'           => 'required|string|max:20',
            'curso_aluno'              => 'required|string|max:100',
            'data_nasc_aluno'          => 'required|date',
            'nivel_aluno'              => 'required|string|max:50',
            'status_aluno'             => 'required|string|max:50',
            'foto_aluno'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nome_aluno', 'email_aluno', 'telefone_aluno',
            'curso_aluno', 'data_nasc_aluno', 'nivel_aluno', 'status_aluno',
        ]);
        $data['senha_aluno'] = bcrypt($request->senha_aluno);

        if ($request->hasFile('foto_aluno')) {
            $foto = $request->file('foto_aluno');
            $nome = strtolower(str_replace(' ', '-', $request->nome_aluno)) . '.' . $foto->getClientOriginalExtension();
            $destino = public_path('traducaidiomas/alunos');

            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }

            $foto->move($destino, $nome);
            $data['foto_aluno'] = $nome;
        } else {
            $data['foto_aluno'] = '';
        }

        Aluno::create($data);

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $aluno = Aluno::findOrFail($id);
        return view('admin.alunos.edit', compact('aluno'));
    }

    public function update(Request $request, $id)
    {
        $aluno = Aluno::findOrFail($id);

        $request->validate([
            'nome_aluno'      => 'required|string|max:255',
            'email_aluno'     => 'required|email|unique:tbl_alunos,email_aluno,' . $id . ',id_aluno',
            'senha_aluno'     => 'nullable|string|min:6|confirmed',
            'telefone_aluno'  => 'required|string|max:20',
            'curso_aluno'     => 'required|string|max:100',
            'data_nasc_aluno' => 'required|date',
            'nivel_aluno'     => 'required|string|max:50',
            'status_aluno'    => 'required|string|max:50',
            'foto_aluno'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nome_aluno', 'email_aluno', 'telefone_aluno',
            'curso_aluno', 'data_nasc_aluno', 'nivel_aluno', 'status_aluno',
        ]);

        if ($request->filled('senha_aluno')) {
            $data['senha_aluno'] = bcrypt($request->senha_aluno);
        }

        if ($request->hasFile('foto_aluno')) {
            $foto = $request->file('foto_aluno');
            $nome = strtolower(str_replace(' ', '-', $request->nome_aluno)) . '.' . $foto->getClientOriginalExtension();
            $destino = public_path('traducaidiomas/alunos');

            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }

            $foto->move($destino, $nome);
            $data['foto_aluno'] = $nome;
        }

        $aluno->update($data);

        return redirect()->route('admin.alunos.index')->with('success', 'Aluno atualizado com sucesso!');
    }



    public function updateStatus(Request $request, $id)
{
    $aluno = Aluno::findOrFail($id);
    $aluno->status_aluno = $request->status_aluno;
    $aluno->save();

    return back()->with('success', 'Status atualizado!');
}

    public function destroy($id)
    {
        Aluno::findOrFail($id)->delete();
        return redirect()->route('admin.alunos.index')->with('success', 'Aluno excluído com sucesso!');
    }
}
