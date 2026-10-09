<?php

namespace App\Http\Controllers\Api\V1\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Atividade;
use App\Models\AtividadeResposta;
use App\Models\Aula;
use App\Models\Curso;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Presenca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Mesma regra do site (aluno/ChatbotController), só que sempre "aluno"
 * (a API é só para o app logado) e com o contexto de TODOS os cursos
 * matriculados, como Agenda/Atividades/Desempenho — não só o "curso
 * atual" da sessão, que não existe aqui.
 */
class ChatbotController extends Controller
{
    public function dados(Request $request): JsonResponse
    {
        $aluno = $request->user();

        return response()->json([
            'success' => true,
            'perfil' => 'aluno',
            'usuario' => [
                'id' => $aluno->id_aluno,
                'nome' => trim($aluno->nome_aluno ?? 'Aluno'),
            ],
        ]);
    }

    public function mensagem(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'mensagem' => 'required|string|max:4000',
        ], [
            'mensagem.required' => 'Escreva uma mensagem.',
        ]);

        $mensagem = trim($dados['mensagem']);
        $aluno = $request->user();
        $nome = trim($aluno->nome_aluno ?? 'Aluno');

        $contexto = $this->montarContexto($aluno);
        $systemPrompt = $this->montarPrompt($nome, $contexto);

        $apiKey = config('services.groq.key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'A chave da API da Groq não está configurada.',
            ], 500);
        }

        $model = config('services.groq.model', env('GROQ_MODEL', 'llama-3.1-8b-instant'));

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $mensagem],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 800,
                ]);

            if (!$response->successful()) {
                Log::error('Erro Groq (app)', ['status' => $response->status(), 'body' => $response->body()]);

                return response()->json($this->comOffline($this->fallbackOffline($mensagem, $aluno)));
            }

            $resposta = $response->json('choices.0.message.content') ?? 'Não consegui gerar uma resposta no momento.';
            $sugestoes = $this->extrairSugestoes($resposta);

            return response()->json([
                'success' => true,
                'perfil' => 'aluno',
                'nome' => $nome,
                'text' => trim($resposta),
                'sugestoes' => $sugestoes,
                'card' => $this->detectarCard($mensagem, $aluno),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro no Chatbot (app)', [
                'erro' => $e->getMessage(), 'arquivo' => $e->getFile(), 'linha' => $e->getLine(),
            ]);

            return response()->json($this->comOffline($this->fallbackOffline($mensagem, $aluno)));
        }
    }

    private function comOffline(array $fallback): array
    {
        return [
            'success' => true,
            'text' => $fallback['text'],
            'sugestoes' => $fallback['sugestoes'],
            'card' => $fallback['card'],
            'offline' => true,
        ];
    }

    private function extrairSugestoes(string &$resposta): array
    {
        if (preg_match('/\[SUGESTOES\](.*?)\[\/SUGESTOES\]/s', $resposta, $matches)) {
            $sugestoes = json_decode(trim($matches[1]), true);

            if (is_array($sugestoes) && count($sugestoes) > 0) {
                $resposta = trim(str_replace($matches[0], '', $resposta));

                return array_slice(array_values($sugestoes), 0, 3);
            }
        }

        return [];
    }

    /**
     * Card visual (opção 4 do site): agenda ou progresso, se a mensagem pedir.
     */
    private function detectarCard(string $mensagem, Aluno $aluno): ?array
    {
        $msg = mb_strtolower($mensagem);
        $idsCursos = Matricula::where('id_aluno', $aluno->id_aluno)
            ->where('status_matricula', 'ATIVO')
            ->pluck('id_curso');

        if (str_contains($msg, 'aula') || str_contains($msg, 'agenda') || str_contains($msg, 'horário') || str_contains($msg, 'horario')) {
            $aulas = Aula::with('professor')
                ->whereIn('id_curso', $idsCursos)
                ->orderBy('data_aulas')->orderBy('hora_aulas')
                ->limit(5)->get();

            if ($aulas->isEmpty()) {
                return null;
            }

            return [
                'type' => 'schedule',
                'title' => 'Próximas Aulas',
                'items' => $aulas->map(fn (Aula $a) => [
                    'titulo' => $a->titulo_aulas,
                    'data' => $a->data_aulas,
                    'hora' => $a->hora_aulas,
                    'professor' => $a->professor->nome_professor ?? 'N/A',
                ])->values(),
            ];
        }

        if (str_contains($msg, 'progresso') || str_contains($msg, 'presença') || str_contains($msg, 'presenca') || str_contains($msg, 'frequência') || str_contains($msg, 'frequencia')) {
            $presencas = Presenca::where('id_aluno', $aluno->id_aluno)->get();
            $total = $presencas->count();
            $presente = $presencas->where('status_presenca', 'presente')->count();
            $falta = $presencas->where('status_presenca', 'falta')->count();
            $perc = $total > 0 ? round(($presente / $total) * 100) : 0;

            return [
                'type' => 'progress',
                'title' => 'Meu Progresso',
                'items' => [
                    ['label' => 'Aulas registradas', 'value' => $total],
                    ['label' => 'Presenças', 'value' => $presente],
                    ['label' => 'Faltas', 'value' => $falta],
                    ['label' => 'Percentual', 'value' => $perc . '%'],
                ],
            ];
        }

        return null;
    }

    /**
     * Respostas prontas pra quando a Groq falha (sem pedir pro aluno tentar de novo
     * sem explicação).
     */
    private function fallbackOffline(string $mensagem, Aluno $aluno): array
    {
        $msg = mb_strtolower($mensagem);

        if (str_contains($msg, 'progresso') || str_contains($msg, 'presença') || str_contains($msg, 'presenca')) {
            $presencas = Presenca::where('id_aluno', $aluno->id_aluno)->get();
            $total = $presencas->count();
            $presente = $presencas->where('status_presenca', 'presente')->count();
            $perc = $total > 0 ? round(($presente / $total) * 100) : 0;

            return [
                'text' => "📊 Seu progresso atual:\n\n• Aulas registradas: {$total}\n• Presenças: {$presente}\n• Percentual de presença: {$perc}%\n\nContinue assim! 💪",
                'sugestoes' => ['Quais são minhas próximas aulas?', 'Quais materiais tenho disponíveis?', 'Quero praticar vocabulário'],
                'card' => null,
            ];
        }

        if (str_contains($msg, 'aula') || str_contains($msg, 'agenda')) {
            $idsCursos = Matricula::where('id_aluno', $aluno->id_aluno)->where('status_matricula', 'ATIVO')->pluck('id_curso');
            $aulas = Aula::whereIn('id_curso', $idsCursos)->orderBy('data_aulas')->limit(3)->get();

            if ($aulas->isEmpty()) {
                return [
                    'text' => 'No momento não encontrei aulas agendadas para você. 📅',
                    'sugestoes' => ['Qual meu progresso?', 'Quais materiais tenho?', 'Quero estudar inglês'],
                    'card' => null,
                ];
            }

            $texto = "📅 Suas próximas aulas:\n\n";
            foreach ($aulas as $aula) {
                $texto .= "• {$aula->titulo_aulas} - {$aula->data_aulas} às {$aula->hora_aulas}\n";
            }

            return [
                'text' => $texto,
                'sugestoes' => ['Qual meu progresso?', 'Quais materiais tenho?', 'Quero praticar conversação'],
                'card' => null,
            ];
        }

        if (str_contains($msg, 'material')) {
            $idsCursos = Matricula::where('id_aluno', $aluno->id_aluno)->where('status_matricula', 'ATIVO')->pluck('id_curso');
            $materiais = Materiais::whereIn('id_curso', $idsCursos)->limit(3)->get();

            if ($materiais->isEmpty()) {
                return [
                    'text' => 'Não encontrei materiais disponíveis para seu curso no momento. 📚',
                    'sugestoes' => ['Qual meu progresso?', 'Quais são minhas aulas?', 'Quero estudar gramática'],
                    'card' => null,
                ];
            }

            $texto = "📚 Materiais disponíveis:\n\n";
            foreach ($materiais as $material) {
                $texto .= "• {$material->titulo_materiais}\n";
            }

            return [
                'text' => $texto,
                'sugestoes' => ['Qual meu progresso?', 'Quais são minhas aulas?', 'Quero praticar vocabulário'],
                'card' => null,
            ];
        }

        return [
            'text' => "Olá, {$aluno->nome_aluno}! 👋\n\nEstou com dificuldade de conexão no momento, mas posso te ajudar com:\n\n• Seu progresso e presenças\n• Suas próximas aulas\n• Materiais disponíveis\n• Dicas de estudo\n\nPergunte sobre qualquer um desses! 😊",
            'sugestoes' => ['Qual meu progresso?', 'Quais são minhas aulas?', 'Quais materiais tenho?'],
            'card' => null,
        ];
    }

    /**
     * Mesmos dados reais do site (aluno/ChatbotController@montarContexto, ramo
     * aluno), só que somando TODOS os cursos com matrícula ativa, não só o
     * "curso atual" da sessão.
     */
    private function montarContexto(Aluno $aluno): string
    {
        $contexto = "DADOS DO ALUNO:\n";
        $contexto .= "- Nome: {$aluno->nome_aluno}\n";
        $contexto .= "- Email: {$aluno->email_aluno}\n";
        $contexto .= "- Curso: {$aluno->curso_aluno}\n";
        $contexto .= "- Nível: {$aluno->nivel_aluno}\n";
        $contexto .= "- Status: {$aluno->status_aluno}\n\n";

        $matriculas = Matricula::where('id_aluno', $aluno->id_aluno)->where('status_matricula', 'ATIVO')->get();

        $contexto .= "MATRÍCULAS:\n";
        foreach ($matriculas as $matricula) {
            $curso = Curso::find($matricula->id_curso);
            $nivel = Nivel::find($matricula->id_nivel);
            $contexto .= "- Curso: " . ($curso->nome_curso ?? 'Curso') . " | Nível: " . ($nivel->nome_nivel ?? 'Nível') .
                " | Data: {$matricula->data_matricula} | Status: {$matricula->status_matricula}\n";
        }
        $contexto .= "\n";

        $idsCursos = $matriculas->pluck('id_curso');

        $aulas = Aula::with('professor')->whereIn('id_curso', $idsCursos)
            ->orderBy('data_aulas')->orderBy('hora_aulas')->get();

        $contexto .= "AULAS:\n";
        foreach ($aulas as $aula) {
            $contexto .= "- {$aula->titulo_aulas} | Data: {$aula->data_aulas} | Hora: {$aula->hora_aulas} | " .
                'Professor: ' . ($aula->professor->nome_professor ?? 'N/A') . "\n";
        }
        $contexto .= "\n";

        $materiais = Materiais::whereIn('id_curso', $idsCursos)->orderByDesc('id_materiais')->get();

        $contexto .= "MATERIAIS DISPONÍVEIS:\n";
        foreach ($materiais as $material) {
            $contexto .= "- {$material->titulo_materiais} | Curso: " . ($material->curso->nome_curso ?? 'N/A') . "\n";
        }
        $contexto .= "\n";

        $presencas = Presenca::where('id_aluno', $aluno->id_aluno)->get();
        $totalAulas = $presencas->count();
        $totalPresente = $presencas->where('status_presenca', 'presente')->count();
        $totalFalta = $presencas->where('status_presenca', 'falta')->count();
        $percPresenca = $totalAulas > 0 ? round(($totalPresente / $totalAulas) * 100) : 0;

        $contexto .= "PROGRESSO:\n";
        $contexto .= "- Total de aulas registradas: {$totalAulas}\n";
        $contexto .= "- Presenças: {$totalPresente}\n";
        $contexto .= "- Faltas: {$totalFalta}\n";
        $contexto .= "- Percentual de presença: {$percPresenca}%\n\n";

        $totalMateriais = $materiais->count();
        $materiaisVistos = DB::table('tbl_progresso_materiais')
            ->where('id_aluno', $aluno->id_aluno)->where('status_progresso', 'CONCLUIDO')->count();
        $percMateriais = $totalMateriais > 0 ? round(($materiaisVistos / $totalMateriais) * 100) : 0;

        $contexto .= "- Materiais totais: {$totalMateriais}\n";
        $contexto .= "- Materiais vistos: {$materiaisVistos}\n";
        $contexto .= "- Percentual de materiais: {$percMateriais}%\n\n";

        $atividades = Atividade::whereIn('id_curso', $idsCursos)->orderByDesc('id_atividade')->get();

        $contexto .= "ATIVIDADES:\n";
        foreach ($atividades as $atividade) {
            $nota = AtividadeResposta::where('id_atividade', $atividade->id_atividade)
                ->where('id_aluno', $aluno->id_aluno)->first()?->nota;

            $contexto .= "- {$atividade->titulo_atividade} | Entrega: {$atividade->data_entrega} | " .
                'Nota: ' . ($nota ?? 'Não respondida') . "\n";
        }
        $contexto .= "\n";

        return $contexto;
    }

    /**
     * Mesmo prompt do site (ramo aluno).
     */
    private function montarPrompt(string $nome, string $contexto): string
    {
        return <<<PROMPT
Você é a Traduca AI, assistente virtual da plataforma Traduca Idiomas.

Você está conversando com um ALUNO, pelo aplicativo do celular.

Nome do aluno: {$nome}

Você possui acesso aos dados reais do aluno abaixo:

{$contexto}

Seu objetivo é ajudar o aluno nos estudos de idiomas.

Você pode ajudar com:

- consultar o progresso do aluno (presenças, materiais vistos);
- consultar o nível do aluno;
- consultar as aulas do aluno;
- consultar os materiais disponíveis;
- consultar as atividades e notas;
- dúvidas de inglês;
- dúvidas de italiano;
- tradução;
- gramática;
- vocabulário;
- exercícios;
- explicações de conteúdos;
- preparação para provas;
- interpretação de textos;
- conversação;
- correção de frases;
- sugestões de estudo;
- explicação de atividades.

Quando o aluno perguntar sobre seu progresso, nível, aulas, materiais ou atividades, use os dados reais do contexto acima.

Explique os conteúdos de maneira didática e adequada ao nível do aluno.

Não entregue apenas a resposta quando o aluno estiver estudando um conteúdo. Sempre que possível, explique o motivo da resposta.

Não invente informações sobre aulas, notas, professores ou matrículas que não estejam no contexto.

Responda em português, exceto quando o aluno solicitar outro idioma.

Seja paciente, didático e objetivo.

IMPORTANTE: Ao final de cada resposta, sugira 3 perguntas de acompanhamento relevantes no formato:
[SUGESTOES] ["Pergunta 1", "Pergunta 2", "Pergunta 3"] [/SUGESTOES]
As sugestões devem ser curtas, relevantes ao contexto da conversa e em português.
PROMPT;
    }
}
