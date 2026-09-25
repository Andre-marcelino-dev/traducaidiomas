<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ModuloController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $modulos = Modulo::with(['curso', 'nivel'])
            ->when($request->filled('curso'), function ($query) use ($request) {
                $query->where('id_curso', $request->curso);
            })
            ->when($request->filled('nivel'), function ($query) use ($request) {
                $query->where('id_nivel', $request->nivel);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status_modulo', $request->status);
            })
            ->orderBy('id_curso')
            ->orderBy('id_nivel')
            ->orderBy('ordem_modulo')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $modulos
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $modulo = Modulo::with(['curso', 'nivel'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $modulo
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $this->validar($request);

        $this->verificarOrdemDuplicada($dados);

        $modulo = Modulo::create($dados);

        return response()->json([
            'success' => true,
            'message' => 'Módulo cadastrado com sucesso!',
            'data' => $modulo->load(['curso', 'nivel'])
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $modulo = Modulo::findOrFail($id);
        $dados = $this->validar($request);

        $this->verificarOrdemDuplicada($dados, $id);

        $modulo->update($dados);

        return response()->json([
            'success' => true,
            'message' => 'Módulo atualizado com sucesso!',
            'data' => $modulo->fresh()->load(['curso', 'nivel'])
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        Modulo::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Módulo removido com sucesso!'
        ]);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'id_curso'              => 'required|exists:tbl_cursos,id_curso',
            'id_nivel'              => 'required|exists:tbl_niveis,id_nivel',
            'ordem_modulo'          => 'required|integer|min:1',
            'nome_modulo'           => 'required|string|max:100',
            'descricao_modulo'      => 'nullable|string',
            'carga_horaria_minutos' => 'required|integer|min:0',
            'status_modulo'         => 'required|in:ATIVO,INATIVO',
        ]);
    }

    private function verificarOrdemDuplicada(array $dados, ?int $ignorarId = null): void
    {
        $ordemDuplicada = Modulo::where('id_curso', $dados['id_curso'])
            ->where('id_nivel', $dados['id_nivel'])
            ->where('ordem_modulo', $dados['ordem_modulo'])
            ->when($ignorarId, function ($query) use ($ignorarId) {
                $query->where('id_modulo', '!=', $ignorarId);
            })
            ->exists();

        if ($ordemDuplicada) {
            throw ValidationException::withMessages([
                'ordem_modulo' => 'Já existe um módulo com essa ordem para esse curso e nível.',
            ]);
        }
    }
}