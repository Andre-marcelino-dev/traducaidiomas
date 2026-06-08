<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="#" class="brand-link">
            <img
                src="{{ asset('traducaidiomas/img/logo.png') }}"
                alt="Traduca Idiomas"
                class="brand-image opacity-75 shadow" />
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul
                class="nav sidebar-menu flex-column"
                role="navigation"
                aria-label="Main navigation"
                id="navigation">
                <li class="nav-item">
                    <a href="{{ route('admin.dash') }}"
                        class="nav-link {{ request()->routeIs('admin.dash') || request()->routeIs('admin.home') ? 'active is-selected' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.professores.index') }}"
                        class="nav-link {{ request()->routeIs('admin.professores.*') ? 'active is-selected' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Professor</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.alunos.index') }}"
                        class="nav-link {{ request()->routeIs('admin.alunos.*') ? 'active is-selected' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Alunos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.matriculas.index') }}"
                        class="nav-link {{ request()->routeIs('admin.matriculas.*') ? 'active is-selected' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Matrículas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.aulas.index') }}"
                        class="nav-link {{ request()->routeIs('admin.aulas.*') ? 'active is-selected' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Aulas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Serviços</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
