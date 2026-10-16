<?php

namespace App\Http\Controllers;

use App\Models\QuizResultado;
use App\Support\QuizNivelamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Teste de nivelamento. A página (GET /quiz) é renderizada pelo Blade; as
 * perguntas, a correção e o resultado passam por endpoints JSON, e o gabarito
 * fica sempre no servidor. O progresso de quem está fazendo o teste é guardado
 * na sessão, por idioma, então recarregar a página não perde as respostas.
 */
class QuizController extends Controller
{
    public function quiz()
    {
        $idiomas = QuizNivelamento::idiomas();

        return view('site.quiz.quiz', [
            'idiomas' => $idiomas,
            'niveis'  => QuizNivelamento::niveis(),
            'total'   => QuizNivelamento::total(),
            'cursos'  => collect(array_keys($idiomas))->flatMap(fn ($slug) => QuizNivelamento::cursos($slug))->all(),
        ]);
    }

    /** Perguntas (sem gabarito) + o que já foi respondido nesta sessão. */
    public function perguntas(string $idioma): JsonResponse
    {
        $this->garantirIdioma($idioma);

        $perguntas  = QuizNivelamento::perguntas($idioma);
        $respondidas = [];

        foreach ($this->respostas($idioma) as $indice => $escolha) {
            // Ignora respostas de perguntas que saíram do config depois de respondidas.
            if (isset($perguntas[$indice])) {
                $respondidas[$indice] = $this->corrigir($perguntas[$indice], $escolha);
            }
        }

        return response()->json([
            'idioma'      => $idioma,
            'total'       => count($perguntas),
            'perguntas'   => QuizNivelamento::perguntasPublicas($idioma),
            'respondidas' => (object) $respondidas,
            'resultado'   => session("quiz.{$idioma}.resultado"),
        ]);
    }

    /** Corrige uma resposta e devolve o feedback da pergunta. */
    public function responder(Request $request, string $idioma): JsonResponse
    {
        $this->garantirIdioma($idioma);

        $perguntas = QuizNivelamento::perguntas($idioma);

        $dados = $request->validate([
            'pergunta' => 'required|integer|min:0|max:' . (count($perguntas) - 1),
            'resposta' => 'required|integer|min:0',
        ]);

        $indice = (int) $dados['pergunta'];

        // Cada pergunta pode ter uma quantidade diferente de alternativas.
        if (!array_key_exists((int) $dados['resposta'], $perguntas[$indice]['alternativas'])) {
            return response()->json([
                'message' => 'Alternativa inválida.',
                'errors'  => ['resposta' => ['Alternativa inválida.']],
            ], 422);
        }

        $respostas = $this->respostas($idioma);

        // A primeira resposta vale: não dá para trocar depois de ver o gabarito.
        if (!array_key_exists($indice, $respostas)) {
            $respostas[$indice] = (int) $dados['resposta'];
            session(["quiz.{$idioma}.respostas" => $respostas]);
        }

        $feedback = $this->corrigir($perguntas[$indice], $respostas[$indice]);
        $acertos  = $this->contarAcertos($idioma, $respostas);

        return response()->json($feedback + [
            'respondidas' => count($respostas),
            'acertos'     => $acertos,
        ]);
    }

    /** Fecha o teste: calcula o nível, grava o resultado anônimo e devolve a recomendação. */
    public function finalizar(string $idioma): JsonResponse
    {
        $this->garantirIdioma($idioma);

        // Duplo clique / reenvio: devolve o mesmo resultado sem gravar de novo.
        if ($existente = session("quiz.{$idioma}.resultado")) {
            return response()->json($existente);
        }

        $respostas = $this->respostas($idioma);

        $perguntas = QuizNivelamento::perguntas($idioma);

        if (count(array_intersect_key($respostas, $perguntas)) < count($perguntas)) {
            return response()->json([
                'message' => 'Responda todas as perguntas para ver o resultado.',
            ], 422);
        }

        $resultado = QuizNivelamento::resultado($idioma, $this->contarAcertos($idioma, $respostas));

        QuizResultado::create([
            'idioma'            => $idioma,
            'pontuacao'         => $resultado['pontuacao'],
            'total'             => $resultado['total'],
            'nivel_cefr'        => $resultado['nivel'],
            'curso_recomendado' => $resultado['curso'],
        ]);

        session(["quiz.{$idioma}.resultado" => $resultado]);

        return response()->json($resultado);
    }

    /** "Refazer o teste": apaga as respostas e o resultado desta sessão. */
    public function reiniciar(string $idioma): JsonResponse
    {
        $this->garantirIdioma($idioma);

        session()->forget("quiz.{$idioma}");

        return response()->json(['ok' => true]);
    }

    // ── Auxiliares ──────────────────────────────────────────────────

    private function garantirIdioma(string $idioma): void
    {
        abort_unless(QuizNivelamento::idiomaValido($idioma), 404);
    }

    /** @return array<int,int> posição da pergunta => alternativa escolhida */
    private function respostas(string $idioma): array
    {
        return session("quiz.{$idioma}.respostas", []);
    }

    private function corrigir(array $pergunta, int $escolha): array
    {
        return [
            'escolhida'      => $escolha,
            'correta'        => $escolha === $pergunta['correta'],
            'correta_indice' => $pergunta['correta'],
            'explicacao'     => $pergunta['explicacao'],
        ];
    }

    private function contarAcertos(string $idioma, array $respostas): int
    {
        $perguntas = QuizNivelamento::perguntas($idioma);

        return collect($respostas)
            ->filter(fn ($escolha, $indice) => isset($perguntas[$indice]) && $perguntas[$indice]['correta'] === $escolha)
            ->count();
    }
}
