<aside class="app-sidebar shadow" data-bs-theme="dark" style="background:linear-gradient(180deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%) !important;">

    <div class="sidebar-brand">
        <a href="#" class="brand-link">
            @php
            $logoFile = \App\Models\ConfiguracaoPainel::get('logo_painel') ?: 'logo.png';
            $logoPath = public_path('traducaidiomas/img/' . $logoFile);
            $logoVer = file_exists($logoPath) ? filemtime($logoPath) : time();
            @endphp
            <img src="{{ asset('traducaidiomas/img/' . $logoFile) }}?v={{ $logoVer }}" alt="Traduca Idiomas"
                class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Traducaidiomas</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            GESTÃO DE CATÁLOGO
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.professores.index') }}" class="nav-link active">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Professor</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.alunos.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Alunos</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.matriculas.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Matriculas</p>
                            </a>
                        </li>





                        <li class="nav-item">
                            <a href="{{ route('admin.aulas.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Aulas</p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ route('admin.presenca.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Presença</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="{{ route('admin.servicos.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Serviços</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="{{ route('admin.agendas.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Agenda</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="{{ route('admin.materiais.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Materiais</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.atividades.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-clipboard-check"></i>
                                <p>Atividades</p>
                            </a>
                        </li>



                    </ul>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-tree-fill"></i>
                        <p>
                            SITE
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.site.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Gerenciar Site</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>