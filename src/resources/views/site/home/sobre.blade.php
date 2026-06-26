@php
    $foto = $siteConfig['sobre_foto']
        ? asset('traducaidiomas/img/' . $siteConfig['sobre_foto'])
        : asset('traducaidiomas/img/imagem.jpg');
@endphp

<section id="sobre-home" class="bio-section">
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
            <h2 class="bio-titulo">{{ $siteConfig['sobre_titulo'] }}</h2>

            <div class="bio-conteudo">
                <p>{!! nl2br(e($siteConfig['sobre_texto'])) !!}</p>
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

            <a href="{{ route('sobre') }}" class="bio-btn">
                Saiba Mais <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
