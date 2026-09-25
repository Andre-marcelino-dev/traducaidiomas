<?php

namespace App\Support;

use App\Models\Matricula;
use App\Models\Modulo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Curso (matrícula) que o aluno escolheu depois do login.
 *
 * A escolha fica na sessão e todas as telas do painel do aluno filtram
 * aulas, materiais, atividades e módulos por ela. Conteúdo sem módulo
 * aparece para todos os níveis do curso; conteúdo com módulo só aparece
 * para o nível do módulo.
 */
class CursoAtual
{
    const SESSAO = 'aluno_id_matricula';

    private static ?Matricula $cache = null;

    public static function matricula(): ?Matricula
    {
        $aluno = auth('aluno')->user();
        $idMatricula = session(self::SESSAO);

        if (!$aluno || !$idMatricula) {
            return null;
        }

        if (self::$cache && self::$cache->id_matricula == $idMatricula) {
            return self::$cache;
        }

        // Revalida a cada request: a matrícula pode ter sido congelada/cancelada
        // ou ter o nível alterado pelo admin depois da escolha.
        $matricula = Matricula::with(['curso', 'nivel'])
            ->where('id_matricula', $idMatricula)
            ->where('id_aluno', $aluno->id_aluno)
            ->where('status_matricula', 'ATIVO')
            ->first();

        if (!$matricula) {
            session()->forget(self::SESSAO);
            return null;
        }

        return self::$cache = $matricula;
    }

    public static function definir(Matricula $matricula): void
    {
        session([self::SESSAO => $matricula->id_matricula]);
        self::$cache = null;
    }

    public static function idsModulos(Matricula $matricula): Collection
    {
        return Modulo::where('id_curso', $matricula->id_curso)
            ->where('id_nivel', $matricula->id_nivel)
            ->pluck('id_modulo');
    }

    /**
     * Restringe uma query de Aula/Materiais/Atividade ao curso e nível da matrícula.
     */
    public static function filtrar(Builder $query, Matricula $matricula): Builder
    {
        $idsModulos = self::idsModulos($matricula);

        return $query
            ->where('id_curso', $matricula->id_curso)
            ->where(function ($q) use ($idsModulos) {
                $q->whereNull('id_modulo')->orWhereIn('id_modulo', $idsModulos);
            });
    }
}
