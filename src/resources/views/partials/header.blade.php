<header class="topo">
    <div class="container">
        @if(!empty($siteConfig['logo_site']))
<<<<<<< HEAD
<<<<<<< HEAD
        <a href="{{ route('home') }}"><img src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}" alt="TraducaIdiomas" style="max-height:60px;"></a>
        @else
        <h1>TraducaIdiomas</h1>
=======
            <a href="{{ route('home') }}"><img src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}" alt="TraducaIdiomas" style="max-height:60px;"></a>
        @else
            <h1>TraducaIdiomas</h1>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
            <a href="{{ route('home') }}"><img src="{{ asset('traducaidiomas/img/' . $siteConfig['logo_site']) }}" alt="TraducaIdiomas" style="max-height:60px;"></a>
        @else
            <h1>TraducaIdiomas</h1>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
        @endif
        <?php
        $pgAtual = basename(path: $_SERVER['REQUEST_URI']);
        ?>

        <button class=abrir-menu></button>
        <nav>
            <button class=fechar-menu></button>
            <ul>

                <li><a href="{{ route('home') }}" class="<?= $pgAtual == 'index.php' ? 'ativo' : '' ?>">Home</a></li>

                <li><a href="{{ route('sobre') }}" class="<?= $pgAtual == 'sobre.php' ? 'ativo' : '' ?>"> Sobre</a></li>

                <li class="dropdown">
<<<<<<< HEAD
<<<<<<< HEAD
                    <a href="{{ route('servicos') }}">Serviços</a>
                    <ul class="submenu">
                        @foreach ($categorias as $linha)
                        <li>
                            {{-- O link envia o ID, mas o texto exibido é apenas o título --}}
                            <a href="{{ route('servicos.categoria', $linha->id_servico) }}">
                                {{ Str::title($linha->titulo_servico) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
=======
=======
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
                    <a href="{{ route('servicos') }}">Serviços <span class="dropdown-arrow">›</span></a>
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
<<<<<<< HEAD
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
                </li>

                <li><a href="{{ route('quiz') }}" class="<?= $pgAtual == 'quiz.php' ? 'ativo' : '' ?>">Quiz</a></li>


                <li><a href="{{ route('contato') }}" class="<?= $pgAtual == 'contato.php' ? 'ativo' : '' ?>">Contato</a>
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
<<<<<<< HEAD
<<<<<<< HEAD
                <a href="https://wa.me/5511999612140" target="_blank">
=======
                <a href="#">
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
                <a href="#">
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
                    <img src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}" alt="Logo whatsapp" width="35">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}" alt="Logo linkedin" width="35">
                </a>
            </li>
            <li class="cart-btn">
<<<<<<< HEAD
<<<<<<< HEAD
                <a href="{{ route('aluno.login') }}">
                    <i class="fa fa-user-graduate" style="font-size: 30px;"></i>
                    <span class="count"></span>
                </a>
=======
=======
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
                @if(auth('aluno')->check() && auth('aluno')->user()->matriculas()->where('status_matricula', 'ATIVO')->exists())
                    <a href="{{ route('aluno.dash') }}">
                        <i class="fa fa-user-graduate" style="font-size: 30px;"></i>
                    </a>
                @else
                    <a href="{{ route('aluno.login') }}">
                        <i class="fa fa-user-graduate" style="font-size: 30px;"></i>
                    </a>
                @endif
<<<<<<< HEAD
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
            </li>
            <li class="cart-btn">
                <a href="{{ route('admin.login') }}">
                    <i class="fa fa-user-shield" style="font-size: 30px;"></i>
                    <span class="count"></span>
                </a>
            </li>
        </ul>



        <script src="{{ asset('traducaidiomas/js/idiomas.js') }}"></script>
<<<<<<< HEAD
<<<<<<< HEAD
</header>
=======
</header>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
=======
</header>
>>>>>>> c2e8e61f063cde17ab9a61599a5a7b1578d390d6
