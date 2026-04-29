<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ContatoController;


use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class,'home'])->name('home');
Route::get("/sobre", [SobreController::class,'sobre'])->name('sobre');
// Mude de ->name('servicos') para ->name('servicos.index')
// Rota principal de serviços (o que você chama no menu)
Route::get("/servicos", [ServicosController::class, 'servicos'])->name('servicos');

// Rota dinâmica para as categorias do dropdown
Route::get("/servicos/categoria/{id}", [ServicosController::class, 'servicos'])->name('servicos.categoria');


Route::get("/quiz", [QuizController::class,'quiz'])->name('quiz');
Route::get("/contato", [ContatoController::class,'contato'])->name('contato');



