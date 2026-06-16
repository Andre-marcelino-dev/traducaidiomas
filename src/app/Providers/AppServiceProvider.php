<?php

namespace App\Providers;

use App\Models\Categoria;
use App\Models\ConfiguracaoPainel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('categorias', Categoria::all());
            $view->with('siteConfig', [
                'banner1_titulo'     => ConfiguracaoPainel::get('banner1_titulo', 'Inglês profissional com método claro, foco em resultado e consistência.'),
                'banner1_subtitulo'  => ConfiguracaoPainel::get('banner1_subtitulo', 'Treinamento para reuniões, entrevistas e apresentações, com metas reais e acompanhamento próximo.'),
                'banner1_eyebrow'    => ConfiguracaoPainel::get('banner1_eyebrow', 'TraducaIdiomas · English & Professional Skills'),
                'banner2_titulo'     => ConfiguracaoPainel::get('banner2_titulo', 'Da aula à aplicação real: comunicação eficiente no seu contexto.'),
                'banner2_subtitulo'  => ConfiguracaoPainel::get('banner2_subtitulo', 'Metodologia prática, material objetivo e feedback contínuo para acelerar sua evolução.'),
                'banner2_eyebrow'    => ConfiguracaoPainel::get('banner2_eyebrow', 'Expertise · Idiomas com estratégia'),
                'info_titulo'        => ConfiguracaoPainel::get('info_titulo', 'Transforme suas ideias em textos de excelência'),
                'info_subtitulo'     => ConfiguracaoPainel::get('info_subtitulo', 'Oferecemos serviços especializados de consultoria, revisão e elaboração de textos claros, precisos e alinhados aos mais altos padrões de qualidade.'),
                'sobre_titulo'       => ConfiguracaoPainel::get('sobre_titulo', 'Biografia'),
                'sobre_texto'        => ConfiguracaoPainel::get('sobre_texto', 'Sou Renato Caetano, consultor e professor trilíngue formado em Letras, com experiência em ensino, tradução e design instrucional.'),
                'sobre_foto'         => ConfiguracaoPainel::get('sobre_foto', ''),
                'logo_painel'        => ConfiguracaoPainel::get('logo_painel', ''),
            ]);
        });
    }
}
