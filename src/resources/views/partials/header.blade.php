<header class="topo">
    <div class="container">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="logo">
            @if (!empty($siteConfig['logo_site']))
                <img
                    src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}"
                    alt="TraducaIdiomas"
                >
            @else
                <h1>TraducaIdiomas</h1>
            @endif
        </a>

        {{-- BOTÃO MOBILE --}}
        <button
            type="button"
            class="abrir-menu"
            aria-label="Abrir menu"
        >
        </button>

        {{-- OVERLAY --}}
        <div class="overlay"></div>

        {{-- NAV --}}
        <nav class="sidebar">

            {{-- BOTÃO FECHAR MOBILE --}}
            <button
                type="button"
                class="fechar-menu"
                aria-label="Fechar menu"
            >
            </button>

            <ul class="menu">

                {{-- HOME --}}
                <li>
                    <a
                        href="{{ route('home') }}"
                        class="{{ $route === 'home' ? 'ativo' : '' }}"
                    >
                        Home
                    </a>
                </li>

                {{-- SOBRE --}}
                <li>
                    <a
                        href="{{ route('sobre') }}"
                        class="{{ $route === 'sobre' ? 'ativo' : '' }}"
                    >
                        Sobre
                    </a>
                </li>

                {{-- SERVIÇOS --}}
                <li class="dropdown">

                    <button
                        type="button"
                        class="dropdown-toggle"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        Serviços
                        <span>›</span>
                    </button>

                    <div class="submenu">

                        <span class="submenu-title">
                            Nossos Serviços
                        </span>

                        <ul>
                            @foreach ($categorias as $linha)

                                <li>
                                    <a
                                        href="{{ route('servicos.categoria', $linha->id_servico) }}"
                                    >

                                        <i class="fa-solid fa-language"></i>

                                        <div>
                                            <strong>
                                                {{ Str::title($linha->titulo_servico) }}
                                            </strong>

                                            @if (!empty($linha->subtitulo_servico))
                                                <small>
                                                    {{ $linha->subtitulo_servico }}
                                                </small>
                                            @endif
                                        </div>

                                    </a>
                                </li>

                            @endforeach
                        </ul>

                    </div>
                </li>

                {{-- QUIZ --}}
                <li>
                    <a
                        href="{{ route('quiz') }}"
                        class="{{ $route === 'quiz' ? 'ativo' : '' }}"
                    >
                        Quiz
                    </a>
                </li>

                {{-- CONTATO --}}
                <li>
                    <a
                        href="{{ route('contato') }}"
                        class="{{ $route === 'contato' ? 'ativo' : '' }}"
                    >
                        Contato
                    </a>
                </li>

            </ul>

            {{-- REDES MOBILE --}}
            <div class="rede mobile-rede">

                <a href="#" aria-label="Instagram">
                    <img
                        src="{{ asset('traducaidiomas/img/instagramLogo.svg') }}"
                        alt="Instagram"
                    >
                </a>

                <a href="#" aria-label="WhatsApp">
                    <img
                        src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}"
                        alt="WhatsApp"
                    >
                </a>

                <a href="#" aria-label="LinkedIn">
                    <img
                        src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}"
                        alt="LinkedIn"
                    >
                </a>

            </div>

        </nav>

        {{-- REDES DESKTOP --}}
        <ul class="rede desktop-rede">

            <li>
                <a href="#" aria-label="Instagram">
                    <img
                        src="{{ asset('traducaidiomas/img/instagramLogo.svg') }}"
                        alt="Instagram"
                    >
                </a>
            </li>

            <li>
                <a href="#" aria-label="WhatsApp">
                    <img
                        src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}"
                        alt="WhatsApp"
                    >
                </a>
            </li>

            <li>
                <a href="#" aria-label="LinkedIn">
                    <img
                        src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}"
                        alt="LinkedIn"
                    >
                </a>
            </li>

            {{-- ALUNO --}}
            <li>
                <a
                    href="{{ auth('aluno')->check() ? route('aluno.dash') : route('aluno.login') }}"
                    aria-label="Área do aluno"
                >
                    <i class="fa fa-user-graduate"></i>
                </a>
            </li>

            {{-- PROFESSOR / ADMIN --}}
            <li>
                <a
                    href="{{ route('admin.login') }}"
                    aria-label="Área do professor"
                >
                    <i class="fa fa-user-shield"></i>
                </a>
            </li>

        </ul>

    </div>
</header>