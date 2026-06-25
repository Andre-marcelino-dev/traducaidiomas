@php
    $aluno = auth('aluno')->user();

    $fotoAluno = $aluno?->foto_aluno
        ? asset('traducaidiomas/alunos/' . $aluno->foto_aluno)
        : null;

    $iniciais = $aluno?->nome_aluno
        ? strtoupper(substr($aluno->nome_aluno, 0, 2))
        : 'AL';
@endphp

<style>
    @keyframes profileDropdownIn {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.96);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .user-menu .dropdown-menu {
        display: block;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(12px) scale(.96);
        transform-origin: top right;
        transition: opacity .22s ease, transform .22s ease, visibility .22s ease;
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(15, 23, 42, .18);
    }

    .user-menu .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateY(0) scale(1);
        animation: profileDropdownIn .25s ease-out both;
    }

    .user-menu .dropdown-toggle::after {
        transition: transform .2s ease;
    }

    .user-menu .dropdown-toggle.show::after {
        transform: rotate(180deg);
    }

    .aluno-header-avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .aluno-header-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a1a2e, #4f46e5);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
        font-weight: 800;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .aluno-user-header {
        background: linear-gradient(135deg, #1a1a2e, #0f3460, #4f46e5);
        color: #fff;
        text-align: center;
        padding: 1.2rem 1rem;
    }

    .aluno-dropdown-avatar {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.35);
        box-shadow: 0 8px 18px rgba(0,0,0,.25);
        margin-bottom: .6rem;
    }

    .aluno-dropdown-avatar-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.35);
        background: rgba(255,255,255,.15);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 800;
        margin: 0 auto .6rem;
    }

    .aluno-dropdown-footer {
        padding: .85rem;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
    }

    .aluno-dropdown-footer .btn {
        border-radius: 10px;
        font-weight: 600;
        font-size: .82rem;
    }


    .panel-header-search {
        width: 100%;
        max-width: 520px;
    }

    .panel-header-search .input-group-text {
        border-color: #dbe3ef;
        background: #fff;
        color: #64748b;
        border-radius: 12px 0 0 12px;
    }

    .panel-header-search .form-control {
        height: 40px;
        border-color: #dbe3ef;
        border-left: 0;
        border-radius: 0 12px 12px 0;
        font-size: .875rem;
        box-shadow: none;
    }

    .panel-header-search .form-control:focus {
        border-color: #4f46e5;
        box-shadow: none;
    }

    .panel-header-search .input-group:focus-within .input-group-text {
        border-color: #4f46e5;
    }

    @media (max-width: 991.98px) {
        .panel-header-search-wrap {
            display: none;
        }
    }

</style>

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

        <div class="panel-header-search-wrap flex-grow-1 d-none d-lg-flex justify-content-center px-3">
            @php
                $alunoSearchItems = [
                    ['label' => 'Dashboard', 'keywords' => ['dashboard', 'painel', 'inicio', 'home'], 'url' => route('aluno.dash')],
                    ['label' => 'Minhas Aulas', 'keywords' => ['aulas', 'agenda', 'minhas aulas'], 'url' => route('aluno.aulas.index')],
                    ['label' => 'Materiais', 'keywords' => ['material', 'materiais', 'apoio', 'downloads'], 'url' => route('aluno.materiais.index')],
                    ['label' => 'Atividades', 'keywords' => ['atividade', 'atividades', 'tarefas'], 'url' => route('aluno.atividades.index')],
                    ['label' => 'Progresso', 'keywords' => ['progresso', 'evolucao', 'desempenho'], 'url' => route('aluno.progresso.index')],
                    ['label' => 'Perfil', 'keywords' => ['perfil', 'conta', 'meus dados'], 'url' => route('aluno.perfil')],
                ];
            @endphp

            <form class="panel-header-search" data-panel-search data-search-empty-url="{{ route('aluno.dash') }}">
                <div class="input-group">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </span>
                    <input
                        type="search"
                        class="form-control"
                        placeholder="Pesquisar aulas, materiais, atividades ou perfil..."
                        autocomplete="off"
                        list="aluno-header-search-options"
                        data-search-input
                    >
                </div>
            </form>

            <datalist id="aluno-header-search-options">
                @foreach ($alunoSearchItems as $item)
                    <option value="{{ $item['label'] }}"></option>
                @endforeach
            </datalist>
        </div>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
                </a>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    @if($fotoAluno)
                        <img src="{{ $fotoAluno }}" class="aluno-header-avatar" alt="Foto do aluno">
                    @else
                        <span class="aluno-header-avatar-placeholder">{{ $iniciais }}</span>
                    @endif

                    <span class="d-none d-md-inline">
                        {{ $aluno->nome_aluno }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="aluno-user-header">
                        @if($fotoAluno)
                            <img src="{{ $fotoAluno }}" class="aluno-dropdown-avatar" alt="Foto do aluno">
                        @else
                            <div class="aluno-dropdown-avatar-placeholder">
                                {{ $iniciais }}
                            </div>
                        @endif

                        <p class="mb-0">
                            <strong>{{ $aluno->nome_aluno }}</strong>
                            <small class="d-block mt-1">{{ $aluno->curso_aluno ?? 'Curso não informado' }}</small>
                            <small class="d-block">Nível: {{ $aluno->nivel_aluno ?? 'Não informado' }}</small>
                        </p>
                    </li>

                    <li class="aluno-dropdown-footer">
                        <a href="{{ route('aluno.perfil') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-user me-1"></i> Perfil
                        </a>

                        <form action="{{ route('aluno.logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-right-from-bracket me-1"></i> Sair
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchForm = document.querySelector('[data-panel-search]');
        const searchInput = searchForm?.querySelector('[data-search-input]');

        if (!searchForm || !searchInput) {
            return;
        }

        const searchItems = @json($alunoSearchItems);

        function normalizeText(value) {
            return String(value || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[̀-ͯ]/g, '')
                .trim();
        }

        function findMatch(term) {
            const normalizedTerm = normalizeText(term);

            if (!normalizedTerm) {
                return searchForm.dataset.searchEmptyUrl;
            }

            const matchedItem = searchItems.find(function (item) {
                const labelMatch = normalizeText(item.label).includes(normalizedTerm);
                const keywordMatch = (item.keywords || []).some(function (keyword) {
                    const normalizedKeyword = normalizeText(keyword);
                    return normalizedKeyword.includes(normalizedTerm) || normalizedTerm.includes(normalizedKeyword);
                });

                return labelMatch || keywordMatch;
            });

            return matchedItem ? matchedItem.url : null;
        }

        searchForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const targetUrl = findMatch(searchInput.value);

            if (targetUrl) {
                window.location.href = targetUrl;
                return;
            }

            searchInput.classList.add('is-invalid');
        });

        searchInput.addEventListener('input', function () {
            searchInput.classList.remove('is-invalid');
        });

        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchForm.requestSubmit();
            }
        });
    });
</script>
