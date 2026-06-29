@php
    use App\Models\ConfiguracaoPainel;
    $sobreFoto  = ConfiguracaoPainel::get('sobre_foto', '');
    $sobreTexto = ConfiguracaoPainel::get('sobre_pagina_texto', 'Atuo no setor corporativo e acadêmico desenvolvendo projetos educacionais, treinamentos e materiais didáticos. Já traduzi conteúdos educacionais, institucionais e publicitários para clientes como Mercedes-Benz e Enel, e trabalho como Tradutor Sênior de inglês e italiano. Passei por cargos de liderança pedagógica no Yázigi e na Bridge English Brasil, onde coordenei programas de inglês para multinacionais como Walmart, BASF, FMGlobal e 3M. Hoje, sou docente no Senac São Paulo, atuando com metodologias baseadas em projetos, e professor de italiano no Spazio Italiano, onde também preparo alunos para o exame CILS, do qual sou aplicador certificado.');
    $foto = $sobreFoto ? asset('traducaidiomas/img/' . $sobreFoto) : asset('traducaidiomas/img/globo2.png');
@endphp

<section id="biografia-home" class="bio-section">
    <div class="bio-wrapper">
        <div class="bio-foto-col">
            <div class="bio-foto-frame">
                <img src="{{ $foto }}" alt="Professor Renato Caetano" class="bio-foto">
                <div class="bio-foto-badge">
                    <i class="fa-solid fa-award"></i> Professor Trilíngue
                </div>
            </div>
        </div>

        <div class="bio-texto-col">
            <span class="bio-eyebrow">Conheça o Professor</span>
            <h2 class="bio-titulo">Biografia</h2>

            <div class="bio-conteudo">
                <p>{!! nl2br(e($sobreTexto)) !!}</p>
            </div>

            <div class="bio-stats">
                <div class="bio-stat">
                    <span class="bio-stat-num">3</span>
                    <span class="bio-stat-label">Idiomas</span>
                </div>
                <div class="bio-stat">
                    <span class="bio-stat-num">10+</span>
                    <span class="bio-stat-label">Anos de experiência</span>
                </div>
                <div class="bio-stat">
                    <span class="bio-stat-num">500+</span>
                    <span class="bio-stat-label">Alunos formados</span>
                </div>
            </div>

            <a href="{{ route('contato') }}" class="bio-btn">
                Entre em Contato <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>