<aside class="app-sidebar shadow" data-bs-theme="dark" style="background:linear-gradient(180deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%) !important;">
    <div class="sidebar-brand">
        <a href="{{ route('aluno.dash') }}" class="brand-link">
            @php
                $logoFile = \App\Models\ConfiguracaoPainel::get('logo_painel') ?: 'logo.png';
                $logoPath = public_path('traducaidiomas/img/' . $logoFile);
                $logoVer = file_exists($logoPath) ? filemtime($logoPath) : time();
            @endphp
            <img src="{{ asset('traducaidiomas/img/' . $logoFile) }}?v={{ $logoVer }}"
                alt="Traduca Idiomas" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Portal do Aluno</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview"
                role="navigation" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('aluno.dash') }}" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-book-fill"></i>
                        <p>
                            MEUS ESTUDOS
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('aluno.aulas.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Minhas Aulas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('aluno.progresso.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Meu Progresso</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('aluno.atividades.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Minhas Atividades</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('aluno.perfil') }}" class="nav-link">
                        <i class="nav-icon bi bi-person-fill"></i>
                        <p>Meu Perfil</p>
                    </a>
                </li>

                <li class="nav-item">
                    <form action="{{ route('aluno.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Sair</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>