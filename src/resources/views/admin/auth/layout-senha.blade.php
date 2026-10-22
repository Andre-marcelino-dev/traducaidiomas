<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') - Painel Traduca</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('traducaidiomas/css/css/login.css') }}">
</head>
<body>

    {{-- PAINEL ESQUERDO (mesmo visual da tela de login) --}}
    <div class="panel-esquerdo">
        <div class="numero-grande">A</div>
        <div class="badge-sistema">Sistema Ativo</div>
        <h2 class="tagline">
            Recuperar<br>
            <em>acesso</em><br>
            ao painel
        </h2>
        <p class="descricao">
            Enviamos um código de 6 dígitos para o e-mail cadastrado. Com ele você cria uma senha nova.
        </p>
    </div>

    {{-- PAINEL DIREITO --}}
    <div class="panel-direito">
        <div class="card-login">

            <div class="logo-topo">
                <div class="logo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="logo-nome">
                    Painel Administrativo
                    <span>TraducaIdiomas</span>
                    <span>Área Restrita</span>
                </div>
            </div>

            <h1 class="titulo">@yield('titulo')</h1>
            <p class="subtitulo">@yield('subtitulo')</p>

            @if(session('info'))
                <div class="alerta-info">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    {{ session('info') }}
                </div>
            @endif

            @yield('conteudo')

            <p class="info-suporte">
                Lembrou a senha? <a href="{{ route('admin.login') }}">Voltar ao login</a>
            </p>

        </div>
    </div>

</body>
</html>
