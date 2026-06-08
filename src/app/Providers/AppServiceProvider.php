<?php

namespace App\Providers;

use App\Models\Categoria;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    // Isso garante que $categorias exista em todas as views que usam o header
    View::composer('*', function ($view) {
        $view->with('categorias', Categoria::all());
    });
}
}
