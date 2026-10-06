<?php

use App\Http\Controllers\Api\V1\AlunoController;
use App\Http\Controllers\Api\V1\AulaController;
use App\Http\Controllers\Api\V1\ModuloController;
use App\Http\Controllers\Api\V1\Aluno\AgendaController as AlunoApiAgendaController;
use App\Http\Controllers\Api\V1\Aluno\AtividadeController as AlunoApiAtividadeController;
use App\Http\Controllers\Api\V1\Aluno\AuthController as AlunoApiAuthController;
use App\Http\Controllers\Api\V1\Aluno\CursoController as AlunoApiCursoController;
use App\Http\Controllers\Api\V1\Aluno\ReagendamentoController as AlunoApiReagendamentoController;
use App\Http\Controllers\Api\V1\Professor\AuthController as ProfessorApiAuthController;
use Illuminate\Support\Facades\Route;

// Toda a API, exceto os logins, usa limite de requisições por token/IP.

Route::prefix('v1')->group(function () {

    // ── App do aluno ──

    Route::prefix('aluno')->name('api.aluno.')->group(function () {

        // Login possui seu próprio rate limit.

<<<<<<< HEAD
        Route::post('/login', [AlunoApiAuthController::class, 'login'])
            ->middleware('throttle:login')
            ->name('login');

        Route::middleware([
            'auth:sanctum',
            'token:aluno',
            'throttle:api',
        ])->group(function () {

            Route::post('/logout', [AlunoApiAuthController::class, 'logout'])
                ->name('logout');

            Route::get('/me', [AlunoApiAuthController::class, 'me'])
                ->name('me');

            Route::get('/cursos', [AlunoApiCursoController::class, 'index'])
                ->name('cursos.index');

            Route::get('/cursos/{idCurso}/modulos', [AlunoApiCursoController::class, 'modulos'])
                ->name('cursos.modulos');

            // Lista todas as aulas ativas do curso do aluno,
            // inclusive aulas que ainda não possuem módulo.
            Route::get('/cursos/{idCurso}/aulas', [AlunoApiCursoController::class, 'aulas'])
                ->name('cursos.aulas');

            Route::get('/cursos/{idCurso}/materiais', [AlunoApiCursoController::class, 'materiais'])
                ->name('cursos.materiais');

            Route::get('/modulos/{idModulo}', [AlunoApiCursoController::class, 'modulo'])
                ->name('modulos.show');

            Route::get('/materiais/{idMaterial}/download', [AlunoApiCursoController::class, 'downloadMaterial'])
                ->name('materiais.download');
=======
            Route::get('/cursos', [AlunoApiCursoController::class, 'index'])->name('cursos.index');
            Route::get('/cursos/{idCurso}/modulos', [AlunoApiCursoController::class, 'modulos'])->name('cursos.modulos');
            Route::get('/cursos/{idCurso}/materiais', [AlunoApiCursoController::class, 'materiais'])->name('cursos.materiais');
            Route::get('/modulos/{idModulo}', [AlunoApiCursoController::class, 'modulo'])->name('modulos.show');
            Route::get('/materiais/{idMaterial}/download', [AlunoApiCursoController::class, 'downloadMaterial'])->name('materiais.download');
            Route::get('/agenda', [AlunoApiAgendaController::class, 'index'])->name('agenda.index');
            Route::post('/reagendamento/solicitar', [AlunoApiReagendamentoController::class, 'solicitar'])->name('reagendamento.solicitar');

            Route::get('/atividades', [AlunoApiAtividadeController::class, 'index'])->name('atividades.index');
            Route::get('/atividades/{id}', [AlunoApiAtividadeController::class, 'show'])->whereNumber('id')->name('atividades.show');
            Route::get('/atividades/{id}/audio', [AlunoApiAtividadeController::class, 'audio'])->whereNumber('id')->name('atividades.audio');
            Route::post('/atividades/{id}/responder', [AlunoApiAtividadeController::class, 'responder'])->whereNumber('id')->name('atividades.responder');
>>>>>>> 6f578f56ecd30dc7830ded11ab7a911becaaacd1
        });
    });

    // ── Professor ──

    Route::prefix('professor')->name('api.professor.')->group(function () {

        // Login possui seu próprio rate limit.

        Route::post('/login', [ProfessorApiAuthController::class, 'login'])
            ->middleware('throttle:login')
            ->name('login');

        Route::middleware([
            'auth:sanctum',
            'token:professor',
            'throttle:api',
        ])->group(function () {

            Route::post('/logout', [ProfessorApiAuthController::class, 'logout'])
                ->name('logout');

            Route::get('/me', [ProfessorApiAuthController::class, 'me'])
                ->name('me');
        });
    });

    // ── Gestão ──

    Route::middleware([
        'auth:sanctum',
        'token:professor',
        'throttle:api',
    ])->group(function () {

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