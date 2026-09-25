<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Support\ModuloProgresso;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $matriculas = Matricula::with(['curso', 'nivel'])
            ->where('id_aluno', $request->user()->id_aluno)
            ->where('status_matricula', 'ATIVO')
            ->get()
            ->map(fn ($m) => [
                'id_curso'   => $m->id_curso,
                'nome_curso' => $m->curso?->nome_curso,
                'id_nivel'   => $m->id_nivel,
                'nome_nivel' => $m->nivel?->nome_nivel,
            ]);

        return response()->json(['success' => true, 'data' => $matriculas]);
    }

    public function modulos(Request $request, int $idCurso): JsonResponse
    {
        $matricula = Matricula::with(['curso', 'nivel'])
            ->where('id_aluno', $request->user()->id_aluno)
            ->where('id_curso', $idCurso)
            ->where('status_matricula', 'ATIVO')
            ->first();

        if (!$matricula) {
            return response()->json([
                'success' => false,
                'message' => 'Matrícula não encontrada para este curso.',
            ], 404);
        }

        $modulos = ModuloProgresso::paraMatricula($matricula);

        return response()->json([
            'success' => true,
            'data' => [
                'curso' => $matricula->curso?->nome_curso,
                'nivel' => $matricula->nivel?->nome_nivel,
                'carga_horaria_minutos' => $modulos->sum('carga_horaria_minutos'),
                'total_aulas' => $modulos->sum('aulas_count'),
                'percentual_geral' => ModuloProgresso::percentualGeral($modulos),
                'modulos' => $modulos->map(fn ($m) => [
                    'id_modulo'              => $m->id_modulo,
                    'ordem_modulo'           => $m->ordem_modulo,
                    'nome_modulo'            => $m->nome_modulo,
                    'descricao_modulo'       => $m->descricao_modulo,
                    'carga_horaria_minutos'  => $m->carga_horaria_minutos,
                    'total_aulas'            => $m->aulas_count,
                    'total_materiais'        => $m->materiais_count,
                    'materiais_concluidos'   => $m->materiais_concluidos,
                    'percentual'             => $m->percentual,
                    'concluido'              => $m->concluido,
                    'liberado'               => $m->liberado,
                    'em_andamento'           => $m->em_andamento,
                ])->values(),
            ],
        ]);
    }
}
