<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Regras do teste de nivelamento: idiomas, perguntas, níveis CEFR e cursos
 * recomendados. Os dados vêm de config/quiz.php e config/quiz_perguntas.php.
 */
class QuizNivelamento
{
    public static function idiomas(): array
    {
        return config('quiz.idiomas', []);
    }

    public static function idiomaValido(string $slug): bool
    {
        return isset(self::idiomas()[$slug]) && !empty(config("quiz_perguntas.{$slug}"));
    }

    public static function niveis(): array
    {
        return config('quiz.niveis', []);
    }

    public static function total(): int
    {
        return (int) config('quiz.total_perguntas', 12);
    }

    /** Todas as perguntas do idioma, com as respostas (uso interno do servidor). */
    public static function perguntas(string $slug): array
    {
        return array_values(config("quiz_perguntas.{$slug}", []));
    }

    /** Perguntas sem o gabarito, prontas para ir ao navegador. */
    public static function perguntasPublicas(string $slug): array
    {
        return array_map(fn (array $p, int $i) => [
            'indice'       => $i,
            'nivel'        => $p['nivel'],
            'enunciado'    => $p['enunciado'],
            'alternativas' => $p['alternativas'],
        ], self::perguntas($slug), array_keys(self::perguntas($slug)));
    }

    /** Código CEFR (A1..C2) para a quantidade de acertos. */
    public static function nivelPorPontuacao(int $acertos): string
    {
        foreach (self::niveis() as $codigo => $nivel) {
            if ($acertos <= $nivel['ate']) {
                return $codigo;
            }
        }

        return (string) array_key_last(self::niveis());
    }

    /** Nome do curso recomendado, ex.: "Inglês B1". */
    public static function nomeCurso(string $slug, string $nivel): string
    {
        return self::idiomas()[$slug]['nome'] . ' ' . $nivel;
    }

    /** Catálogo completo (um curso por nível) de um idioma. */
    public static function cursos(string $slug): array
    {
        $idioma = self::idiomas()[$slug]['nome'];

        return collect(self::niveis())->map(fn (array $n, string $codigo) => [
            'idioma'    => $slug,
            'nivel'     => $codigo,
            'rotulo'    => $n['rotulo'],
            'nome'      => "{$idioma} {$codigo}",
            'descricao' => str_replace(':idioma', $idioma, $n['curso']),
            'url'       => route('contato', ['curso' => "{$idioma} {$codigo}"]) . '#contcts_container',
        ])->values()->all();
    }

    /** Monta o resultado final (nível, curso e textos) a partir dos acertos. */
    public static function resultado(string $slug, int $acertos): array
    {
        $codigo = self::nivelPorPontuacao($acertos);
        $nivel  = self::niveis()[$codigo];
        $idioma = self::idiomas()[$slug]['nome'];
        $curso  = self::nomeCurso($slug, $codigo);

        $codigos = array_keys(self::niveis());
        $pos     = array_search($codigo, $codigos, true);
        $proximo = $codigos[$pos + 1] ?? null;

        return [
            'idioma'        => $slug,
            'idioma_nome'   => $idioma,
            'pontuacao'     => $acertos,
            'total'         => count(self::perguntas($slug)),
            'nivel'         => $codigo,
            'nivel_rotulo'  => $nivel['rotulo'],
            'curso'         => $curso,
            'descricao'     => str_replace(':idioma', $idioma, $nivel['curso']),
            'sugestao'      => $nivel['sugestao'] . ($proximo ? " Depois, o próximo passo é o nível {$proximo}." : ''),
            'cta_url'       => route('contato', ['curso' => $curso]) . '#contcts_container',
        ];
    }

    /** Mantém apenas texto simples e curto vindo da URL (?curso=...). */
    public static function limparCurso(?string $curso): string
    {
        return Str::limit(trim(strip_tags((string) $curso)), 60, '');
    }
}
