

<!DOCTYPE html>
<html lang="en">
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

  <!-- INICIO SESSÃO SOBRE  -->

   <?php require_once("conteudo/sobre.php") ?>
  <!-- FIM SESSÃO SOBRE  -->

<!-- INICIO SESSÃO ALUNOS CARDS -->
<section aria-label="Lista de alunos">
    <div class="alunos">Alunos</div>

    <div class="card-container">


        <!-- Card Aluno 1 -->
        <div class="card">
            <div class="card-header">
                <div class="school-logo" title="Instituição">🌍</div>

            <img 
                src=""
                alt="Foto do aluno"
                class="photo"
            >

                <div class="student-info">
                 <h2></h2>
                    <div class="student-id">Matricula:ALUNO-2025-001</div>

                </div>
            </div>

            <div class="card-body">
                <div class="course-info">
                    <div class="info-group">
             
           
                    </div>

                    <div class="info-group">
                        <span class="label">Turma</span>
                        <span class="value">Seg 18h-20h</span>
                    </div>
                </div>

                <div class="language-badge">
                    <div 
                        class="flag" 
                        aria-hidden="true"
                        style="background: linear-gradient(to bottom, #ff0000 33%, #ffffff 33% 66%, #000080 66%);">
                    </div>
                    Português
                </div>

                <div class="validity">Válido até 12/2026</div>
            </div>
        </div>
        


       
</section>
<!-- FIM SESSÃO ALUNOS CARDS -->


  <section class="page-title">

      <h2>Transforme suas ideias em textos de excelência</h2>
      <p>
       Caminhe da ideia ao texto de excelência - Consultoria, revisão e ghost writing com quem tem um percuso de mais de 18 ano
      </p>
    </section>


    <!-- Começo TIMELINE -->

    <?php require_once("conteudo/timeline.php") ?>
    <!-- Fim TIMELINE -->


  <!-- Começo IDADE-->
    



<!-- Fim idade-->
   
  
    </main>


    <?php require_once("conteudo/footer.php") ?>


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