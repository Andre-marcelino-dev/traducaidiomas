
$('.banner').slick({
  slidesToShow: 1,
  slidesToScroll:1,
  autoplay: true,
  autoplaySpeed: 5000,

});



let currentLevel = 0;
const totalLevels = 6;
const sections = document.querySelectorAll('.timeline-section');
const progressBar = document.getElementById('progressBar');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');

function updateTimeline() {
    sections.forEach((section, index) => {
        section.classList.remove('active');
        if (index === currentLevel) {
            section.classList.add('active');
            
            setTimeout(() => {
                const cards = section.querySelectorAll('.timeline-card');
                cards.forEach(card => card.classList.add('show'));
            }, 100);
        }
    });

    const progress = ((currentLevel + 1) / totalLevels) * 100;
    progressBar.style.width = progress + '%';

    prevBtn.disabled = currentLevel === 0;
    nextBtn.disabled = currentLevel === totalLevels - 1;
}

function changeLevel(direction) {
    const newLevel = currentLevel + direction;
    if (newLevel >= 0 && newLevel < totalLevels) {
        currentLevel = newLevel;
        
        sections[currentLevel - direction]?.querySelectorAll('.timeline-card').forEach(card => {
            card.classList.remove('show');
        });
        
        updateTimeline();
    }
}

updateTimeline();



const firstSectionCards = sections[0].querySelectorAll('.timeline-card');
setTimeout(() => {
    firstSectionCards.forEach(card => card.classList.add('show'));
}, 100);

// Idade

$(".option").click(function(){
   $(".option").removeClass("active");
   $(this).addClass("active");
   
});

$(document).ready(function() {
   console.log('DOM carregado, tentando selecionar .abrir-menu');
   const abrirMenu = document.querySelector('.abrir-menu');
   console.log('Elemento .abrir-menu:', abrirMenu);
   if (abrirMenu) {
       abrirMenu.onclick = function(){
           console.log('Clicou em abrir menu');
           document.documentElement.classList.add('menu-ativo');
       }
   } else {
       console.log('Elemento .abrir-menu não encontrado');
   }

   const fecharMenu = document.querySelector('.fechar-menu');
   if (fecharMenu) {
       fecharMenu.onclick = function(){
           console.log('Clicou em fechar menu');
           document.documentElement.classList.remove("menu-ativo");
       }
   }
});

