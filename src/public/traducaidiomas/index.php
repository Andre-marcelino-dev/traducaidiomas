

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TraducaIdiomas</title>
  <!-- CODIGO PARA O CARROSSEL  -->
  <link rel="stylesheet" type="text/css" href="./css/slick.css" />
  <link rel="stylesheet" type="text/css" href="./css/slick-theme.css" />
  <!-- CODIGO PARA O CARROSSEL  -->
  <link rel="stylesheet" href="./css/lity.min.css" />
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
  <link rel="stylesheet" href="./css/estilo.css" />
</head>
<body>
  <!-- Começo HEADER -->
   <?php require_once("conteudo/topo.php") ?>
   <!-- Fim HEADER -->

  <!-- Começo BANNER  -->
  <?php require_once("conteudo/banner.php") ?>
  <!-- Fim BANNER -->

<!--Começo INFORMAÇÕES -->
   <?php require_once("conteudo/informacao.php") ?> 
 
<!-- FIM INICIO SOBRE  -->





<!--Começo INFORMAÇÕES -->
   <!-- <?php require_once("conteudo/informacao.php") ?> -->

    <!-- Começo TIMELINE -->

    <?php require_once("conteudo/timeline.php") ?>
    <!-- Fim TIMELINE -->


<?php require_once("conteudo/sobre_home.php") ?>




 <?php require_once("conteudo/faxaetaria.php") ?>
   
  <!-- CONTATO -->

  

<section class="page-title">
  <h2>Transforme suas ideias em textos de excelência</h2>
  <p>
    Oferecemos serviços especializados de consultoria, revisão e experiência na elaboração de textos claros, precisos e alinhados aos mais altos padrões de qualidade.
  </p>
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

  <script src="./js/animacao.js"></script>

</body>
</html>