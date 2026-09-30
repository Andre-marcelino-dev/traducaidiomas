<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Presenca;
use App\Support\CursoAtual;
use App\Support\ModuloProgresso;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Tela "Curso": carga horária, progresso geral e lista de módulos.
     */
    public function modulos(Request $request, int $idCurso): JsonResponse
    {
        $matricula = $this->matriculaNoCurso($request, $idCurso);

        if (!$matricula) {
            return $this->naoEncontrado('Matrícula não encontrada para este curso.');
        }

        $modulos = ModuloProgresso::paraMatricula($matricula);

        return response()->json([
            'success' => true,
            'data' => [
                'curso' => $matricula->curso?->nome_curso,
                'nivel' => $matricula->nivel?->nome_nivel,
                'carga_horaria_minutos' => $modulos->sum('carga_horaria_minutos'),
                'total_modulos' => $modulos->count(),
                'total_aulas' => $modulos->sum('aulas_count'),
                'aulas_concluidas' => $modulos->sum('aulas_concluidas'),
                'percentual_geral' => ModuloProgresso::percentualGeral($modulos),
                'modulos' => $modulos->map(fn ($m) => [
                    'id_modulo'              => $m->id_modulo,
                    'ordem_modulo'           => $m->ordem_modulo,
                    'nome_modulo'            => $m->nome_modulo,
                    'descricao_modulo'       => $m->descricao_modulo,
                    'carga_horaria_minutos'  => $m->carga_horaria_minutos,
                    'total_aulas'            => $m->aulas_count,
                    'aulas_concluidas'       => $m->aulas_concluidas,
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

    /**
     * Tela "Módulo": cabeçalho, progresso e lista de aulas do módulo.
     */
    public function modulo(Request $request, int $idModulo): JsonResponse
    {
        $modulo = Modulo::with(['curso', 'nivel'])
            ->where('status_modulo', 'ATIVO')
            ->find($idModulo);

        $matricula = $modulo ? Matricula::with(['curso', 'nivel'])
            ->where('id_aluno', $request->user()->id_aluno)
            ->where('id_curso', $modulo->id_curso)
            ->where('id_nivel', $modulo->id_nivel)
            ->where('status_matricula', 'ATIVO')
            ->first() : null;

        if (!$matricula) {
            return $this->naoEncontrado('Módulo não encontrado.');
        }

        $modulos = ModuloProgresso::paraMatricula($matricula)->values();
        $posicao = $modulos->search(fn ($m) => (int) $m->id_modulo === (int) $modulo->id_modulo);
        $progresso = $modulos[$posicao];
        $proximo = $modulos[$posicao + 1] ?? null;

        if (!$progresso->liberado) {
            return response()->json([
                'success' => false,
                'message' => 'Conclua o módulo anterior para liberar este.',
            ], Response::HTTP_FORBIDDEN);
        }

        $aulas = Aula::with('professor:id_professor,nome_professor')
            ->where('id_modulo', $modulo->id_modulo)
            ->where('status_aulas', 'ATIVO')
            ->orderByRaw('ordem_aula IS NULL')
            ->orderBy('ordem_aula')
            ->orderBy('data_aulas')
            ->orderBy('hora_aulas')
            ->get();

        $presencas = Presenca::where('id_aluno', $matricula->id_aluno)
            ->whereIn('id_aulas', $aulas->pluck('id_aulas'))
            ->get()
            ->keyBy('id_aulas');

        $listaAulas = $aulas->values()->map(function (Aula $aula, int $i) use ($presencas) {
            $presenca = $presencas[$aula->id_aulas] ?? null;
            $status = $presenca ? strtolower($presenca->status_presenca) : null;

            return [
                'id_aula'          => $aula->id_aulas,
                'numero'           => $aula->ordem_aula ?? $i + 1,
                'titulo'           => $aula->titulo_aulas,
                'descricao'        => $aula->descricao_aulas,
                'data'             => $aula->data_aulas ? substr((string) $aula->data_aulas, 0, 10) : null,
                'hora'             => $aula->hora_aulas ? substr((string) $aula->hora_aulas, 0, 5) : null,
                'duracao_minutos'  => $aula->duracao_minutos,
                'ao_vivo'          => (bool) $aula->link_teams,
                'link_aula'        => $aula->link_teams,
                'professor'        => $aula->professor?->nome_professor,
                'presenca'         => $status,
                'concluida'        => in_array($status, ModuloProgresso::PRESENCA_CONCLUI, true),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'curso'  => $matricula->curso?->nome_curso,
                'nivel'  => $matricula->nivel?->nome_nivel,
                'modulo' => [
                    'id_modulo'             => $modulo->id_modulo,
                    'ordem_modulo'          => $modulo->ordem_modulo,
                    'nome_modulo'           => $modulo->nome_modulo,
                    'descricao_modulo'      => $modulo->descricao_modulo,
                    'carga_horaria_minutos' => $progresso->carga_horaria_minutos,
                ],
                'progresso' => [
                    'total_aulas'          => $listaAulas->count(),
                    'aulas_concluidas'     => $listaAulas->where('concluida', true)->count(),
                    'total_materiais'      => $progresso->materiais_count,
                    'materiais_concluidos' => $progresso->materiais_concluidos,
                    'percentual'           => $progresso->percentual,
                    'concluido'            => $progresso->concluido,
                ],
                'proximo_modulo' => $proximo ? [
                    'id_modulo'   => $proximo->id_modulo,
                    'nome_modulo' => $proximo->nome_modulo,
                    'liberado'    => $proximo->liberado,
                ] : null,
                'aulas' => $listaAulas,
            ],
        ]);
    }

    /**
     * Aba "Materiais" da tela Curso. Filtro opcional: ?modulo=ID
     */
    public function materiais(Request $request, int $idCurso): JsonResponse
    {
        $matricula = $this->matriculaNoCurso($request, $idCurso);

        if (!$matricula) {
            return $this->naoEncontrado('Matrícula não encontrada para este curso.');
        }

        $materiais = CursoAtual::filtrar(Materiais::with('modulo'), $matricula)
            ->when($request->filled('modulo'), fn ($q) => $q->where('id_modulo', $request->integer('modulo')))
            ->orderBy('id_materiais')
            ->get()
            ->sortBy(fn ($m) => [$m->modulo?->ordem_modulo ?? PHP_INT_MAX, $m->id_materiais])
            ->values();

        $concluidos = ModuloProgresso::materiaisConcluidos($matricula->id_aluno, $materiais->pluck('id_materiais'));

        return response()->json([
            'success' => true,
            'data' => $materiais->map(fn (Materiais $m) => [
                'id_material'  => $m->id_materiais,
                'titulo'       => $m->titulo_materiais,
                'descricao'    => $m->descricao_materiais,
                'id_modulo'    => $m->id_modulo,
                'nome_modulo'  => $m->modulo?->nome_modulo,
                'ordem_modulo' => $m->modulo?->ordem_modulo,
                'tem_arquivo'  => (bool) $m->arquivo_materiais,
                'extensao'     => $m->arquivo_materiais ? strtolower(pathinfo($m->arquivo_materiais, PATHINFO_EXTENSION)) : null,
                'concluido'    => $concluidos->contains((int) $m->id_materiais),
                'url_download' => $m->arquivo_materiais ? route('api.aluno.materiais.download', $m->id_materiais) : null,
            ]),
        ]);
    }

    /**
     * Baixa o arquivo do material (com o token do aluno) e marca como concluído.
     */
    public function downloadMaterial(Request $request, int $idMaterial)
    {
        $idAluno = (int) $request->user()->id_aluno;

        $matriculas = Matricula::where('id_aluno', $idAluno)
            ->where('status_matricula', 'ATIVO')
            ->get();

        // Só material do curso/nível de alguma matrícula ativa do aluno.
        $material = null;
        foreach ($matriculas as $matricula) {
            $material = CursoAtual::filtrar(Materiais::query(), $matricula)->find($idMaterial);
            if ($material) {
                break;
            }
        }

        if (!$material) {
            return $this->naoEncontrado('Material não encontrado.');
        }

        $caminho = public_path($material->arquivo_materiais);

        if (!$material->arquivo_materiais || !file_exists($caminho)) {
            return $this->naoEncontrado('Arquivo não encontrado no servidor.');
        }

        ModuloProgresso::registrarMaterial($idAluno, $material, concluido: true);

        $ext = pathinfo($caminho, PATHINFO_EXTENSION);

        return response()->download($caminho, $material->titulo_materiais . '.' . $ext);
    }

    private function matriculaNoCurso(Request $request, int $idCurso): ?Matricula
    {
        return Matricula::with(['curso', 'nivel'])
            ->where('id_aluno', $request->user()->id_aluno)
            ->where('id_curso', $idCurso)
            ->where('status_matricula', 'ATIVO')
            ->first();
    }

    private function naoEncontrado(string $mensagem): JsonResponse
    {
        return response()->json(['success' => false, 'message' => $mensagem], Response::HTTP_NOT_FOUND);
    }
}
