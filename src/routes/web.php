<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\DashController;
use App\Http\Controllers\admin\ProfessorController;
use App\Http\Controllers\admin\AlunoController;
use App\Http\Controllers\admin\AgendaController;
use App\Http\Controllers\admin\AulaController;
use App\Http\Controllers\admin\ServicoController as adminServicoController;
use App\Http\Controllers\admin\MatriculaController; // Importação unificada com o 'admin' minúsculo
use App\Http\Controllers\aluno\AuthController as AlunoAuthController;
use App\Http\Controllers\aluno\DashController as AlunoDashController;
use Illuminate\Support\Facades\Route;

// ── Rotas Públicas do Site ──
Route::get("/", [HomeController::class, 'home'])->name('home');
Route::get("/sobre", [SobreController::class, 'sobre'])->name('sobre');
Route::get("/servicos", [ServicosController::class, 'servicos'])->name('servicos');
Route::get("/servicos/categoria/{id}", [ServicosController::class, 'servicos'])->name('servicos.categoria');
Route::get("/quiz", [QuizController::class, 'quiz'])->name('quiz');
Route::get("/contato", [ContatoController::class, 'contato'])->name('contato');
Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos');

// ── Rotas do Administrador (Admin) ──
Route::prefix('admin')->name('admin.')->group(function () {

    // Autenticação Admin (Públicas)
    Route::get('/login',  [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'autenticar'])->name('autenticar');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Painel Admin (Protegidas por Middleware)
    Route::middleware('auth:admin')->group(function () {

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
            Route::get('/{id}/edit',        [AgendaController::class, 'edit'])->name('edit');
            Route::get('/{id}',             [AgendaController::class, 'show'])->name('show');
            Route::put('/{id}',             [AgendaController::class, 'update'])->name('update');
            Route::delete('/{id}',          [AgendaController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/reagendar', [AgendaController::class, 'solicitarReagendamento'])->name('reagendar');
            Route::get('/aluno/{id}',       [AgendaController::class, 'porAluno'])->name('porAluno');
            Route::get('/professor/{id}',   [AgendaController::class, 'porProfessor'])->name('porProfessor');
        });

        // CRUD Matrículas (Agora completo, padronizado e protegido por login!)
        Route::prefix('matriculas')->name('matriculas.')->group(function () {
            Route::get('/',          [MatriculaController::class, 'index'])->name('index');
            Route::post('/',         [MatriculaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [MatriculaController::class, 'edit'])->name('edit');
            Route::put('/{id}',      [MatriculaController::class, 'update'])->name('update');
            Route::delete('/{id}',   [MatriculaController::class, 'destroy'])->name('destroy');
        });

        // Recursos Adicionais do Admin (Aulas e Serviços)
        Route::resource('aulas', AulaController::class)->parameters(['aulas' => 'id']);
        Route::resource('servicos', adminServicoController::class)->parameters(['servicos' => 'id']);

    });

});

// ── Rotas do Aluno ──
Route::prefix('aluno')->name('aluno.')->group(function () {

    // Autenticação Aluno (Públicas)
    Route::get('/login',  [AlunoAuthController::class, 'login'])->name('login');
    Route::post('/login', [AlunoAuthController::class, 'autenticar'])->name('autenticar');
    Route::post('/logout', [AlunoAuthController::class, 'logout'])->name('logout');

    // Painel Aluno (Protegidas por Middleware)
    Route::middleware('auth:aluno')->group(function () {
        Route::get('/',       [AlunoDashController::class, 'index'])->name('dash');
        Route::get('/perfil', [AlunoAuthController::class, 'perfil'])->name('perfil');
    });

});