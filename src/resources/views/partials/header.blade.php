<header class="topo">
  <div class="container">
    <h1>TraducaIdiomas</h1>
    <?php
    $pgAtual = basename(path: $_SERVER['REQUEST_URI']);
    ?>

    <button class=abrir-menu></button>
    <nav>
      <button class=fechar-menu></button>
      <ul>

        <li><a href="{{route('home')}}" class="<?= ($pgAtual == 'index.php') ? 'ativo' : '' ?>">Home</a></li>

        <li><a href="{{route('sobre')}}" class="<?= ($pgAtual == 'sobre.php') ? 'ativo' : '' ?>"> Sobre</a></li>
        
  <li class="dropdown">
    <a href="{{ route('servicos') }}" 
       class="<?= ($pgAtual == 'servico.php') ? 'ativo' : '' ?>">
        Serviços
    </a>

    <ul class="submenu">
        <li>
            <a href="{{ route('servicos.php') }}">Inglês</a>
        </li>
        <li>
            <a href="{{ route('servicos.php') }}">Italiano</a>
        </li>
        <li>
            <a href="{{ route('servicos.php') }}">Portugues</a>
        </li>
    </ul>
</li>
      
        <li><a href="{{ route('quiz') }}" class="<?= ($pgAtual == 'quiz.php') ? 'ativo' : '' ?>">Quiz</a></li>


        <li><a href="{{ route('contato') }}" class="<?= ($pgAtual == 'contato.php') ? 'ativo' : '' ?>">Contato</a></li>

      </ul>
    </nav>

    <ul class="rede">
      <li>
        <a href="#">
          <img src="{{ asset('traducaidiomas/img/instagramLogo.svg') }}"
               alt="Logo instagram" width="50">
        </a>
      </li>
      <li>
        <a href="#">
          <img src="{{ asset('traducaidiomas/img/whatsappLogo.svg') }}"
               alt="Logo whatsapp" width="50">
        </a>
      </li>
      <li>
        <a href="#">
          <img src="{{ asset('traducaidiomas/img/linkedinLogo.svg') }}"
               alt="Logo linkedin" width="50">
        </a>
      </li>
    </ul>

  </div>

  <script src="{{ asset('traducaidiomas/js/idiomas.js') }}"></script>
</header>