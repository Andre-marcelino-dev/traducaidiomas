<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AulaController extends Controller
{
    private array $regras = [
        'titulo_aulas'    => 'required|string|max:100',
        'descricao_aulas' => 'required|string',
        'data_aulas'      => 'required|date',
        'hora_aulas'      => 'required',
        'id_professor'    => 'required|exists:tbl_professor,id_professor',
        'id_curso'        => 'required|exists:tbl_cursos,id_curso',
        'link_teams'      => 'nullable|url|max:500',
        'cursos_aulas'    => 'required|string|max:100',
        'status_aulas'    => 'required|in:ATIVO,INATIVO,CANCELADO',
    ];

    private array $campos = [
        'titulo_aulas', 'descricao_aulas', 'data_aulas', 'hora_aulas',
        'id_professor', 'id_curso', 'link_teams', 'cursos_aulas', 'status_aulas',
    ];

    public function index(Request $request): JsonResponse
    {
        $aulas = Aula::with('professor:id_professor,nome_professor')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status_aulas', $request->status);
            })
            ->when($request->filled('curso'), function ($query) use ($request) {
                $query->where('id_curso', $request->curso);
            })
            ->when($request->filled('data'), function ($query) use ($request) {
                $query->whereDate('data_aulas', $request->data);
            })
            ->orderBy('data_aulas', 'desc')
            ->orderBy('hora_aulas', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $aulas
        ]);
    }

    public function resumo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_aulas'  => Aula::count(),
                'aulas_ativas' => Aula::where('status_aulas', 'ATIVO')->count(),
                'aulas_hoje'   => Aula::whereDate('data_aulas', now()->toDateString())->count(),
            ]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $aula = Aula::with('professor:id_professor,nome_professor')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $aula
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate($this->regras);

        $aula = Aula::create($request->only($this->campos));

        return response()->json([
            'success' => true,
            'message' => 'Aula cadastrada com sucesso!',
            'data' => $aula->load('professor:id_professor,nome_professor')
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $aula = Aula::findOrFail($id);

        $request->validate($this->regras);

        $aula->update($request->only($this->campos));

        return response()->json([
            'success' => true,
            'message' => 'Aula atualizada com sucesso!',
            'data' => $aula->fresh()->load('professor:id_professor,nome_professor')
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        Aula::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aula removida com sucesso!'
        ]);
    }
}