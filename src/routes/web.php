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
use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class, 'home'])->name('home');
Route::get("/sobre", [SobreController::class, 'sobre'])->name('sobre');
Route::get("/servicos", [ServicosController::class, 'servicos'])->name('servicos');
Route::get("/servicos/categoria/{id}", [ServicosController::class, 'servicos'])->name('servicos.categoria');
Route::get("/quiz", [QuizController::class, 'quiz'])->name('quiz');
Route::get("/contato", [ContatoController::class, 'contato'])->name('contato');
Route::get('/alunos', [AlunoController::class, 'index'])->name('alunos');

// ── Rotas do Admin ──
Route::prefix('admin')->name('admin.')->group(function () {

    // Públicas
    Route::get('/login',  [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'autenticar'])->name('autenticar');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    // Protegidas
    Route::middleware('auth:admin')->group(function () {

        Route::get('/',          [DashController::class, 'index'])->name('dash');
        Route::get('/categorias',[DashController::class, 'index'])->name('categoria');

        Route::prefix('professores')->name('professores.')->group(function () {
            Route::get('/',          [ProfessorController::class, 'index'])->name('index');
            Route::post('/',         [ProfessorController::class, 'store'])->name('store');
            Route::get('/create',    [ProfessorController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [ProfessorController::class, 'edit'])->name('edit');
            Route::get('/{id}',      [ProfessorController::class, 'show'])->name('show');
            Route::put('/{id}',      [ProfessorController::class, 'update'])->name('update');
            Route::delete('/{id}',   [ProfessorController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('alunos')->name('alunos.')->group(function () {
            Route::get('/',          [AlunoController::class, 'index'])->name('index');
            Route::post('/',         [AlunoController::class, 'store'])->name('store');
            Route::get('/create',    [AlunoController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [AlunoController::class, 'edit'])->name('edit');
            Route::get('/{id}',      [AlunoController::class, 'show'])->name('show');
            Route::put('/{id}',      [AlunoController::class, 'update'])->name('update');
            Route::delete('/{id}',   [AlunoController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/status',     [AlunoController::class, 'updateStatus'])->name('updateStatus'); // <-- adicione esta
        });

    });
});
// Aulas
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::resource('aulas', \App\Http\Controllers\admin\AulaController::class)->parameters(['aulas' => 'id']);
});
