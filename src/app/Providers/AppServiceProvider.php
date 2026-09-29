<?php

namespace App\Providers;

use App\Models\Categoria;
use App\Models\ConfiguracaoPainel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configurarLimitesDeAcesso();

        // Dados usados pelo layout de todas as telas. O composer roda para CADA
        // view/partial, então monta uma vez por requisição e reaproveita
        // (antes eram ~14 consultas por partial, 120-170 por página).
        View::composer('*', function ($view) {
            if (!app()->bound('view.dados_globais')) {
                app()->instance('view.dados_globais', $this->dadosGlobaisDasViews());
            }

            $view->with(app('view.dados_globais'));
        });
    }

    private function dadosGlobaisDasViews(): array
    {
        return [
            'categorias' => Categoria::all(),
            'siteConfig' => [
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
                'logo_site'          => ConfiguracaoPainel::get('logo_site', ''),
            ],
        ];
    }

    /**
     * Limites de requisições (quem passar recebe erro 429 e precisa esperar).
     */
    private function configurarLimitesDeAcesso(): void
    {
        // Login (painel, aluno e API): 5 tentativas por minuto por e-mail+IP, 20 por IP.
        RateLimiter::for('login', function (Request $request) {
            $email = strtolower((string) $request->input('email_professor', $request->input('email_aluno', '')));

            return [
                Limit::perMinute(5)->by('login|' . $email . '|' . $request->ip()),
                Limit::perMinute(20)->by('login-ip|' . $request->ip()),
            ];
        });

        // Chatbot chama a Groq (cota paga): limita por usuário logado ou IP do visitante.
        RateLimiter::for('chatbot', function (Request $request) {
            $quem = auth('admin')->id() ? 'prof' . auth('admin')->id()
                : (auth('aluno')->id() ? 'aluno' . auth('aluno')->id() : $request->ip());

            return [
                Limit::perMinute(10)->by('chatbot|' . $quem),
                Limit::perDay(200)->by('chatbot-dia|' . $quem),
            ];
        });

        // API em geral: 60 requisições por minuto por token (ou IP sem token).
        RateLimiter::for('api', function (Request $request) {
            $usuario = $request->user();
            $chave = $usuario ? class_basename($usuario) . $usuario->getAuthIdentifier() : $request->ip();

            return Limit::perMinute(60)->by('api|' . $chave);
        });
    }
}
