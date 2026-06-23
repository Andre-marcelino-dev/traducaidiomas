<section id="sobre-home" class="bg-light">
    <div class="container">
        <div class="sobre-container">
            <div class="foto-container">
                @if($siteConfig['sobre_foto'])
                    <img src="{{ asset('traducaidiomas/img/' . $siteConfig['sobre_foto']) }}" alt="Professor" class="foto-perfil">
                @else
                    <img src="{{ asset('traducaidiomas/img/imagem.jpg') }}" alt="Professor Renato Caetano" class="foto-perfil">
                @endif
            </div>
            <div class="texto-sobre">
                <h2>{{ $siteConfig['sobre_titulo'] }}</h2>
                <div class="biografia-texto">
                    <p>{!! nl2br(e($siteConfig['sobre_texto'])) !!}</p>
                </div>
                <a href="{{ route('sobre') }}" class="btn-gradient">SAIBA MAIS</a>
            </div>
        </div>
    </div>
</section>
