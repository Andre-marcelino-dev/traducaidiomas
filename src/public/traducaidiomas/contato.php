<?php
$ok = 0;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

try {
  if (isset($_POST["email"])) {
    // só vai entrar aqui... se preencher o form e clicar no btn enviar 

    require 'vendor/phpmailer/Exception.php';
    require 'vendor/phpmailer/PHPMailer.php';
    require 'vendor/phpmailer/SMTP.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(exceptions: true);

    $nome = $_POST["nome"];
    $email =  $_POST["email"];
    // $tel = $_POST["telefone"];
    // $motivo = $_POST["motivo"];
    $menssagem = $_POST["menssagem"];
    $assunto = 'E-mail do Traduca Idiomas';

    // var_dump($nome);
    // var_dump($email);
    // var_dump($tel);
    // var_dump($motivo);
    // var_dump($mensagem);

    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'email-ssl.com.br';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'traducaidiomas@adminfo.dev.br';                     //SMTP username
    $mail->Password   = 'Traducaidiomas@2025';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('traducaidiomas@adminfo.dev.br', $assunto); // quem dispara o email
    $mail->addAddress('felixsouzalps@gmail.com');     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');


  //Attachments
  // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

  //Content
  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = $assunto;
  $mail->Body    = "
        
           Nome: $nome <br>
           E-mail: $email <br>
           Menssagem: $menssagem
  
  ";


  $mail->AltBody = "
           Nome: $nome /n
           E-mail: $email /n
           Mensagem: $menssagem
  ";

  $mail->send();
  // echo $nome . ', Sua mensagem foi enviada com sucesso';
  $ok = 1;
}

  } // FIM DO IF

  
     catch (Exception $e) {
    // echo $nome . ", ERRO NO ENVIO DO E-MAIL: {$mail->ErrorInfo}";
    $Ok = 2;
}

?>



<!DOCTYPE html>
<html lang="pt-br">
<!--Troca a liguagem do site-->

<!-- Alt + shift + S Identa o codigo -->

<!--sessão da pagina -->

<head>
  <meta charset="UTF-8" />
  <!--Aceita caracteres especiais-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!--meta viewport serve para trabalhar com resposibilidade do site-->
  <title>Professor de idiomas</title>
  <!--titulo da sua pagina-->


  <link rel="manifest" href="img/icon/manifest.json" />
  <meta name="msapplication-TileColor" content="#00c2ff" />
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png" />
  <meta name="theme-color" content="#00c2ff" />

  <link rel="stylesheet" href="css/reset.css" />

  <!-- CODIGO PARA O CARROSSEL  -->
  <link rel="stylesheet" type="text/css" href="css/slick.css" />
  <link rel="stylesheet" type="text/css" href="css/slick-theme.css" />

  <!-- CODIGO PARA O CARROSSEL  -->
  <link rel="stylesheet" href="css/lity.min.css" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <link
    rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/estilo.css" />
</head>
<!--fim sessão da pagina -->

<body>

  <!-- inicio cabeçalho 1920px total da pagina - contendo 100% -->
  <?php require_once("conteudo/topo.php") ?>
  <!-- fim cabeçalho -->

  <main>

  <!-- INICIO SECTION MAPA -->
 <section class="mapa">
  <iframe
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3659.02082114526!2d-46.43309591962194!3d-23.495759527450783!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce63dda7be6fb9%3A0xa74e7d5a53104311!2sSenac%20S%C3%A3o%20Miguel%20Paulista!5e0!3m2!1spt-BR!2sbr!4v1761654096332!5m2!1spt-BR!2sbr" width="100%" height="560" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">"
   
  </iframe>
</section>



  <section class="page-title">
      <h2>Transforme suas ideias em textos de exeleência</h2>
      <p>
       Caminhe da ideia ao texto de exeleência - Consultoria, revisão e ghost writing com quem tem um percuso de mais de 18 ano
      </p>
    </section>

    

    <main id="main_container">
      <section id="contcts_container">
        <h3>Entre em contato</h3>
        <p>
          Preencha o formulário ao lado e entraremos em contato com você o mais
          rápido possivel.
        </p>

        <div id="cards_container">
          <!-- ################ INICIO CARD PHONE ##############  -->
          <a
            href="tel:+5511963067419"
            target="_blank"
            class="contact-card phone"
          >
            <div class="card-icon">
              <i class="fa-solid fa-phone"></i>
            </div>

            <div class="card-infos">
              <p>Telefone</p>
              <span> (11)96306-7419 </span>
            </div>
          </a>
          <!-- ################ FIM CARD PHONE ##############  -->

          <!-- ################ INICIO CARD LOCALIZAÇÃO##############  -->

          <a href="#" target="_blank" class="contact-card location">
            <div class="card-icon">
              <i class="fa-solid fa-location-dot"></i>
            </div>

            <div class="card-infos">
              <p>Localização</p>
              <span>
                Rua Jesus Te Ama, 777, Bairro: Felicidade, Cidade: Ceú - Estado:
                Feliz
              </span>
            </div>
          </a>

          <!-- ################ FIM CARD LOCALIZAÇÃO##############  -->

          <!-- ################ INICIO CARD EMAIL##############  -->
          <a
            href="mailto:email@gmail.com"
            target="_blank"
            class="contact-card email"
          >
            <div class="card-icon">
              <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="card-infos">
              <p>E-mail</p>
              <span> email@gmail.com </span>
            </div>
          </a>

          <!-- ################ FIM CARD EMAIL##############  -->

          <!-- ################ INICIO  CARD WHATSAP##############  -->

          <a
            href="https://api.whatsapp.com/send?phone="
            target="_blank"
            class="contact-card whatsapp"
          >
            <div class="card-icon">
              <i class="fa-brands fa-whatsapp"></i>
            </div>

            <div class="card-infos">
              <p>whatsapp</p>
              <span> (11)99999-9999-999 </span>
            </div>
          </a>

          <!-- ################ FIM CARD WHATSAP##############  -->
        </div>

        <h4>Nos siga nas redes sociais</h4>

        <div class="social_media_icons">

          <a href="#" class="icon-link twitter">
            <i class="fa-brands fa-x-twitter"></i>
          </a>

          <a href="#" class="icon-link facebook">
            <i class="fa-brands fa-facebook"></i>
          </a>

          <a href="#" class="icon-link linkedin">
            <i class="fa-brands fa-linkedin"></i>
          </a>

          <a href="#" class="icon-link instagram">
            <i class="fa-brands fa-instagram"></i>
          </a>
        </div>
      </section>


      

      <section id="contacts_form_container">
        <h3>Contatar</h3>
        <form  action="#" method="post" id="contact_form">

          <div class="input-group">
            <label for="name"> Nome </label>
            <input type="text" id="name" name="nome" required />
          </div>

          <div class="input-group">
            <label for="email"> E-mail </label>
            <input type="text" id="email" name="email" required />
          </div>

          <div class="input-group">
            <label for="subjet"> Assunto </label>
            <input type="text" id="subjet" name="assunto" required />
          </div>

          <div class="input-group">
            <label for="messagem"> Mensagem </label>
            <textarea name="menssagem" id="message" required rows="5"> </textarea>
          </div>
          <h3 
  style="<?php 
    echo ($ok == 1) ? 'color: green;' : (($ok == 2) ? 'color: red;' : ''); 
  ?>"
>
  <?php
    if ($ok == 1) {
      echo $nome . ", sua mensagem foi enviada.";
    } else if ($ok == 2) {
      echo $nome . ", não foi possível enviar sua mensagem, tente novamente mais tarde.";
    }
  ?>
</h3>

          <button type="submit">Enviar mensagem</button>

       
        </form>

      </section>

      </main>
    <?php require_once("conteudo/footer.php")?>


  <script
    type="text/javascript"
    src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script type="text/javascript" src="js/slick.js"></script>

  <script src="./js/lity.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="javascript.js"></script> -->

  <script src="js/animacao.js"></script>
  <script src="js/contato.js"></script>

</body>

</html>