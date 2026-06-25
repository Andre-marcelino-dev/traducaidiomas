<section class="banner animate__animated animate__zoomIn">
    <?php
    $bannerUrl = (isset($configSiteBanner) && !empty($configSiteBanner) && file_exists(__DIR__ . '/../uploads/banner/' . $configSiteBanner))
        ? "uploads/banner/" . htmlspecialchars($configSiteBanner)
        : "img/banner.png";
    ?>
    <div class="banner-slide" style="--banner-image: url('<?= $bannerUrl ?>');">
        <div class="banner-media" style="background-image: url('<?= $bannerUrl ?>');"></div>
        <div class="banner-overlay"></div>
        <div class="banner-grid container">
            <div class="banner-card">
                <span class="banner-eyebrow">TraducaIdiomas English & Professional Skills</span>
                <h1>Inglês profissional com método claro, foco em resultado e consist&ecirc;ncia.</h1>
                <p>Treinamento para reuniões, entrevistas e apresentações, com metas reais e acompanhamento pr&oacute;ximo.</p>
                <div class="banner-actions">
                    <a class="btn btn-primary" href="contato.php">Agendar Diagn&oacute;stico</a>
                    <a class="btn btn-outline-light" href="{{ route('servicos') }}">Ver Serviços</a>
                </div>
                <div class="banner-trust">
                    <div class="trust-item">
                        <span class="trust-title">Plano Individual</span>
                        <small>Roteiro pensado para seu objetivo</small>
                    </div>
                    <div class="trust-item">
                        <span class="trust-title">Aulas Objetivas</span>
                        <small>Com foco direto em performance</small>
                    </div>
                </div>
            </div>
            <div class="banner-metrics">
                <div class="metric-card">
                    <span class="metric-label">Foco</span>
                    <strong>Neg&oacute;cios, entrevistas e fluência</strong>
                </div>
                <div class="metric-card">
                    <span class="metric-label">Formato</span>
                    <strong>Aulas ao vivo com acompanhamento</strong>
                </div>
                <div class="metric-card">
                    <span class="metric-label">Resultado</span>
                    <strong>Comunicação confiante e objetiva</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="banner-slide" style="--banner-image: url('img/banner2.png');">
        <div class="banner-media" style="background-image: url('img/banner2.png');"></div>
        <div class="banner-overlay"></div>
        <div class="banner-grid container">
            <div class="banner-card">
                <span class="banner-eyebrow">Expertise em idiomas com estrat&eacute;gia</span>
                <h1>Da aula &agrave; aplica&ccedil;&atilde;o real: comunicação eficiente no seu contexto.</h1>
                <p>Metodologia pr&aacute;tica, material objetivo e feedback cont&iacute;nuo para acelerar sua evolução.</p>
                <div class="banner-actions">
                    <a class="btn btn-primary" href="contato.php">Falar com Especialista</a>
                    <a class="btn btn-outline-light" href="sobre.php">Conheça a Escola</a>
                </div>
                <div class="banner-trust">
                    <div class="trust-item">
                        <span class="trust-title">Metodologia Direta</span>
                        <small>Foco no que destrava sua fala</small>
                    </div>
                    <div class="trust-item">
                        <span class="trust-title">Performance</span>
                        <small>Resultados mensur&aacute;veis na pr&aacute;tica</small>
                    </div>
                </div>
            </div>
            <div class="banner-metrics">
                <div class="metric-card">
                    <span class="metric-label">Aulas</span>
                    <strong>Planos flex&iacute;veis e personalizados</strong>
                </div>
                <div class="metric-card">
                    <span class="metric-label">Suporte</span>
                    <strong>Feedback pr&oacute;ximo e ajustes contínuos</strong>
                </div>
                <div class="metric-card">
                    <span class="metric-label">Confiança</span>
                    <strong>Fale com clareza em qualquer cen&aacute;rio</strong>
                </div>
            </div>
        </div>
    </div>
</section>