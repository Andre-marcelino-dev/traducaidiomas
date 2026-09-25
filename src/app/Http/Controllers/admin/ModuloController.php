<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Nivel;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    private function dadosComuns(): array
    {
        return [
            'cursos'       => Curso::orderBy('nome_curso')->get(),
            'niveis'       => Nivel::orderBy('nome_nivel')->get(),
            'totalModulos' => Modulo::count(),
        ];
    }

    public function index()
    {
        $modulos = Modulo::with(['curso', 'nivel'])
            ->orderBy('id_curso')
            ->orderBy('id_nivel')
            ->orderBy('ordem_modulo')
            ->get();

        return view('admin.modulos.index', array_merge($this->dadosComuns(), [
            'modulos'    => $modulos,
            'moduloEdit' => null,
        ]));
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);

        $ordemDuplicada = Modulo::where('id_curso', $dados['id_curso'])
            ->where('id_nivel', $dados['id_nivel'])
            ->where('ordem_modulo', $dados['ordem_modulo'])
            ->exists();

        if ($ordemDuplicada) {
            return back()->withInput()->withErrors([
                'ordem_modulo' => 'Já existe um módulo com essa ordem para esse curso e nível.',
            ]);
        }

        Modulo::create($dados);

        return redirect()
            ->route('admin.modulos.index')
            ->with('success', 'Módulo cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $modulos = Modulo::with(['curso', 'nivel'])
            ->orderBy('id_curso')
            ->orderBy('id_nivel')
            ->orderBy('ordem_modulo')
            ->get();

        return view('admin.modulos.index', array_merge($this->dadosComuns(), [
            'modulos'    => $modulos,
            'moduloEdit' => Modulo::findOrFail($id),
        ]));
    }

    public function update(Request $request, $id)
    {
        $modulo = Modulo::findOrFail($id);
        $dados = $this->validar($request);

        $ordemDuplicada = Modulo::where('id_curso', $dados['id_curso'])
            ->where('id_nivel', $dados['id_nivel'])
            ->where('ordem_modulo', $dados['ordem_modulo'])
            ->where('id_modulo', '!=', $id)
            ->exists();

        if ($ordemDuplicada) {
            return back()->withInput()->withErrors([
                'ordem_modulo' => 'Já existe um módulo com essa ordem para esse curso e nível.',
            ]);
        }

        $modulo->update($dados);

        return redirect()
            ->route('admin.modulos.index')
            ->with('success', 'Módulo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Modulo::findOrFail($id)->delete();

        return redirect()
            ->route('admin.modulos.index')
            ->with('success', 'Módulo removido com sucesso!');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_curso'               => 'required|exists:tbl_cursos,id_curso',
            'id_nivel'               => 'required|exists:tbl_niveis,id_nivel',
            'ordem_modulo'           => 'required|integer|min:1',
            'nome_modulo'            => 'required|string|max:100',
            'descricao_modulo'       => 'nullable|string',
            'carga_horaria_minutos'  => 'required|integer|min:0',
            'status_modulo'          => 'required|in:ATIVO,INATIVO',
        ]);
    }
}
