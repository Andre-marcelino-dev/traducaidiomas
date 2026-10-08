<?php

use App\Http\Controllers\Api\V1\AlunoController;
use App\Http\Controllers\Api\V1\AulaController;
use App\Http\Controllers\Api\V1\ModuloController;
use App\Http\Controllers\Api\V1\Aluno\AgendaController as AlunoApiAgendaController;
use App\Http\Controllers\Api\V1\Aluno\AtividadeController as AlunoApiAtividadeController;
use App\Http\Controllers\Api\V1\Aluno\AuthController as AlunoApiAuthController;
use App\Http\Controllers\Api\V1\Aluno\CursoController as AlunoApiCursoController;
use App\Http\Controllers\Api\V1\Aluno\JustificativaController as AlunoApiJustificativaController;
use App\Http\Controllers\Api\V1\Aluno\PerfilController as AlunoApiPerfilController;
use App\Http\Controllers\Api\V1\Aluno\ReagendamentoController as AlunoApiReagendamentoController;
use App\Http\Controllers\Api\V1\Professor\AuthController as ProfessorApiAuthController;
use Illuminate\Support\Facades\Route;

// Toda a API: limite de requisições por token/IP (ver AppServiceProvider).
Route::prefix('v1')->middleware('throttle:api')->group(function () {

    // ── App do aluno (token Sanctum de aluno) ──
    Route::prefix('aluno')->name('api.aluno.')->group(function () {
        Route::post('/login', [AlunoApiAuthController::class, 'login'])->middleware('throttle:login')->name('login');

        Route::middleware(['auth:sanctum', 'token:aluno'])->group(function () {
            Route::post('/logout', [AlunoApiAuthController::class, 'logout'])->name('logout');
            Route::get('/me', [AlunoApiAuthController::class, 'me'])->name('me');

            Route::get('/perfil', [AlunoApiPerfilController::class, 'show'])->name('perfil.show');
            Route::put('/perfil/email', [AlunoApiPerfilController::class, 'email'])->name('perfil.email');
            Route::put('/perfil/senha', [AlunoApiPerfilController::class, 'senha'])->middleware('throttle:login')->name('perfil.senha');
            Route::post('/perfil/foto', [AlunoApiPerfilController::class, 'foto'])->name('perfil.foto');

            Route::get('/cursos', [AlunoApiCursoController::class, 'index'])->name('cursos.index');
            Route::get('/cursos/{idCurso}/modulos', [AlunoApiCursoController::class, 'modulos'])->name('cursos.modulos');
            Route::get('/cursos/{idCurso}/materiais', [AlunoApiCursoController::class, 'materiais'])->name('cursos.materiais');
            Route::get('/cursos/{idCurso}/desempenho', [AlunoApiCursoController::class, 'desempenho'])->name('cursos.desempenho');
            Route::post('/presencas/{idPresenca}/justificar', [AlunoApiJustificativaController::class, 'store'])->name('presencas.justificar');
            Route::get('/modulos/{idModulo}', [AlunoApiCursoController::class, 'modulo'])->name('modulos.show');
            Route::get('/materiais/{idMaterial}/download', [AlunoApiCursoController::class, 'downloadMaterial'])->name('materiais.download');
            Route::get('/agenda', [AlunoApiAgendaController::class, 'index'])->name('agenda.index');
            Route::post('/reagendamento/solicitar', [AlunoApiReagendamentoController::class, 'solicitar'])->name('reagendamento.solicitar');

            Route::get('/atividades', [AlunoApiAtividadeController::class, 'index'])->name('atividades.index');
            Route::get('/atividades/{id}', [AlunoApiAtividadeController::class, 'show'])->whereNumber('id')->name('atividades.show');
            Route::get('/atividades/{id}/audio', [AlunoApiAtividadeController::class, 'audio'])->whereNumber('id')->name('atividades.audio');
            Route::post('/atividades/{id}/responder', [AlunoApiAtividadeController::class, 'responder'])->whereNumber('id')->name('atividades.responder');
        });
    });

    // ── Professor (token Sanctum de professor) ──
    Route::prefix('professor')->name('api.professor.')->group(function () {
        Route::post('/login', [ProfessorApiAuthController::class, 'login'])->middleware('throttle:login')->name('login');

        Route::middleware(['auth:sanctum', 'token:professor'])->group(function () {
            Route::post('/logout', [ProfessorApiAuthController::class, 'logout'])->name('logout');
            Route::get('/me', [ProfessorApiAuthController::class, 'me'])->name('me');
        });
    });

    // ── Gestão (antes era pública; agora exige token de professor) ──
    Route::middleware(['auth:sanctum', 'token:professor'])->group(function () {

        // Alunos e módulos: somente professor administrador
        Route::middleware('token:admin')->group(function () {
            Route::get('/alunos', [AlunoController::class, 'index']);
            Route::get('/alunos/{id}', [AlunoController::class, 'show']);
            Route::post('/alunos', [AlunoController::class, 'store']);
            Route::put('/alunos/{id}', [AlunoController::class, 'update']);
            Route::patch('/alunos/{id}/status', [AlunoController::class, 'updateStatus']);
            Route::delete('/alunos/{id}', [AlunoController::class, 'destroy']);

            Route::post('/modulos', [ModuloController::class, 'store']);
            Route::put('/modulos/{id}', [ModuloController::class, 'update']);
            Route::delete('/modulos/{id}', [ModuloController::class, 'destroy']);
        });

        Route::get('/aulas/resumo', [AulaController::class, 'resumo']);
        Route::get('/aulas', [AulaController::class, 'index']);
        Route::get('/aulas/{id}', [AulaController::class, 'show']);
        Route::post('/aulas', [AulaController::class, 'store']);
        Route::put('/aulas/{id}', [AulaController::class, 'update']);
        Route::delete('/aulas/{id}', [AulaController::class, 'destroy']);

        Route::get('/modulos', [ModuloController::class, 'index']);
        Route::get('/modulos/{id}', [ModuloController::class, 'show']);
    });
});
