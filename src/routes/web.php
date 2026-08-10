<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ContatoController;


// =====================================================
// CONTROLLERS DO ALUNO
// =====================================================

use App\Http\Controllers\aluno\ReagendamentoController as AlunoReagendamentoController;
use App\Http\Controllers\aluno\AtividadeController as AlunoAtividadeController;
use App\Http\Controllers\aluno\AuthController as AlunoAuthController;
use App\Http\Controllers\aluno\DashController as AlunoDashController;
use App\Http\Controllers\aluno\MateriaisController as AlunoMateriaisController;
use App\Http\Controllers\aluno\ChatbotController as AlunoChatbotController;
use App\Http\Controllers\aluno\AulaController as AlunoAulaController;
use App\Http\Controllers\aluno\ProgressoController as AlunoProgressoController;
use App\Http\Controllers\aluno\FeedbackController as AlunoFeedbackController;


// =====================================================
// CONTROLLERS DO ADMIN
// =====================================================

use App\Http\Controllers\admin\ReagendamentoController as AdminReagendamentoController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\DashController;
use App\Http\Controllers\admin\ProfessorController;
use App\Http\Controllers\admin\AlunoController;
use App\Http\Controllers\admin\AgendaController;
use App\Http\Controllers\admin\AulaController;
use App\Http\Controllers\admin\ServicoController as adminServicoController;
use App\Http\Controllers\admin\MatriculaController;
use App\Http\Controllers\admin\MateriaisController as AdminMateriaisController;
use App\Http\Controllers\admin\PresencaController;
use App\Http\Controllers\admin\SiteController;
use App\Http\Controllers\admin\AtividadeController as AdminAtividadeController;
use App\Http\Controllers\admin\ProfessorChatbotController;

// ── Rotas Públicas do Site ──
Route::get("/", [HomeController::class, 'home'])->name('home');
Route::get("/sobre", [SobreController::class, 'sobre'])->name('sobre');
Route::get("/servicos", [ServicosController::class, 'servicos'])->name('servicos');
Route::get("/servicos/categoria/{id}", [ServicosController::class, 'servicos'])->name('servicos.categoria');
Route::get("/quiz", [QuizController::class, 'quiz'])->name('quiz');
Route::get("/contato", [ContatoController::class, 'contato'])->name('contato');
Route::post("/contato", [ContatoController::class, 'enviar'])->name('contato.enviar');

// ── Rotas do Administrador (Admin) ──
Route::prefix('admin')->name('admin.')->group(function () {

    // Autenticação Admin (Públicas)
    Route::get('/login',  [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'autenticar'])->name('autenticar');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Primeiro Acesso
    Route::get('/verificar',          [AuthController::class, 'verificar'])->name('verificar');
    Route::post('/verificar',         [AuthController::class, 'verificarAcesso'])->name('verificar.acesso');
    Route::get('/criar-credenciais',  [AuthController::class, 'criarCredenciais'])->name('criar-credenciais');
    Route::post('/criar-credenciais', [AuthController::class, 'salvarCredenciais'])->name('salvar-credenciais');

    // Recuperação de Senha
    Route::get('/recuperar-senha',  [AuthController::class, 'recuperarSenha'])->name('recuperar-senha');
    Route::post('/recuperar-senha', [AuthController::class, 'processarRecuperacao'])->name('processar-recuperacao');
    Route::get('/redefinir-senha',  [AuthController::class, 'redefinirSenha'])->name('redefinir-senha');
    Route::post('/redefinir-senha', [AuthController::class, 'salvarNovaSenha'])->name('salvar-nova-senha');

    // Painel Admin (Protegidas por Middleware)
    Route::middleware(['auth:admin', 'session.timeout:admin'])->group(function () {

        Route::get('/',           [DashController::class, 'index'])->name('dash');
        Route::get('/categorias', [DashController::class, 'index'])->name('categoria');

        // CRUD Professores
        Route::prefix('professores')->name('professores.')->group(function () {
            Route::get('/',          [ProfessorController::class, 'index'])->name('index');
            Route::post('/',         [ProfessorController::class, 'store'])->name('store');
            Route::get('/create',    [ProfessorController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [ProfessorController::class, 'edit'])->name('edit');
            Route::get('/{id}',      [ProfessorController::class, 'show'])->name('show');
            Route::put('/{id}',      [ProfessorController::class, 'update'])->name('update');
            Route::delete('/{id}',   [ProfessorController::class, 'destroy'])->name('destroy');
        });

        // CRUD Alunos
        Route::prefix('alunos')->name('alunos.')->group(function () {
            Route::get('/',            [AlunoController::class, 'index'])->name('index');
            Route::post('/',           [AlunoController::class, 'store'])->name('store');
            Route::get('/create',      [AlunoController::class, 'create'])->name('create');
            Route::get('/{id}/edit',   [AlunoController::class, 'edit'])->name('edit');
            Route::get('/{id}',        [AlunoController::class, 'show'])->name('show');
            Route::put('/{id}',        [AlunoController::class, 'update'])->name('update');
            Route::delete('/{id}',     [AlunoController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/status', [AlunoController::class, 'updateStatus'])->name('updateStatus');
        });

        // CRUD Agendas
        Route::prefix('agendas')->name('agendas.')->group(function () {
            Route::get('/',                 [AgendaController::class, 'index'])->name('index');
            Route::post('/',                [AgendaController::class, 'store'])->name('store');
            Route::get('/create',           [AgendaController::class, 'create'])->name('create');
            Route::get('/eventos',          [AgendaController::class, 'eventos'])->name('eventos');
            Route::get('/{id}/edit',        [AgendaController::class, 'edit'])->name('edit');
            Route::get('/{id}',             [AgendaController::class, 'show'])->name('show');
            Route::put('/{id}',             [AgendaController::class, 'update'])->name('update');
            Route::delete('/{id}',          [AgendaController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/reagendar', [AgendaController::class, 'solicitarReagendamento'])->name('reagendar');
            Route::get('/aluno/{id}',       [AgendaController::class, 'porAluno'])->name('porAluno');
            Route::get('/professor/{id}',   [AgendaController::class, 'porProfessor'])->name('porProfessor');
        });

        // CRUD Matrículas
        Route::prefix('matriculas')->name('matriculas.')->group(function () {
            Route::get('/',            [MatriculaController::class, 'index'])->name('index');
            Route::post('/',           [MatriculaController::class, 'store'])->name('store');
            Route::get('/{id}/edit',   [MatriculaController::class, 'edit'])->name('edit');
            Route::put('/{id}',        [MatriculaController::class, 'update'])->name('update');
            Route::delete('/{id}',     [MatriculaController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/status', [MatriculaController::class, 'updateStatus'])->name('updateStatus');
        });

        // CRUD Materiais
        Route::prefix('materiais')->name('materiais.')->group(function () {
            Route::get('/',              [AdminMateriaisController::class, 'index'])->name('index');
            Route::get('/{id}/download', [AdminMateriaisController::class, 'download'])->name('download');
            Route::post('/',             [AdminMateriaisController::class, 'store'])->name('store');
            Route::get('/create',        [AdminMateriaisController::class, 'create'])->name('create');
            Route::get('/{id}/edit',     [AdminMateriaisController::class, 'edit'])->name('edit');
            Route::get('/{id}',          [AdminMateriaisController::class, 'show'])->name('show');
            Route::put('/{id}',          [AdminMateriaisController::class, 'update'])->name('update');
            Route::delete('/{id}',       [AdminMateriaisController::class, 'destroy'])->name('destroy');
        });

        // Presença
        Route::prefix('presenca')->name('presenca.')->group(function () {
            Route::get('/',                  [PresencaController::class, 'index'])->name('index');
            Route::get('/{id_aulas}/alunos', [PresencaController::class, 'alunos'])->name('alunos');
            Route::post('/salvar',           [PresencaController::class, 'salvar'])->name('salvar');
        });

        // Recursos Adicionais (Aulas e Serviços)
        Route::resource('aulas', AulaController::class)->parameters(['aulas' => 'id']);
        Route::resource('servicos', adminServicoController::class)->parameters(['servicos' => 'id']);

        // Reagendamentos (Admin)
        Route::prefix('reagendamentos')->name('reagendamentos.')->group(function () {
            Route::get('/',                        [AdminReagendamentoController::class, 'index'])->name('index');
            Route::post('/',                       [AdminReagendamentoController::class, 'store'])->name('store');
            Route::get('/{reagendamento}',         [AdminReagendamentoController::class, 'show'])->name('show');
            Route::put('/{reagendamento}/aceitar', [AdminReagendamentoController::class, 'aceitar'])->name('aceitar');
            Route::put('/{reagendamento}/recusar', [AdminReagendamentoController::class, 'recusar'])->name('recusar');
            Route::delete('/{reagendamento}',      [AdminReagendamentoController::class, 'destroy'])->name('destroy');
        });
        Route::get('reagendamento/notificacoes', [AdminReagendamentoController::class, 'contarNotificacoes'])
            ->name('reagendamento.notificacoes');

        // Atividades
        Route::prefix('atividades')->name('atividades.')->group(function () {
            Route::get('/',              [AdminAtividadeController::class, 'index'])->name('index');
            Route::get('/create',        [AdminAtividadeController::class, 'create'])->name('create');
            Route::post('/',             [AdminAtividadeController::class, 'store'])->name('store');
            Route::get('/{id}',          [AdminAtividadeController::class, 'show'])->name('show');
            Route::delete('/{id}',       [AdminAtividadeController::class, 'destroy'])->name('destroy');
            Route::put('/corrigir/{id}', [AdminAtividadeController::class, 'corrigir'])->name('corrigir');
        });

        // Gerenciamento do Site (Protegido)
        Route::prefix('site')->name('site.')->group(function () {
            Route::get('/', [SiteController::class, 'index'])->name('index');
            Route::put('/', [SiteController::class, 'update'])->name('update');
        });

        // Chatbot IA Professor
        // Chatbot IA Professor
        // =====================================================
        // CHATBOT PROFESSOR
        // =====================================================

        Route::prefix('professor')
            ->name('professor.')
            ->group(function () {

                Route::get(
                    '/chatbot/dados',
                    [ProfessorChatbotController::class, 'dados']
                )->name('chatbot.dados');

                Route::post(
                    '/chatbot/mensagem',
                    [ProfessorChatbotController::class, 'mensagem']
                )->name('chatbot.mensagem');
            });
    });
});


// ── Rotas do Aluno ──
Route::prefix('aluno')->name('aluno.')->group(function () {

    // Autenticação Aluno (Públicas)
    Route::get('/login',  [AlunoAuthController::class, 'login'])->name('login');
    Route::post('/login', [AlunoAuthController::class, 'autenticar'])->name('autenticar');
    Route::post('/logout', [AlunoAuthController::class, 'logout'])->name('logout');

    // Painel Aluno (Protegidas por Middleware)
    Route::middleware(['auth:aluno', 'session.timeout:aluno'])->group(function () {

        Route::get('/',       [AlunoDashController::class, 'index'])->name('dash');
        Route::get('/perfil', [AlunoAuthController::class, 'perfil'])->name('perfil');
        Route::put('/perfil/foto',  [AlunoAuthController::class, 'atualizarFoto'])->name('perfil.foto');
        Route::put('/perfil/email', [AlunoAuthController::class, 'atualizarEmail'])->name('perfil.email');
        Route::put('/perfil/senha', [AlunoAuthController::class, 'atualizarSenha'])->name('perfil.senha');

        // Minhas Aulas & Atividades
        Route::get('/aulas',            [AlunoAulaController::class, 'index'])->name('aulas.index');
        Route::get('/progresso',        [AlunoProgressoController::class, 'index'])->name('progresso.index');
        Route::get('/atividades',       [AlunoAtividadeController::class, 'index'])->name('atividades.index');
        Route::get('/atividades/{id}',  [AlunoAtividadeController::class, 'show'])->name('atividades.show');
        Route::post('/atividades/{id}/responder', [AlunoAtividadeController::class, 'responder'])->name('atividades.responder');

        // Materiais (somente leitura)
        Route::get('/materiais',               [AlunoMateriaisController::class, 'index'])->name('materiais.index');
        Route::get('/materiais/{id}',          [AlunoMateriaisController::class, 'show'])->name('materiais.show');
        Route::get('/materiais/{id}/download', [AlunoMateriaisController::class, 'download'])->name('materiais.download');
        Route::get('/materiais/{id}/visualizar', [AlunoMateriaisController::class, 'verArquivo'])->name('materiais.visualizar');

        // Chatbot Aluno
        // =====================================================
        // CHATBOT ALUNO
        // =====================================================

        Route::get(
            '/chatbot/dados',
            [AlunoChatbotController::class, 'dados']
        )->name('chatbot.dados');

        Route::post(
            '/chatbot/mensagem',
            [AlunoChatbotController::class, 'mensagem']
        )->name('chatbot.mensagem');
        // Feedback
        Route::post('feedback', [AlunoFeedbackController::class, 'store'])->name('feedback.store');

        // Reagendamentos (Aluno)
        Route::post('reagendamento/solicitar', [AlunoReagendamentoController::class, 'solicitar'])
            ->name('reagendamento.solicitar');
        Route::get('reagendamentos', [AlunoReagendamentoController::class, 'meusSolicitados'])
            ->name('reagendamentos.index');
        Route::get('reagendamento/notificacoes', [AlunoReagendamentoController::class, 'contarNotificacoes'])
            ->name('reagendamento.notificacoes');
    });
});

// =====================================================
// CHATBOT PÚBLICO / VISITANTE
// =====================================================

Route::get('/chatbot/dados', function () {

    return response()->json([
        'success' => true,
        'perfil' => 'visitante',
        'usuario' => null,
        'nome' => 'Visitante',
    ]);
})->name('chatbot.dados');


Route::post('/chatbot/mensagem', function (\Illuminate\Http\Request $request) {

    $request->validate([
        'mensagem' => 'required|string|max:4000',
    ]);

    $mensagem = trim($request->input('mensagem'));

    $apiKey = config('services.groq.key');

    if (!$apiKey) {

        return response()->json([
            'success' => false,
            'message' => 'A chave da API da Groq não está configurada.',
        ], 500);
    }

    $model = config(
        'services.groq.model',
        env('GROQ_MODEL', 'llama-3.1-8b-instant')
    );

    /*
    |--------------------------------------------------------------------------
    | Contexto real do banco de dados para o visitante
    |--------------------------------------------------------------------------
    */

    $contexto = "";

    // Professores
    $professores = \App\Models\Professor::all();

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
    $totalMatriculas = \App\Models\Matricula::count();
    $totalAlunos = \App\Models\Aluno::count();

    $contexto .= "DADOS DA PLATAFORMA:\n";
    $contexto .= "- Total de alunos cadastrados: {$totalAlunos}\n";
    $contexto .= "- Total de matrículas ativas: {$totalMatriculas}\n";

    $prompt = "Você é a Traduca AI, assistente virtual do site Traduca Idiomas.

Você está conversando com um VISITANTE.

Você possui acesso aos dados reais da plataforma abaixo:

{$contexto}

Ajude com informações gerais sobre:

- professores da Traduca Idiomas;
- preços dos cursos e serviços;
- quantidade de alunos matriculados;
- Traduca Idiomas;
- cursos;
- idiomas;
- aulas;
- matrícula;
- funcionamento da plataforma;
- estudos de idiomas;
- informações gerais sobre o site.

Quando o visitante perguntar sobre professores, preços ou matrículas, use os dados reais do contexto acima.

O visitante não está autenticado.

Você NÃO possui acesso a dados privados de alunos ou professores.

Nunca invente dados pessoais, notas, matrículas ou informações privadas.

Se o visitante perguntar por informações privadas, explique que ele precisa fazer login.

Responda em português.

Seja amigável, profissional, claro e objetivo.";

    try {

        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
            ->timeout(30)
            ->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' => $model,

                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $prompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $mensagem,
                        ],
                    ],

                    'temperature' => 0.7,
                    'max_tokens' => 800,
                ]
            );

        if (!$response->successful()) {

            \Illuminate\Support\Facades\Log::error(
                'Erro Groq visitante',
                [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'A IA não conseguiu processar a mensagem.',
            ], 500);
        }

        $data = $response->json();

        $resposta = $data['choices'][0]['message']['content']
            ?? 'Não consegui gerar uma resposta no momento.';

        return response()->json([
            'success' => true,
            'perfil' => 'visitante',
            'nome' => 'Visitante',
            'text' => trim($resposta),
            'resposta' => trim($resposta),
        ]);
    } catch (\Throwable $e) {

        \Illuminate\Support\Facades\Log::error(
            'Erro chatbot visitante',
            [
                'erro' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine(),
            ]
        );

        return response()->json([
            'success' => false,
            'message' => 'Erro interno ao comunicar com a IA.',
        ], 500);
    }
})->name('chatbot.mensagem');
