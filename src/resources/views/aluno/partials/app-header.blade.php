<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
                </a>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    @if(auth('aluno')->user()->foto_aluno)
                        <img src="{{ asset('traducaidiomas/alunos/' . auth('aluno')->user()->foto_aluno) }}"
                            style="width:40px;height:40px;object-fit:cover;" class="rounded-circle shadow me-1">
                    @endif
                    {{ auth('aluno')->user()->nome_aluno }}
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        @if(auth('aluno')->user()->foto_aluno)
                            <img src="{{ asset('traducaidiomas/alunos/' . auth('aluno')->user()->foto_aluno) }}"
                                style="width:50px;height:50px;object-fit:cover;" class="rounded-circle shadow">
                        @endif
                        <p>
                            {{ auth('aluno')->user()->nome_aluno }}
                            <small>{{ auth('aluno')->user()->curso_aluno }}</small>
                            <small>Nível: {{ auth('aluno')->user()->nivel_aluno }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('aluno.perfil') }}" class="btn btn-outline-secondary">Perfil</a>
                        <form action="{{ route('aluno.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger float-end">Sair</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
