<?php

use App\Http\Controllers\Api\V1\AlunoController;
use App\Http\Controllers\Api\V1\AulaController;
use App\Http\Controllers\Api\V1\ModuloController;
use App\Http\Controllers\Api\V1\Aluno\AuthController as AlunoApiAuthController;
use App\Http\Controllers\Api\V1\Aluno\CursoController as AlunoApiCursoController;
use Illuminate\Support\Facades\Route;

// ── App do aluno (autenticado via Sanctum) ──
Route::prefix('v1/aluno')->name('api.aluno.')->group(function () {
    Route::post('/login', [AlunoApiAuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AlunoApiAuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AlunoApiAuthController::class, 'me'])->name('me');

        Route::get('/cursos', [AlunoApiCursoController::class, 'index'])->name('cursos.index');
        Route::get('/cursos/{idCurso}/modulos', [AlunoApiCursoController::class, 'modulos'])->name('cursos.modulos');
    });
});

Route::prefix('v1')->group(function () {
    Route::get('/alunos', [AlunoController::class, 'index']);
    Route::get('/alunos/{id}', [AlunoController::class, 'show']);
    Route::post('/alunos', [AlunoController::class, 'store']);
    Route::put('/alunos/{id}', [AlunoController::class, 'update']);
    Route::patch('/alunos/{id}/status', [AlunoController::class, 'updateStatus']);
    Route::delete('/alunos/{id}', [AlunoController::class, 'destroy']);



Route::get('/aulas/resumo', [AulaController::class, 'resumo']);
Route::get('/aulas', [AulaController::class, 'index']);
Route::get('/aulas/{id}', [AulaController::class, 'show']);
Route::post('/aulas', [AulaController::class, 'store']);
Route::put('/aulas/{id}', [AulaController::class, 'update']);
Route::delete('/aulas/{id}', [AulaController::class, 'destroy']);




Route::get('/modulos', [ModuloController::class, 'index']);
Route::get('/modulos/{id}', [ModuloController::class, 'show']);
Route::post('/modulos', [ModuloController::class, 'store']);
Route::put('/modulos/{id}', [ModuloController::class, 'update']);
Route::delete('/modulos/{id}', [ModuloController::class, 'destroy']);

});