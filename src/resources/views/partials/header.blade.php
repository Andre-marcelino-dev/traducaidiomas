<header class="topo">
    <div class="container">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="logo">
            @if(!empty($siteConfig['logo_site']))
                <img src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}" alt="TraducaIdiomas">
            @else
                <h1>TraducaIdiomas</h1>
            @endif
        </a>

        {{-- BOTÃO MOBILE --}}
        <button class="abrir-menu" aria-label="Abrir menu"></button>

        {{-- OVERLAY --}}
        <div class="overlay"></div>

        {{-- NAV --}}
        <nav class="sidebar">

            <button class="fechar-menu" aria-label="Fechar menu"></button>

            <ul class="menu">

                <li>
                    <a href="{{ route('home') }}"
                       class="{{ $route === 'home' ? 'ativo' : '' }}">
                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('sobre') }}"
                       class="{{ $route === 'sobre' ? 'ativo' : '' }}">
                        Sobre
                    </a>
                </li>

                {{-- DROPDOWN MELHORADO --}}
                <li class="dropdown">
                    <button class="dropdown-toggle">
                        Serviços <span>›</span>
                    </button>

                    <div class="submenu">
                        <span class="submenu-title">Nossos Serviços</span>

                        <ul>
                            @foreach ($categorias as $linha)
                                <li>
                                    <a href="{{ route('servicos.categoria', $linha->id_servico) }}">
                                        <i class="fa-solid fa-language"></i>
                                        <div>
                                            <strong>{{ Str::title($linha->titulo_servico) }}</strong>
                                            <small>{{ $linha->subtitulo_servico }}</small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{ route('quiz') }}"
                       class="{{ $route === 'quiz' ? 'ativo' : '' }}">
                        Quiz
                    </a>
                </li>

                <li>
                    <a href="{{ route('contato') }}"
                       class="{{ $route === 'contato' ? 'ativo' : '' }}">
                        Contato
                    </a>
                </li>

            </ul>

            {{-- REDES (MOBILE DENTRO DO MENU) --}}
            <div class="rede mobile-rede">
                <a href="#"><img src="{{ asset('traducaidiomas/img/instagramLogo.svg') }}"></a>
                <a href="#"><img src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}"></a>
                <a href="#"><img src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}"></a>
            </div>

        </nav>

        {{-- REDES (DESKTOP) --}}
        <ul class="rede desktop-rede">
            <li><a href="#"><img src="{{ asset('traducaidiomas/img/instagramLogo.svg') }}"></a></li>
            <li><a href="#"><img src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}"></a></li>
            <li><a href="#"><img src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}"></a></li>

            <li>
                <a href="{{ auth('aluno')->check() ? route('aluno.dash') : route('aluno.login') }}">
                    <i class="fa fa-user-graduate"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.login') }}">
                    <i class="fa fa-user-shield"></i>
                </a>
            </li>
        </ul>

    </div>
</header>
