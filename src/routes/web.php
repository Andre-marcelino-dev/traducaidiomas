<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\SobreController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ContatoController;


use Illuminate\Support\Facades\Route;

Route::get("/", [HomeController::class,'home'])->name('home');
Route::get("/sobre", [SobreController::class,'sobre'])->name('sobre');
Route::get("/servicos", [ServicosController::class,'servicos'])->name('servicos');
Route::get("/quiz", [QuizController::class,'quiz'])->name('quiz');
Route::get("/contato", [ContatoController::class,'contato'])->name('contato');





Route::get('/servicos/ingles', function () {
    return view('ingles');
})->name('servicos.php');

Route::get('/servicos/italiano', function () {
    return view('italiano');
})->name('servicos.php');

Route::get('/servicos/portugues', function () {
    return view('portugues');
})->name('servicos.php');