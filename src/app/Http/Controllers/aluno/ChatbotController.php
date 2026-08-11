<?php

namespace App\Http\Controllers\aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Aula;
use App\Models\Materiais;
use App\Models\Matricula;
use App\Models\Presenca;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Atividade;
use App\Models\AtividadeResposta;

class ChatbotController extends Controller
{
    /**
     * Retorna os dados do usuário/perfil atual.
     */
    public function dados(Request $request)
    {
        $perfil = 'visitante';
        $usuario = null;

        if (Auth::guard('admin')->check()) {
            $perfil = 'professor';
            $usuario = Auth::guard('admin')->user();
        } elseif (Auth::guard('aluno')->check()) {
            $perfil = 'aluno';
            $usuario = Auth::guard('aluno')->user();
        }

        return response()->json([
            'success' => true,
            'perfil' => $perfil,
            'usuario' => $usuario ? [
                'id' => $usuario->getAuthIdentifier(),
                'nome' => $this->nomeUsuario($usuario, $perfil),
            ] : null,
        ]);
    }

    /**
     * Envia uma mensagem para a IA.
     */
    public function mensagem(Request $request)
    {
        $request->validate([
            'mensagem' => 'required|string|max:4000',
        ]);

        $mensagem = trim($request->input('mensagem'));

        $perfil = 'visitante';
        $usuario = null;

        /*
        |--------------------------------------------------------------------------
        | Identifica o perfil
        |--------------------------------------------------------------------------
        */

        if (Auth::guard('admin')->check()) {
            $perfil = 'professor';
            $usuario = Auth::guard('admin')->user();
        } elseif (Auth::guard('aluno')->check()) {
            $perfil = 'aluno';
            $usuario = Auth::guard('aluno')->user();
        }

        /*
        |--------------------------------------------------------------------------
        | Nome do usuário
        |--------------------------------------------------------------------------
        */

        $nome = $usuario
            ? $this->nomeUsuario($usuario, $perfil)
            : 'Visitante';

        /*
        |--------------------------------------------------------------------------
        | Contexto real do banco de dados
        |--------------------------------------------------------------------------
        */

        $contexto = $this->montarContexto($perfil, $usuario);

        /*
        |--------------------------------------------------------------------------
        | Prompt específico para cada perfil
        |--------------------------------------------------------------------------
        */

        $systemPrompt = $this->montarPrompt($perfil, $nome, $contexto);

        /*
        |--------------------------------------------------------------------------
        | Verifica a chave da Groq
        |--------------------------------------------------------------------------
        */

        $apiKey = config('services.groq.key');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'A chave da API da Groq não está configurada.'
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Modelo
        |--------------------------------------------------------------------------
        */

        $model = config(
            'services.groq.model',
            env('GROQ_MODEL', 'llama-3.1-8b-instant')
        );

        /*
        |--------------------------------------------------------------------------
        | Chamada para Groq
        |--------------------------------------------------------------------------
        */

        try {

            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [

                    'model' => $model,

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $mensagem,
                        ],
                    ],

                    'temperature' => 0.7,

                    'max_tokens' => 800,
                ]);

            /*
            |--------------------------------------------------------------------------
            | Erro da API
            |--------------------------------------------------------------------------
            */

            if (!$response->successful()) {

                Log::error('Erro Groq', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                // ★ FALLBACK OFFLINE (opção 9)
                $fallback = $this->fallbackOffline($perfil, $mensagem, $usuario);

                return response()->json([
                    'success' => true,
                    'text' => $fallback['text'],
                    'resposta' => $fallback['text'],
                    'sugestoes' => $fallback['sugestoes'],
                    'card' => $fallback['card'],
                    'offline' => true,
                ]);
            }

            $data = $response->json();

            $resposta = $data['choices'][0]['message']['content']
                ?? 'Não consegui gerar uma resposta no momento.';

            // Extrai sugestões da resposta se vierem em formato JSON (opção 3)
            $sugestoes = $this->extrairSugestoes($resposta);

            // Detecta se a mensagem pede um card visual (opção 4)
            $card = $this->detectarCard($perfil, $mensagem, $usuario);

            return response()->json([
                'success' => true,
                'perfil' => $perfil,
                'nome' => $nome,
                'text' => trim($resposta),
                'resposta' => trim($resposta),
                'sugestoes' => $sugestoes,
                'card' => $card,
            ]);

        } catch (\Throwable $e) {

            Log::error('Erro no Chatbot', [
                'erro' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine(),
            ]);

            // ★ FALLBACK OFFLINE (opção 9)
            $fallback = $this->fallbackOffline($perfil, $mensagem, $usuario);

            return response()->json([
                'success' => true,
                'text' => $fallback['text'],
                'resposta' => $fallback['text'],
                'sugestoes' => $fallback['sugestoes'],
                'card' => $fallback['card'],
                'offline' => true,
            ]);
        }
    }

    /**
     * Extrai sugestões de resposta da IA.
     */
    private function extrairSugestoes(string $resposta): array
    {
        // Tenta extrair JSON de sugestões no formato: [SUGESTOES] ["...", "..."] [/SUGESTOES]
        if (preg_match('/\[SUGESTOES\](.*?)\[\/SUGESTOES\]/s', $resposta, $matches)) {
            $json = trim($matches[1]);
            $sugestoes = json_decode($json, true);

            if (is_array($sugestoes) && count($sugestoes) > 0) {
                // Remove o bloco de sugestões da resposta visível
                $resposta = trim(str_replace($matches[0], '', $resposta));

                return array_slice(array_values($sugestoes), 0, 3);
            }
        }

        return [];
    }

    /**
     * Detecta se a mensagem pede um card visual (opção 4).
     */
    private function detectarCard(string $perfil, string $mensagem, $usuario): ?array
    {
        $msg = mb_strtolower($mensagem);

        // ALUNO - card de agenda/aulas
        if ($perfil === 'aluno' && (
            str_contains($msg, 'aula') ||
            str_contains($msg, 'agenda') ||
            str_contains($msg, 'horário') ||
            str_contains($msg, 'horario')
        )) {
            $alunoId = $usuario->getAuthIdentifier();
            $matriculas = Matricula::where('id_aluno', $alunoId)->get();
            $idCursos = $matriculas->pluck('id_curso');

            $aulas = Aula::with('professor')
                ->whereIn('id_curso', $idCursos)
                ->orderBy('data_aulas')
                ->orderBy('hora_aulas')
                ->limit(5)
                ->get();

            if ($aulas->isEmpty()) {
                $aulas = Aula::with('professor')
                    ->orderBy('data_aulas')
                    ->orderBy('hora_aulas')
                    ->limit(5)
                    ->get();
            }

            if ($aulas->isEmpty()) {
                return null;
            }

            $items = [];

            foreach ($aulas as $aula) {
                $items[] = [
                    'titulo' => $aula->titulo_aulas,
                    'data' => $aula->data_aulas,
                    'hora' => $aula->hora_aulas,
                    'professor' => $aula->professor->nome_professor ?? 'N/A',
                ];
            }

            return [
                'type' => 'schedule',
                'title' => 'Próximas Aulas',
                'items' => $items,
            ];
        }

        // ALUNO - card de progresso
        if ($perfil === 'aluno' && (
            str_contains($msg, 'progresso') ||
            str_contains($msg, 'presença') ||
            str_contains($msg, 'presenca') ||
            str_contains($msg, 'frequência') ||
            str_contains($msg, 'frequencia')
        )) {
            $alunoId = $usuario->getAuthIdentifier();

            $presencas = Presenca::where('id_aluno', $alunoId)->get();
            $totalAulas = $presencas->count();
            $totalPresente = $presencas->where('status_presenca', 'PRESENTE')->count();
            $totalFalta = $presencas->where('status_presenca', 'FALTA')->count();
            $percPresenca = $totalAulas > 0 ? round(($totalPresente / $totalAulas) * 100) : 0;

            return [
                'type' => 'progress',
                'title' => 'Meu Progresso',
                'items' => [
                    ['label' => 'Aulas registradas', 'value' => $totalAulas],
                    ['label' => 'Presenças', 'value' => $totalPresente],
                    ['label' => 'Faltas', 'value' => $totalFalta],
                    ['label' => 'Percentual', 'value' => $percPresenca . '%'],
                ],
            ];
        }

        // VISITANTE - card de professores
        if ($perfil === 'visitante' && (
            str_contains($msg, 'professor') ||
            str_contains($msg, 'professores')
        )) {
            $professores = Professor::all();

            if ($professores->isEmpty()) {
                return null;
            }

            $items = [];

            foreach ($professores as $professor) {
                $items[] = [
                    'nome' => $professor->nome_professor,
                    'especialidade' => $professor->especialidade_professor,
                    'curso' => $professor->curso_professor,
                    'nivel' => $professor->nivel_professor,
                ];
            }

            return [
                'type' => 'professors',
                'title' => 'Nossos Professores',
                'items' => $items,
            ];
        }

        // VISITANTE - card de preços
        if ($perfil === 'visitante' && (
            str_contains($msg, 'preço') ||
            str_contains($msg, 'preco') ||
            str_contains($msg, 'valor') ||
            str_contains($msg, 'quanto custa')
        )) {
            $servicos = \App\Models\Servico::all();

            if ($servicos->isEmpty()) {
                return null;
            }

            $items = [];

            foreach ($servicos as $servico) {
                $items[] = [
                    'titulo' => $servico->titulo_servico,
                    'preco' => $servico->preco_servico,
                    'idioma' => $servico->lingua_servico,
                ];
            }

            return [
                'type' => 'prices',
                'title' => 'Preços dos Cursos',
                'items' => $items,
            ];
        }

        return null;
    }

    /**
     * Fallback offline com respostas pré-definidas (opção 9).
     */
    private function fallbackOffline(string $perfil, string $mensagem, $usuario): array
    {
        $msg = mb_strtolower(trim($mensagem));

        // ALUNO
        if ($perfil === 'aluno') {
            $alunoId = $usuario->getAuthIdentifier();
            $aluno = Aluno::find($alunoId);

            if (str_contains($msg, 'progresso') || str_contains($msg, 'presença') || str_contains($msg, 'presenca')) {
                $presencas = Presenca::where('id_aluno', $alunoId)->get();
                $total = $presencas->count();
                $presente = $presencas->where('status_presenca', 'PRESENTE')->count();
                $perc = $total > 0 ? round(($presente / $total) * 100) : 0;

                return [
                    'text' => "📊 Seu progresso atual:\n\n• Aulas registradas: {$total}\n• Presenças: {$presente}\n• Percentual de presença: {$perc}%\n\nContinue assim! 💪",
                    'sugestoes' => ['Quais são minhas próximas aulas?', 'Quais materiais tenho disponíveis?', 'Quero praticar vocabulário'],
                    'card' => null,
                ];
            }

            if (str_contains($msg, 'aula') || str_contains($msg, 'agenda')) {
                $matriculas = Matricula::where('id_aluno', $alunoId)->get();
                $idCursos = $matriculas->pluck('id_curso');
                $aulas = Aula::whereIn('id_curso', $idCursos)->orderBy('data_aulas')->limit(3)->get();

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
                $matriculas = Matricula::where('id_aluno', $alunoId)->get();
                $idCursos = $matriculas->pluck('id_curso');
                $materiais = Materiais::whereIn('id_curso', $idCursos)->limit(3)->get();

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
                'text' => "Olá" . ($aluno ? ", {$aluno->nome_aluno}" : '') . "! 👋\n\nEstou com dificuldade de conexão no momento, mas posso te ajudar com:\n\n• Seu progresso e presenças\n• Suas próximas aulas\n• Materiais disponíveis\n• Dicas de estudo\n\nPergunte sobre qualquer um desses! 😊",
                'sugestoes' => ['Qual meu progresso?', 'Quais são minhas aulas?', 'Quais materiais tenho?'],
                'card' => null,
            ];
        }

        // VISITANTE
        if ($perfil === 'visitante') {
            if (str_contains($msg, 'professor')) {
                $professores = Professor::all();

                if ($professores->isEmpty()) {
                    return [
                        'text' => 'No momento não encontrei professores cadastrados. 😕',
                        'sugestoes' => ['Quanto custa o curso?', 'Quantos alunos estão matriculados?', 'Quais idiomas posso estudar?'],
                        'card' => null,
                    ];
                }

                $texto = "👨‍🏫 Nossos professores:\n\n";
                foreach ($professores->take(3) as $professor) {
                    $texto .= "• {$professor->nome_professor} - {$professor->especialidade_professor}\n";
                }

                return [
                    'text' => $texto,
                    'sugestoes' => ['Quanto custa o curso?', 'Quantos alunos estão matriculados?', 'Quero conhecer a Traduca'],
                    'card' => null,
                ];
            }

            if (str_contains($msg, 'preço') || str_contains($msg, 'preco') || str_contains($msg, 'valor') || str_contains($msg, 'quanto custa')) {
                $servicos = \App\Models\Servico::all();

                if ($servicos->isEmpty()) {
                    return [
                        'text' => 'No momento não encontrei informações de preços. 😕',
                        'sugestoes' => ['Quais professores existem?', 'Quantos alunos estão matriculados?', 'Quais idiomas posso estudar?'],
                        'card' => null,
                    ];
                }

                $texto = "💰 Nossos preços:\n\n";
                foreach ($servicos->take(3) as $servico) {
                    $texto .= "• {$servico->titulo_servico}: R$ {$servico->preco_servico}\n";
                }

                return [
                    'text' => $texto,
                    'sugestoes' => ['Quais professores existem?', 'Quantos alunos estão matriculados?', 'Quero fazer matrícula'],
                    'card' => null,
                ];
            }

            if (str_contains($msg, 'matriculado') || str_contains($msg, 'alunos')) {
                $totalAlunos = Aluno::count();
                $totalMatriculas = Matricula::count();

                return [
                    'text' => "🎓 Dados da plataforma:\n\n• Alunos cadastrados: {$totalAlunos}\n• Matrículas ativas: {$totalMatriculas}\n\nVenha fazer parte da Traduca Idiomas! 🚀",
                    'sugestoes' => ['Quais professores existem?', 'Quanto custa o curso?', 'Quais idiomas posso estudar?'],
                    'card' => null,
                ];
            }

            return [
                'text' => "Olá! 👋\n\nEstou com dificuldade de conexão no momento, mas posso te ajudar com:\n\n• Informações sobre professores\n• Preços dos cursos\n• Quantidade de alunos matriculados\n• Dicas de estudo de idiomas\n\nPergunte sobre qualquer um desses! 😊",
                'sugestoes' => ['Quais professores existem?', 'Quanto custa o curso?', 'Quantos alunos estão matriculados?'],
                'card' => null,
            ];
        }

        // PROFESSOR
        return [
            'text' => "Olá, Professor! 👋\n\nEstou com dificuldade de conexão no momento, mas posso te ajudar com:\n\n• Dados dos alunos\n• Suas aulas\n• Seus materiais\n• Planejamento de aulas\n\nPergunte sobre qualquer um desses! 😊",
            'sugestoes' => ['Quais alunos estão matriculados?', 'Quais são minhas aulas?', 'Crie uma atividade'],
            'card' => null,
        ];
    }

    /**
     * Monta o contexto real do banco de dados para a IA.
     */
    private function montarContexto(string $perfil, $usuario): string
    {
        $contexto = "";

        /*
        |--------------------------------------------------------------------------
        | PROFESSOR - consulta dados de alunos
        |--------------------------------------------------------------------------
        */

        if ($perfil === 'professor') {

            $professorId = $usuario->getAuthIdentifier();

            $alunos = Aluno::all();

            $contexto .= "DADOS DOS ALUNOS DA PLATAFORMA:\n";

            foreach ($alunos as $aluno) {

                $matriculas = Matricula::where('id_aluno', $aluno->id_aluno)->get();

                $cursosNomes = [];

                foreach ($matriculas as $matricula) {

                    $curso = Curso::find($matricula->id_curso);
                    $nivel = Nivel::find($matricula->id_nivel);

                    $cursosNomes[] = trim(
                        ($curso->nome_curso ?? 'Curso') .
                        ' - ' .
                        ($nivel->nome_nivel ?? 'Nível')
                    );
                }

                $presencas = Presenca::where('id_aluno', $aluno->id_aluno)->get();
                $totalPresencas = $presencas->count();
                $totalPresente = $presencas->where('status_presenca', 'PRESENTE')->count();
                $percPresenca = $totalPresencas > 0
                    ? round(($totalPresente / $totalPresencas) * 100)
                    : 0;

                $contexto .=
                    "- Aluno: {$aluno->nome_aluno} | " .
                    "Email: {$aluno->email_aluno} | " .
                    "Curso: " . implode(', ', $cursosNomes) . " | " .
                    "Nível: {$aluno->nivel_aluno} | " .
                    "Presença: {$percPresenca}% | " .
                    "Status: {$aluno->status_aluno}\n";
            }

            $contexto .= "\n";

            return $contexto;
        }

        /*
        |--------------------------------------------------------------------------
        | ALUNO - consulta progresso, nível, materiais, aulas
        |--------------------------------------------------------------------------
        */

        if ($perfil === 'aluno') {

            $alunoId = $usuario->getAuthIdentifier();

            $aluno = Aluno::find($alunoId);

            if (!$aluno) {
                return "Dados do aluno não encontrados.\n";
            }

            $contexto .= "DADOS DO ALUNO:\n";
            $contexto .= "- Nome: {$aluno->nome_aluno}\n";
            $contexto .= "- Email: {$aluno->email_aluno}\n";
            $contexto .= "- Curso: {$aluno->curso_aluno}\n";
            $contexto .= "- Nível: {$aluno->nivel_aluno}\n";
            $contexto .= "- Status: {$aluno->status_aluno}\n\n";

            // Matrículas
            $matriculas = Matricula::where('id_aluno', $alunoId)->get();

            $contexto .= "MATRÍCULAS:\n";

            foreach ($matriculas as $matricula) {

                $curso = Curso::find($matricula->id_curso);
                $nivel = Nivel::find($matricula->id_nivel);

                $contexto .=
                    "- Curso: " . ($curso->nome_curso ?? 'Curso') .
                    " | Nível: " . ($nivel->nome_nivel ?? 'Nível') .
                    " | Data: {$matricula->data_matricula}" .
                    " | Status: {$matricula->status_matricula}\n";
            }

            $contexto .= "\n";

            // Aulas do aluno (baseado nos cursos matriculados)
            $idCursos = $matriculas->pluck('id_curso');

            $aulas = Aula::with('professor')
                ->whereIn('id_curso', $idCursos)
                ->orderBy('data_aulas')
                ->orderBy('hora_aulas')
                ->get();

            if ($aulas->isEmpty()) {
                $aulas = Aula::with('professor')
                    ->orderBy('data_aulas')
                    ->orderBy('hora_aulas')
                    ->get();
            }

            $contexto .= "AULAS:\n";

            foreach ($aulas as $aula) {

                $contexto .=
                    "- {$aula->titulo_aulas} | " .
                    "Data: {$aula->data_aulas} | " .
                    "Hora: {$aula->hora_aulas} | " .
                    "Professor: " . ($aula->professor->nome_professor ?? 'N/A') . "\n";
            }

            $contexto .= "\n";

            // Materiais do curso do aluno
            $materiais = Materiais::whereIn('id_curso', $idCursos)
                ->orderByDesc('id_materiais')
                ->get();

            $contexto .= "MATERIAIS DISPONÍVEIS:\n";

            foreach ($materiais as $material) {

                $contexto .=
                    "- {$material->titulo_materiais} | " .
                    "Curso: " . ($material->curso->nome_curso ?? 'N/A') . "\n";
            }

            $contexto .= "\n";

            // Progresso - Presenças
            $presencas = Presenca::where('id_aluno', $alunoId)->get();
            $totalAulas = $presencas->count();
            $totalPresente = $presencas->where('status_presenca', 'PRESENTE')->count();
            $totalFalta = $presencas->where('status_presenca', 'FALTA')->count();
            $percPresenca = $totalAulas > 0 ? round(($totalPresente / $totalAulas) * 100) : 0;

            $contexto .= "PROGRESSO:\n";
            $contexto .= "- Total de aulas registradas: {$totalAulas}\n";
            $contexto .= "- Presenças: {$totalPresente}\n";
            $contexto .= "- Faltas: {$totalFalta}\n";
            $contexto .= "- Percentual de presença: {$percPresenca}%\n\n";

            // Progresso - Materiais vistos
            $totalMateriais = $materiais->count();
            $materiaisVistos = DB::table('tbl_progresso_materiais')
                ->where('id_aluno', $alunoId)
                ->where('status_progresso', 'CONCLUIDO')
                ->count();
            $percMateriais = $totalMateriais > 0 ? round(($materiaisVistos / $totalMateriais) * 100) : 0;

            $contexto .= "- Materiais totais: {$totalMateriais}\n";
            $contexto .= "- Materiais vistos: {$materiaisVistos}\n";
            $contexto .= "- Percentual de materiais: {$percMateriais}%\n\n";

            // Atividades
            $atividades = Atividade::whereIn('id_curso', $idCursos)
                ->orderByDesc('id_atividade')
                ->get();

            $contexto .= "ATIVIDADES:\n";

            foreach ($atividades as $atividade) {

                $respostas = AtividadeResposta::where('id_atividade', $atividade->id_atividade)
                    ->where('id_aluno', $alunoId)
                    ->get();

                $nota = $respostas->first()->nota ?? null;

                $contexto .=
                    "- {$atividade->titulo_atividade} | " .
                    "Tipo: {$atividade->tipo_atividade} | " .
                    "Entrega: {$atividade->data_entrega} | " .
                    "Nota: " . ($nota ?? 'Não respondida') . "\n";
            }

            $contexto .= "\n";

            return $contexto;
        }

        /*
        |--------------------------------------------------------------------------
        | VISITANTE - consulta professores, preços, matrículas
        |--------------------------------------------------------------------------
        */

        $professores = Professor::all();

        $contexto .= "PROFESSORES DA TRADUCA IDIOMAS:\n";

        foreach ($professores as $professor) {

            $contexto .=
                "- {$professor->nome_professor} | " .
                "Especialidade: {$professor->especialidade_professor} | " .
                "Curso: {$professor->curso_professor} | " .
                "Nível: {$professor->nivel_professor} | " .
                "Experiência: {$professor->experiencia_professor}\n";
        }

        $contexto .= "\n";

        // Serviços com preços
        $servicos = \App\Models\Servico::all();

        $contexto .= "SERVIÇOS E PREÇOS:\n";

        foreach ($servicos as $servico) {

            $contexto .=
                "- {$servico->titulo_servico} | " .
                "Preço: R$ {$servico->preco_servico} | " .
                "Idioma: {$servico->lingua_servico}\n";
        }

        $contexto .= "\n";

        // Total de alunos matriculados
        $totalMatriculas = Matricula::count();
        $totalAlunos = Aluno::count();

        $contexto .= "DADOS DA PLATAFORMA:\n";
        $contexto .= "- Total de alunos cadastrados: {$totalAlunos}\n";
        $contexto .= "- Total de matrículas ativas: {$totalMatriculas}\n";

        return $contexto;
    }

    /**
     * Monta o comportamento da IA de acordo com o perfil.
     */
    private function montarPrompt(string $perfil, string $nome, string $contexto): string
    {
        if ($perfil === 'professor') {

            return <<<PROMPT
Você é a Traduca AI, assistente virtual da plataforma Traduca Idiomas.

Você está conversando com um PROFESSOR.

Nome do professor: {$nome}

Você possui acesso aos dados reais dos alunos da plataforma abaixo:

{$contexto}

Seu objetivo é ajudar o professor com tarefas relacionadas à plataforma e ao ensino de idiomas.

Você pode ajudar com:

- consultar dados de alunos (nome, email, curso, nível, presença, status);
- organização de aulas;
- planejamento de aulas;
- criação de atividades;
- criação de exercícios;
- criação de provas;
- criação de perguntas;
- explicação de conteúdos;
- ideias para aulas;
- materiais didáticos;
- organização de cronogramas;
- sugestões para alunos;
- relatórios e análises quando os dados estiverem disponíveis;
- dúvidas sobre o funcionamento da plataforma.

Quando o professor perguntar sobre um aluno específico, use os dados reais do contexto acima.

Se o professor pedir para criar uma atividade, entregue uma atividade pronta e organizada.

Se pedir uma prova, monte questões adequadas ao nível informado.

Se não houver informações suficientes, faça uma pergunta curta antes de inventar informações.

Nunca invente dados de alunos, notas, aulas ou matrículas que não estejam no contexto.

Responda em português, salvo quando o professor solicitar outro idioma.

Seja profissional, claro e objetivo.
PROMPT;
        }

        if ($perfil === 'aluno') {

            return <<<PROMPT
Você é a Traduca AI, assistente virtual da plataforma Traduca Idiomas.

Você está conversando com um ALUNO.

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

        /*
        |--------------------------------------------------------------------------
        | Visitante
        |--------------------------------------------------------------------------
        */

        return <<<PROMPT
Você é a Traduca AI, assistente virtual do site Traduca Idiomas.

Você está conversando com um VISITANTE que ainda não está autenticado.

Nome do visitante: {$nome}

Você possui acesso aos dados reais da plataforma abaixo:

{$contexto}

Seu objetivo é apresentar informações gerais sobre a plataforma e ajudar o visitante.

Você pode ajudar com:

- informações sobre os professores da Traduca Idiomas;
- preços dos cursos e serviços;
- quantidade de alunos matriculados;
- informações gerais sobre a Traduca Idiomas;
- cursos;
- idiomas;
- funcionamento da plataforma;
- dúvidas sobre matrícula;
- dúvidas sobre aulas;
- orientação para encontrar funcionalidades do site;
- informações gerais sobre estudos de idiomas.

Quando o visitante perguntar sobre professores, preços ou matrículas, use os dados reais do contexto acima.

Como o visitante não está autenticado, você NÃO possui acesso aos dados pessoais de alunos ou professores.

Nunca invente informações específicas sobre usuários.

Se o visitante perguntar por dados privados, explique que ele precisa fazer login.

Responda de forma amigável, profissional e objetiva.

IMPORTANTE: Ao final de cada resposta, sugira 3 perguntas de acompanhamento relevantes no formato:
[SUGESTOES] ["Pergunta 1", "Pergunta 2", "Pergunta 3"] [/SUGESTOES]
As sugestões devem ser curtas, relevantes ao contexto da conversa e em português.
PROMPT;
    }

    /**
     * Obtém o nome independente do modelo utilizado.
     */
    private function nomeUsuario($usuario, string $perfil): string
    {
        if ($perfil === 'professor') {
            return trim($usuario->nome_professor ?? 'Professor');
        }

        if ($perfil === 'aluno') {
            return trim($usuario->nome_aluno ?? 'Aluno');
        }

        return 'Visitante';
    }
}
