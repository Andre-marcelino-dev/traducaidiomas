<div class="services-container">
    @foreach ($servicos as $servico)
        <div class="service-card categoria-{{ $servico->id_categoria }}">
            
            <!-- Faixa Azul com Título Destacado -->
            <div class="faixa-azul">
                <h1 class="titulo-servico-destaque">
                    {{ $servico->titulo_servico }}
                </h1>
            </div>

            <div class="teacher-flag"></div>
            
            <h2 class="teacher-name">
                {{ $servico->nome_professor }}
            </h2>
 
            <p class="teacher-title">
                {{ $servico->titulo_professor_servico }}
            </p>
 
            <ul class="services-list">
                <li>
                    <div class="service-icon">📚</div>
                    <span class="service-desc">{{ $servico->subtitulo_servico }}</span>
                </li>
 
                <li>
                    <div class="service-icon">🎯</div>
                    <span class="service-desc">{{ $servico->subtitulo_servico }}</span>
                </li>
 
                <li>
                    <div class="service-icon">💼</div>
                    <span class="service-desc">{{ $servico->titulo_servico}}</span>
                </li>
 
                <li>
                    <div class="service-icon">👥</div>
                    <span class="service-desc">{{ $servico->subtitulo_servico }}</span>
                </li>
 
                <li>
                    <div class="service-icon">📱</div>
                    <span class="service-desc">{{ $servico->titulo_servico}}</span>
                </li>
            </ul>
 
            <div class="contact-section">
                <h3>{{ $servico->chamada }}</h3>
                <p>{{ $servico->preco }}</p>
 
                <a href="https://wa.me/{{ $servico->whatsapp }}"
                   class="contact-btn" target="_blank">
                    WhatsApp Agora
                </a>
            </div>
 
        </div>
    @endforeach
 
</div>
 
