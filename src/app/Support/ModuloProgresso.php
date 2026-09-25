<?php

namespace App\Support;

use App\Models\Matricula;
use App\Models\Modulo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Calcula, para uma matrícula (aluno + curso + nível), a lista de módulos
 * anotados com progresso e liberação sequencial.
 *
 * Regra: módulo concluído = todos os materiais dele marcados CONCLUIDO em
 * tbl_progresso_materiais. Módulo sem material cadastrado conta como
 * concluído (senão travaria a sequência por falta de cadastro). Aulas e
 * atividades ainda não entram na conta porque não têm conclusão por aluno
 * rastreada no sistema.
 *
 * Usado tanto pela tela web do aluno quanto pela API, pra não duplicar a
 * regra de bloqueio em dois lugares.
 */
class ModuloProgresso
{
    public static function paraMatricula(Matricula $matricula): Collection
    {
        $modulos = Modulo::where('id_curso', $matricula->id_curso)
            ->where('id_nivel', $matricula->id_nivel)
            ->where('status_modulo', 'ATIVO')
            ->withCount(['materiais', 'aulas'])
            ->orderBy('ordem_modulo')
            ->get();

        $progressoPorModulo = DB::table('tbl_progresso_materiais')
            ->join('tbl_materiais', 'tbl_materiais.id_materiais', '=', 'tbl_progresso_materiais.id_materiais')
            ->where('tbl_progresso_materiais.id_aluno', $matricula->id_aluno)
            ->where('tbl_progresso_materiais.status_progresso', 'CONCLUIDO')
            ->whereNotNull('tbl_materiais.id_modulo')
            ->select('tbl_materiais.id_modulo', DB::raw('count(*) as concluidos'))
            ->groupBy('tbl_materiais.id_modulo')
            ->pluck('concluidos', 'id_modulo');

        $liberado = true;

        foreach ($modulos as $modulo) {
            $total = $modulo->materiais_count;
            $concluidos = min($progressoPorModulo[$modulo->id_modulo] ?? 0, $total);

            $modulo->materiais_concluidos = $concluidos;
            $modulo->percentual = $total > 0 ? (int) round(($concluidos / $total) * 100) : 0;
            $modulo->concluido = $total > 0 && $concluidos >= $total;
            $modulo->liberado = $liberado;
            $modulo->em_andamento = $modulo->liberado && !$modulo->concluido;

            $liberado = $modulo->concluido || $total === 0;
        }

        return $modulos;
    }

    public static function percentualGeral(Collection $modulos): int
    {
        $total = $modulos->sum('materiais_count');
        $concluidos = $modulos->sum('materiais_concluidos');

        return $total > 0 ? (int) round(($concluidos / $total) * 100) : 0;
    }
}
