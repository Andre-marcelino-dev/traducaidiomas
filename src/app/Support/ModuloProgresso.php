<?php

namespace App\Support;

use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Modulo;
use App\Models\Presenca;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Calcula, para uma matrícula (aluno + curso + nível), a lista de módulos
 * anotados com progresso e liberação sequencial.
 *
 * Regra: o módulo tem "itens" = materiais + aulas ativas.
 *  - material conta como feito quando está CONCLUIDO em tbl_progresso_materiais
 *    (o aluno abriu/baixou o arquivo);
 *  - aula conta como feita quando o professor marcou presença "presente" ou
 *    "justificado" para o aluno.
 * Módulo concluído = todos os itens feitos. Módulo sem nenhum item não trava
 * a sequência (senão travaria por falta de cadastro).
 *
 * Usado pela tela web do aluno e pela API do app, pra não duplicar a regra.
 */
class ModuloProgresso
{
    /** Status de presença que contam a aula como concluída. */
    const PRESENCA_CONCLUI = ['presente', 'justificado'];

    public static function paraMatricula(Matricula $matricula): Collection
    {
        $modulos = Modulo::where('id_curso', $matricula->id_curso)
            ->where('id_nivel', $matricula->id_nivel)
            ->where('status_modulo', 'ATIVO')
            ->withCount([
                'materiais',
                'aulas' => fn ($q) => $q->where('status_aulas', 'ATIVO'),
            ])
            ->orderBy('ordem_modulo')
            ->get();

        $materiaisPorModulo = DB::table('tbl_progresso_materiais')
            ->join('tbl_materiais', 'tbl_materiais.id_materiais', '=', 'tbl_progresso_materiais.id_materiais')
            ->where('tbl_progresso_materiais.id_aluno', $matricula->id_aluno)
            ->where('tbl_progresso_materiais.status_progresso', 'CONCLUIDO')
            ->whereNotNull('tbl_materiais.id_modulo')
            ->select('tbl_materiais.id_modulo', DB::raw('count(distinct tbl_materiais.id_materiais) as concluidos'))
            ->groupBy('tbl_materiais.id_modulo')
            ->pluck('concluidos', 'id_modulo');

        $aulasPorModulo = DB::table('presenca')
            ->join('tbl_aulas', 'tbl_aulas.id_aulas', '=', 'presenca.id_aulas')
            ->where('presenca.id_aluno', $matricula->id_aluno)
            ->whereIn('presenca.status_presenca', self::PRESENCA_CONCLUI)
            ->where('tbl_aulas.status_aulas', 'ATIVO')
            ->whereNotNull('tbl_aulas.id_modulo')
            ->select('tbl_aulas.id_modulo', DB::raw('count(distinct tbl_aulas.id_aulas) as concluidas'))
            ->groupBy('tbl_aulas.id_modulo')
            ->pluck('concluidas', 'id_modulo');

        $liberado = true;

        foreach ($modulos as $modulo) {
            $materiaisFeitos = min((int) ($materiaisPorModulo[$modulo->id_modulo] ?? 0), $modulo->materiais_count);
            $aulasFeitas = min((int) ($aulasPorModulo[$modulo->id_modulo] ?? 0), $modulo->aulas_count);

            $total = $modulo->materiais_count + $modulo->aulas_count;
            $feitos = $materiaisFeitos + $aulasFeitas;

            $modulo->materiais_concluidos = $materiaisFeitos;
            $modulo->aulas_concluidas = $aulasFeitas;
            $modulo->total_itens = $total;
            $modulo->itens_concluidos = $feitos;
            $modulo->percentual = $total > 0 ? (int) round(($feitos / $total) * 100) : 0;
            $modulo->concluido = $total > 0 && $feitos >= $total;
            $modulo->liberado = $liberado;
            $modulo->em_andamento = $modulo->liberado && !$modulo->concluido;

            $liberado = $modulo->concluido || $total === 0;
        }

        return $modulos;
    }

    public static function percentualGeral(Collection $modulos): int
    {
        $total = $modulos->sum('total_itens');
        $feitos = $modulos->sum('itens_concluidos');

        return $total > 0 ? (int) round(($feitos / $total) * 100) : 0;
    }

    /**
     * Ids das aulas (dentre as informadas) que o aluno já concluiu.
     */
    public static function aulasConcluidas(int $idAluno, Collection $idsAulas): Collection
    {
        return Presenca::where('id_aluno', $idAluno)
            ->whereIn('id_aulas', $idsAulas)
            ->whereIn('status_presenca', self::PRESENCA_CONCLUI)
            ->pluck('id_aulas')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    /**
     * Ids dos materiais (dentre os informados) que o aluno já concluiu.
     */
    public static function materiaisConcluidos(int $idAluno, Collection $idsMateriais): Collection
    {
        return DB::table('tbl_progresso_materiais')
            ->where('id_aluno', $idAluno)
            ->whereIn('id_materiais', $idsMateriais)
            ->where('status_progresso', 'CONCLUIDO')
            ->pluck('id_materiais')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    /**
     * Grava o acesso do aluno ao material. Material já CONCLUIDO nunca volta
     * para EM ANDAMENTO. Usado pelo site (aluno/MateriaisController) e pela API.
     */
    public static function registrarMaterial(int $idAluno, Materiais $material, bool $concluido): void
    {
        $chave = [
            'id_aluno'     => $idAluno,
            'id_materiais' => $material->id_materiais,
        ];

        $atual = DB::table('tbl_progresso_materiais')->where($chave)->first();

        if ($atual && $atual->status_progresso === 'CONCLUIDO') {
            $concluido = true;
        }

        $dados = [
            'status_progresso'                => $concluido ? 'CONCLUIDO' : 'EM ANDAMENTO',
            'progresso_materiais'             => $concluido ? 100 : 0,
            'data_acesso_progresso_materiais' => now(),
        ];

        if ($atual) {
            DB::table('tbl_progresso_materiais')->where('id_progresso', $atual->id_progresso)->update($dados);
        } else {
            DB::table('tbl_progresso_materiais')->insert($chave + $dados);
        }
    }
}
