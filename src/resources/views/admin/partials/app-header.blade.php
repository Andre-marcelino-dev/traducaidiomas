@php
    $admin = auth('admin')->user();
    $fotoProfessor = $admin?->foto_professor
        ? asset('traducaidiomas/professor/' . $admin->foto_professor)
        : null;

    $iniciais = $admin?->nome_professor
        ? strtoupper(substr($admin->nome_professor, 0, 2))
        : 'AD';
@endphp

<style>
    .admin-header-avatar {
        width: 38px;
        height: 38px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }

    .admin-header-avatar-placeholder {
        width: 38px;
        height: 38px;
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

    .admin-user-name {
        font-weight: 600;
        color: #1e293b;
        margin-left: .45rem;
    }

    .admin-dropdown-header {
        background: linear-gradient(135deg, #1a1a2e, #0f3460);
        color: #fff;
        text-align: center;
        padding: 1.3rem 1rem;
    }

    .admin-dropdown-avatar {
        width: 78px;
        height: 78px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.35);
        box-shadow: 0 8px 18px rgba(0,0,0,.25);
        margin-bottom: .7rem;
    }

    .admin-dropdown-avatar-placeholder {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,.35);
        background: rgba(255,255,255,.15);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0 auto .7rem;
    }

    .admin-dropdown-name {
        font-size: .95rem;
        font-weight: 800;
        margin-bottom: .25rem;
    }

    .admin-dropdown-info {
        font-size: .76rem;
        opacity: .9;
        margin-bottom: .25rem;
    }

    .admin-dropdown-menu {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(15, 23, 42, .18);
    }

    .admin-dropdown-footer {
        padding: .85rem;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
    }

    .admin-dropdown-footer .btn {
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
        border-color: #10b981;
        box-shadow: none;
    }

    .panel-header-search .input-group:focus-within .input-group-text {
        border-color: #10b981;
    }

    @media (max-width: 991.98px) {
        .panel-header-search-wrap {
            display: none;
        }
    }

</style>

<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">

        {{-- Links da esquerda --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" class="nav-link">
                    Home
                </a>
            </li>
        </ul>

        {{-- Links da direita --}}
        <div class="panel-header-search-wrap flex-grow-1 d-none d-lg-flex justify-content-center px-3">
            @php
                $adminSearchItems = [
                    ['label' => 'Dashboard', 'keywords' => ['dashboard', 'painel', 'inicio', 'home'], 'url' => route('admin.dash')],
                    ['label' => 'Professores', 'keywords' => ['professor', 'professores', 'docentes'], 'url' => route('admin.professores.index')],
                    ['label' => 'Alunos', 'keywords' => ['aluno', 'alunos', 'estudantes'], 'url' => route('admin.alunos.index')],
                    ['label' => 'Agendas', 'keywords' => ['agenda', 'agendas', 'calendario'], 'url' => route('admin.agendas.index')],
                    ['label' => 'Aulas', 'keywords' => ['aula', 'aulas', 'classes'], 'url' => route('admin.aulas.index')],
                    ['label' => 'Matriculas', 'keywords' => ['matricula', 'matriculas', 'inscricoes'], 'url' => route('admin.matriculas.index')],
                    ['label' => 'Materiais', 'keywords' => ['material', 'materiais', 'arquivos'], 'url' => route('admin.materiais.index')],
                    ['label' => 'Presencas', 'keywords' => ['presenca', 'presencas', 'frequencia'], 'url' => route('admin.presenca.index')],
                ];
            @endphp

            <form class="panel-header-search" data-panel-search data-search-empty-url="{{ route('admin.dash') }}">
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
                        placeholder="Pesquisar alunos, aulas, materiais ou agendas..."
                        autocomplete="off"
                        list="admin-header-search-options"
                        data-search-input
                    >
                </div>
            </form>

            <datalist id="admin-header-search-options">
                @foreach ($adminSearchItems as $item)
                    <option value="{{ $item['label'] }}"></option>
                @endforeach
            </datalist>
        </div>
        <ul class="navbar-nav ms-auto">

            {{-- Tela cheia --}}
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none;"></i>
                </a>
            </li>

            {{-- Menu do usuário --}}
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">

                    @if($fotoProfessor)
                        <img src="{{ $fotoProfessor }}" class="admin-header-avatar" alt="Foto do professor">
                    @else
                        <span class="admin-header-avatar-placeholder">
                            {{ $iniciais }}
                        </span>
                    @endif

                    <span class="admin-user-name d-none d-md-inline">
                        {{ $admin->nome_professor ?? 'Administrador' }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end admin-dropdown-menu">

                    {{-- Header --}}
                    <li class="admin-dropdown-header">
                        @if($fotoProfessor)
                            <img src="{{ $fotoProfessor }}" class="admin-dropdown-avatar" alt="Foto do professor">
                        @else
                            <div class="admin-dropdown-avatar-placeholder">
                                {{ $iniciais }}
                            </div>
                        @endif

                        <div class="admin-dropdown-name">
                            {{ $admin->nome_professor ?? 'Administrador' }}
                        </div>

                        <div class="admin-dropdown-info">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Graduado em Letras & Idiomas
                        </div>

                        <div class="admin-dropdown-info">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Desde
                            {{ $admin?->criado_em_professor ? date('d/m/Y', strtotime($admin->criado_em_professor)) : '—' }}
                        </div>

                        <div class="admin-dropdown-info">
                            <i class="fas fa-briefcase me-1"></i>
                            {{ $admin->experiencia_professor ?? 'Experiência não informada' }}
                        </div>
                    </li>

                    {{-- Footer --}}
                    <li class="admin-dropdown-footer">
                       

                        <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
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

        const searchItems = @json($adminSearchItems);

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
