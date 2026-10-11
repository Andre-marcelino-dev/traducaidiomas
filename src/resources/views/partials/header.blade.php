<header class="topo">
    <div class="container">
        @if(!empty($siteConfig['logo_site']))
        <a href="{{ route('home') }}"><img src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}" alt="TraducaIdiomas" style="max-height:60px;"></a>
        @else
        <h1>TraducaIdiomas</h1>
        @endif
        <?php
        // Item do menu da página atual (sublinhado pela classe .ativo do header.css).
        // Antes comparava com "sobre.php"/"contato.php", nomes do site antigo em PHP,
        // e por isso nunca destacava nada.
        $ativo = fn (string ...$rotas) => request()->routeIs(...$rotas) ? 'ativo' : '';
        ?>

        <button class=abrir-menu></button>
        <nav>
            <button class=fechar-menu></button>
            <ul>

                <li><a href="{{ route('home') }}" class="{{ $ativo('home') }}">Home</a></li>

                <li><a href="{{ route('sobre') }}" class="{{ $ativo('sobre') }}"> Sobre</a></li>

                <li class="dropdown">
                    <a href="{{ route('servicos') }}" class="{{ $ativo('servicos', 'servicos.categoria') }}">Serviços <span class="dropdown-arrow">›</span></a>
                    <div class="submenu">
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

                <li><a href="{{ route('quiz') }}" class="{{ $ativo('quiz') }}">Quiz</a></li>


                <li><a href="{{ route('contato') }}" class="{{ $ativo('contato') }}">Contato</a>
                </li>

            </ul>
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
                @if(auth('aluno')->check() && auth('aluno')->user()->matriculas()->where('status_matricula', 'ATIVO')->exists())
                <a href="{{ route('aluno.dash') }}">
                    <i class="fa fa-user-graduate" style="font-size: 30px;"></i>
                </a>
                @else
                <a href="{{ route('aluno.login') }}">
                    <i class="fa fa-user-graduate" style="font-size: 30px;"></i>
                </a>
                @endif
            </li>
            <li class="cart-btn">
                <a href="{{ route('admin.login') }}">
                    <i class="fa fa-user-shield" style="font-size: 30px;"></i>
                    <span class="count"></span>
                </a>
            </li>
        </ul>


</header>