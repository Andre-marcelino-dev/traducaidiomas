<?php
use App\Models\ConfiguracaoPainel;
$sobreFoto  = ConfiguracaoPainel::get('sobre_foto', '');
$sobreTexto = ConfiguracaoPainel::get('sobre_pagina_texto', 'Atuo no setor corporativo e acadêmico desenvolvendo projetos educacionais, treinamentos e materiais didáticos. Já traduzi conteúdos educacionais, institucionais e publicitários para clientes como Mercedes-Benz e Enel, e trabalho como Tradutor Sênior de inglês e italiano. Passei por cargos de liderança pedagógica no Yázigi e na Bridge English Brasil, onde coordenei programas de inglês para multinacionais como Walmart, BASF, FMGlobal e 3M. Hoje, sou docente no Senac São Paulo, atuando com metodologias baseadas em projetos, e professor de italiano no Spazio Italiano, onde também preparo alunos para o exame CILS, do qual sou aplicador certificado.');
$fotoSrc    = $sobreFoto ? asset('traducaidiomas/img/' . $sobreFoto) : asset('traducaidiomas/img/globo2.png');
?>
<section id="biografia-home" class="home-light">
    <div class="container">
        <div class="sobre-container">
            <div class="imagem-container">
                <img src="<?= $fotoSrc ?>" alt="Professor Caetano" class="img-perfil">
            </div>
            <div class="texto-sobre">
                <h2>Biografia</h2>
                <div class="biografia-texto">
                    <p><?= nl2br(e($sobreTexto)) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
