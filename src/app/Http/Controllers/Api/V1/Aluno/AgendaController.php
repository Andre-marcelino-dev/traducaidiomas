<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aula;
use App\Models\Matricula;
use App\Support\CursoAtual;
use App\Support\ModuloProgresso;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AgendaController extends Controller
{
    /**
     * Tela "Agenda": todas as aulas do aluno (em todos os cursos com matrícula
     * ativa), em ordem cronológica, mais a próxima aula já calculada.
     */
    public function index(Request $request): JsonResponse
    {
        $idAluno = (int) $request->user()->id_aluno;

        $matriculas = Matricula::with('curso')
            ->where('id_aluno', $idAluno)
            ->where('status_matricula', 'ATIVO')
            ->get();

        $aulas = $matriculas->flatMap(function (Matricula $matricula) {
            return CursoAtual::filtrar(
                Aula::with('professor:id_professor,nome_professor')
                    ->where('status_aulas', 'ATIVO'),
                $matricula
            )->get()->each(function (Aula $aula) use ($matricula) {
                $aula->setAttribute('nome_curso_agenda', $matricula->curso?->nome_curso);
            });
        });

        $aulas = $aulas
            ->sortBy(fn (Aula $a) => $a->data_aulas . ' ' . $a->hora_aulas)
            ->values();

        $concluidas = ModuloProgresso::aulasConcluidas($idAluno, $aulas->pluck('id_aulas'));

        $lista = $aulas->map(fn (Aula $aula) => $this->formatarAula($aula, $concluidas))->values();

        $agora = Carbon::now();
        $proxima = $lista->first(
            fn (array $a) => $a['data'] && $a['hora'] && Carbon::parse($a['data'] . ' ' . $a['hora'])->greaterThanOrEqualTo($agora)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'proxima_aula' => $proxima,
                'aulas' => $lista,
            ],
        ]);
    }

    private function formatarAula(Aula $aula, Collection $concluidas): array
    {
        return [
            'id_aula'         => $aula->id_aulas,
            'titulo'          => $aula->titulo_aulas,
            'curso'           => $aula->nome_curso_agenda,
            'data'            => $aula->data_aulas ? substr((string) $aula->data_aulas, 0, 10) : null,
            'hora'            => $aula->hora_aulas ? substr((string) $aula->hora_aulas, 0, 5) : null,
            'duracao_minutos' => $aula->duracao_minutos,
            'ao_vivo'         => (bool) $aula->link_teams,
            'link_aula'       => $aula->link_teams,
            'professor'       => $aula->professor?->nome_professor,
            'concluida'       => $concluidas->contains((int) $aula->id_aulas),
        ];
    }
}
