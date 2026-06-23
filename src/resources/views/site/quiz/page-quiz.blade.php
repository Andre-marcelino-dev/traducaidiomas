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