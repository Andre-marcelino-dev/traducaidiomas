<!DOCTYPE html>
<html lang="ePT-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz</title>
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
  <link rel="stylesheet" href="css/estilo.css" />
</head>
<body>
  <!-- Começo HEADER -->
   <?php require_once("conteudo/topo.php") ?>
   <!-- Fim HEADER -->

  <!-- Começo BANNER  -->
  <?php require_once("conteudo/banner.php") ?>
  <!-- Fim BANNER -->

  
<div class="quiz-section">
  <div class="quiz-container">
    <div class="quiz-header">
      <h1>
        <div class="flag-icon"></div>
        Quiz Interativo
      </h1>
      <p style="color: #666; font-size: 1rem;">Teste seus conhecimentos!</p>
    </div>

    <div class="progress-container">
      <div class="progress-text">
        Pergunta <span id="current-question">1</span> de
        <span id="total-questions">10</span>
      </div>
      <div class="progress-bar">
        <div class="progress-fill" id="progress-fill" style="width: 10%"></div>
      </div>
    </div>

    <div id="quiz-content">
      <div class="question-container">
        <div class="question" id="question"></div>
        <div class="ops" id="ops"></div>
      </div>
      <button class="next-btn" id="next-btn">Próxima</button>
    </div>

    <div class="result-container" id="result-container">
      <div class="result-icon" id="result-icon"></div>
      <div class="result-title">Quiz Finalizado!</div>
      <div class="result-score" id="result-score"></div>
      <div class="result-message" id="result-message"></div>
      <button class="restart-btn" onclick="restartQuiz()">Jogar Novamente</button>
    </div>
  </div>
</div>

      <?php require_once("conteudo/footer.php") ?>

<script
    type="text/javascript"
    src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script type="text/javascript" src="js/slick.js"></script>

  <script src="./js/lity.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="javascript.js"></script> -->

  <script src="./js/animacao.js"></script>
  <script src="./js/quiz.js"></script>
   </body>
</html>