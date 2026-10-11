<header class="topo">
    <div class="container">
        <a href="{{ route('home') }}" class="topo-logo">
            @if(!empty($siteConfig['logo_site']))
            <img src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}" alt="TraducaIdiomas">
            @else
            <h1>TraducaIdiomas</h1>
            @endif
        </a>
        <?php
        // Item do menu da página atual (fica vermelho pela classe .ativo do header.css).
        // Antes comparava com "sobre.php"/"contato.php", nomes do site antigo em PHP,
        // e por isso nunca destacava nada.
        $ativo = fn (string ...$rotas) => request()->routeIs(...$rotas) ? 'ativo' : '';

        // Área do aluno: vai direto para o painel se o aluno já estiver logado e matriculado.
        $alunoLogado = auth('aluno')->check() && auth('aluno')->user()->matriculas()->where('status_matricula', 'ATIVO')->exists();
        $linkAluno = $alunoLogado ? route('aluno.dash') : route('aluno.login');
        ?>

        {{-- No celular/tablet o <nav> vira uma gaveta lateral aberta pelo .menu-toggle (js/menu.js). --}}
        <nav class="menu" id="menu-principal" aria-label="Menu principal">
            <span class="menu-titulo">Menu</span>

            <ul class="menu-lista">

                <li><a href="{{ route('home') }}" class="{{ $ativo('home') }}"><i class="menu-icone fa-solid fa-house"></i>Home</a></li>

                <li><a href="{{ route('sobre') }}" class="{{ $ativo('sobre') }}"><i class="menu-icone fa-solid fa-user-tie"></i>Sobre</a></li>

                <li class="dropdown">
                    <a href="{{ route('servicos') }}" class="{{ $ativo('servicos', 'servicos.categoria') }}"><i class="menu-icone fa-solid fa-language"></i>Serviços <span class="dropdown-arrow">›</span></a>
                    <button type="button" class="menu-sub-toggle" aria-expanded="false" aria-controls="submenu-servicos" aria-label="Mostrar serviços">
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="submenu" id="submenu-servicos">
                        <span class="submenu-header">Nossos Serviços</span>
                        <ul class="submenu-list">
                            @foreach ($categorias as $linha)
                            <li>
                                <a href="{{ route('servicos.categoria', $linha->id_servico) }}">
                                    <span class="submenu-icon">
                                        <i class="fa-solid fa-language"></i>
                                    </span>
                                    <span class="submenu-text">
                                        <strong>{{ Str::title($linha->titulo_servico) }}</strong>
                                        <small>{{ $linha->subtitulo_servico }}</small>
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                <li><a href="{{ route('quiz') }}" class="{{ $ativo('quiz') }}"><i class="menu-icone fa-solid fa-circle-question"></i>Quiz</a></li>

                <li><a href="{{ route('contato') }}" class="{{ $ativo('contato') }}"><i class="menu-icone fa-solid fa-envelope"></i>Contato</a></li>

            </ul>

            {{-- Só aparece na gaveta do celular; no desktop esses links ficam em .rede --}}
            <div class="menu-rodape">
                <a href="{{ $linkAluno }}" class="menu-btn menu-btn-aluno">
                    <i class="fa fa-user-graduate"></i> Área do Aluno
                </a>
                <a href="{{ route('admin.login') }}" class="menu-btn menu-btn-admin">
                    <i class="fa fa-user-shield"></i> Admin
                </a>
                <div class="menu-redes">
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://wa.me/5511999612140" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </nav>

        <ul class="rede">
            <li>
                <a href="#">
                    <img src="{{ asset('traducaidiomas/img/instagramLogo.svg') }}" alt="Logo instagram" width="35">
                </a>
            </li>
            <li>
                <a href="https://wa.me/5511999612140" target="_blank">
                    <img src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}" alt="Logo whatsapp" width="35">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}" alt="Logo linkedin" width="35">
                </a>
            </li>
            <li class="cart-btn">
                <a href="{{ $linkAluno }}" title="Área do Aluno">
                    <i class="fa fa-user-graduate" style="font-size: 30px;"></i>
                </a>
            </li>
            <li class="cart-btn">
                <a href="{{ route('admin.login') }}" title="Admin">
                    <i class="fa fa-user-shield" style="font-size: 30px;"></i>
                    <span class="count"></span>
                </a>
            </li>
        </ul>

        <button type="button" class="menu-toggle" aria-controls="menu-principal" aria-expanded="false" aria-label="Abrir menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="menu-overlay"></div>
</header>
