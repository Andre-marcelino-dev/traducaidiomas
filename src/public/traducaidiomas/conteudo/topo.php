
    
    <header class="topo">
         <div class="container">
        <h1>TraducaIdiomas</h1>
        <?php
  $pgAtual = basename(path: $_SERVER['REQUEST_URI']);
  // echo($pgAtual);
  ?>

  <button class=abrir-menu></button>
  <nav>
     <button class=fechar-menu></button>
          <ul>
           
            <li><a href="index.php" class="<?= ($pgAtual == 'index.php')? 'ativo': ''?>">Home</a></li>
            

            <li><a href="sobre.php" class="<?= ($pgAtual == 'sobre.php')? 'ativo': ''?>"> Sobre</a></li>
            <li><a href="servicos.php"class="<?= ($pgAtual == 'servico.php')? 'ativo': ''?>">Serviços</a></li>
            <li><a href="quiz.php"class="<?= ($pgAtual == 'quiz.php')? 'ativo': ''?>">Quiz</a></li>
         
 
            
              <li><a href="contato.php"class="<?= ($pgAtual == 'contato.php')? 'ativo': ''?>">Contato</a></li>
               
             
          </ul>
        </nav>

        <ul class="rede">
          <li>
            <a href="#"
              ><img
                src="./img/instagramLogo.svg"
                alt="Logo instagram"
                sizes="50px"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="./img/whatsappLogo.svg" alt="Logo instagram" sizes="50px"
            /></a>
          </li>
          <li>
            <a href="#"
              ><img src="./img/linkedinLogo.svg" alt="Logo Facebook" sizes="50px"
            /></a>
          </li>
        </ul>
  
        
      </div>
      <script src="js/idiomas.js"></script>
    </header>
