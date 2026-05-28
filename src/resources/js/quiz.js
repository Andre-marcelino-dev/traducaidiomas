
   document.querySelector('.abrir-menu').onclick = function(){
  document.documentElement.classList.add('menu-ativo')
   }

   document.querySelector('.fechar-menu').onclick = function(){
     document.documentElement.classList.remove("menu-ativo")


     
 }

    document.querySelector('.abrir-menu').onclick = function(){
  document.documentElement.classList.add('menu-ativo')
   }

   document.querySelector('.fechar-menu').onclick = function(){
     document.documentElement.classList.remove("menu-ativo")


     
 }




let questions = [
            {
                question: "A) Excuse me. __________________ ) Yes. There's a bank on the next corner.",
                ops: ["Is there a bank near here?", "What's a bank near here?", "Where bank is here?", "Bank is there here?"],
                correct: 0
            },
            {
                question: "A) __________________ ) I'm fine, thanks!",
                ops: ["How you are?", "What are you?", "How are you?", "How is you?"],
                correct: 2
            },
            {
                question: "She ________ to school every day.",
                ops: ["go", "goes", "going", "is go"],
                correct: 1
            },
            {
                question: "A) What do you do? ) __________________",
                ops: ["I do my homework", "I'm a teacher", "I'm doing fine", "I do sports"],
                correct: 1
            },
            {
                question: "I ________ English for three years.",
                ops: ["study", "am studying", "have studied", "studied"],
                correct: 2
            },
            {
                question: "A) Can you help me? ) __________________",
                ops: ["Yes, I help", "Yes, I can", "Yes, I do", "Yes, I am"],
                correct: 1
            },
            {
                question: "They ________ to the party last night.",
                ops: ["don't go", "didn't went", "didn't go", "not go"],
                correct: 2
            },
            {
                question: "________ people are in your family?",
                ops: ["How much", "How many", "How", "What much"],
                correct: 1
            },
            {
                question: "If I ________ rich, I would travel the world.",
                ops: ["am", "was", "were", "be"],
                correct: 2
            },
            {
                question: "A) ______ I ask you a question, Mr. Jones?",
                ops: ["Yes, I like", "Does", "Could", "Do"],
                correct: 2
            }
        ];

        let currentQuestionIndex = 0;
        let score = 0;
        let answered = false;

        function shuffleQuestions() {
            for (let i = questions.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [questions[i], questions[j]] = [questions[j], questions[i]];
            }
        }

        function startQuiz() {
            shuffleQuestions();
            currentQuestionIndex = 0;
            score = 0;
            answered = false;
            showQuestion();
        }

        // Mostrar pergunta atual
        function showQuestion() {
            const question = questions[currentQuestionIndex];
            document.getElementById('question').textContent = question.question;
            document.getElementById('current-question').textContent = currentQuestionIndex + 1;
            document.getElementById('total-questions').textContent = questions.length;
            
            const progressPercent = ((currentQuestionIndex + 1) / questions.length) * 100;
            document.getElementById('progress-fill').style.width = progressPercent + '%';

            const opsContainer = document.getElementById('ops');
            opsContainer.innerHTML = '';

            question.ops.forEach((opz, index) => {
                const opzDiv = document.createElement('div');
                opzDiv.className = 'opz';
                opzDiv.textContent = opz;
                opzDiv.onclick = () => selectopz(index);
                opsContainer.appendChild(opzDiv);
            });

            document.getElementById('next-btn').style.display = 'none';
            answered = false;
        }

        // Selecionar opção
        function selectopz(selectedIndex) {
            if (answered) return;

            answered = true;
            const question = questions[currentQuestionIndex];
            const ops = document.querySelectorAll('.opz');

            ops.forEach((opz, index) => {
                opz.classList.add('disabled');
                
                if (index === question.correct) {
                    opz.classList.add('correct');
                }
                
                if (index === selectedIndex && selectedIndex !== question.correct) {
                    opz.classList.add('wrong');
                    
                    // Move a pergunta para o final se errou
                    setTimeout(() => {
                        const wrongQuestion = questions.splice(currentQuestionIndex, 1)[0];
                        questions.push(wrongQuestion);
                    }, 1000);
                } else if (index === selectedIndex && selectedIndex === question.correct) {
                    score++;
                }
            });

            document.getElementById('next-btn').style.display = 'block';
        }

        // Próxima pergunta
        document.getElementById('next-btn').onclick = function() {
            currentQuestionIndex++;

            if (currentQuestionIndex < questions.length) {
                showQuestion();
            } else {
                showResults();
            }
        };

        // Mostrar resultados
        function showResults() {
            document.getElementById('quiz-content').style.display = 'none';
            document.querySelector('.progress-container').style.display = 'none';
            
            const resultContainer = document.getElementById('result-container');
            resultContainer.classList.add('show');

            const percentage = (score / questions.length) * 100;
            document.getElementById('result-score').textContent = `${score} / ${questions.length}`;

            let icon, message;
            if (percentage >= 80) {
                icon = '🏆';
                message = 'Excellent! Advanced Level - You have great English proficiency!';
            } else if (percentage >= 60) {
                icon = '👍';
                message = 'Good Job! Intermediate Level - Keep practicing!';
            } else if (percentage >= 40) {
                icon = '😊';
                message = 'Not bad! Pre-Intermediate Level - You\'re on the right track!';
            } else {
                icon = '💪';
                message = 'Basic Level - Keep studying, you can improve!';
            }

            document.getElementById('result-icon').textContent = icon;
            document.getElementById('result-message').textContent = message;
        }

        // Reiniciar quiz
        function restartQuiz() {
            document.getElementById('quiz-content').style.display = 'block';
            document.querySelector('.progress-container').style.display = 'block';
            document.getElementById('result-container').classList.remove('show');
            startQuiz();
        }

        // Iniciar o quiz quando a página carregar
        startQuiz();