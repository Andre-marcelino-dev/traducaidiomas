@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">


    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="app-content">
        <div class="container-fluid">

            {{-- ── GREETING ── --}}
            <div class="row mb-4 fade-up">
                <div class="col-12">
                    <div class="dash-greeting-card shadow-sm">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="dash-hora" id="dash-hora"></p>
                                <h2 class="dash-nome">
                                    @php
                                        $h = now()->format('H');
                                        $saudacao = $h < 12 ? 'Bom dia' : ($h < 18 ? 'Boa tarde' : 'Boa noite');
                                    @endphp
                                    {{ $saudacao }}, {{ explode(' ', auth('admin')->user()->nome_professor)[0] }}! 👋
                                </h2>
                                <p class="dash-sub">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                                <div class="dash-badge-prof">
                                    <i class="fas fa-shield-halved"></i>
                                    Administrador do sistema
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-flex justify-content-end">
                                <img src="{{ asset('traducaidiomas/professor/' . auth('admin')->user()->foto_professor) }}"
                                    style="width:120px;height:120px;object-fit:cover;border-radius:16px;border:3px solid rgba(255,255,255,.15);"
                                    alt="{{ auth('admin')->user()->nome_professor }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── METRIC CARDS ── --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue fade-up">
                        <div class="mc-icon">👨‍🏫</div>
                        <div class="mc-val" id="totalProfessores" data-target="{{ $totalProfessores }}">0</div>
                        <p class="mc-lbl">Professores</p>
                        <div class="mc-trend">
                            👥 <span id="prof-trend">carregando...</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green fade-up">
                        <div class="mc-icon">🎓</div>
                        <div class="mc-val" id="totalAlunos" data-target="{{ $totalAlunos }}">0</div>
                        <p class="mc-lbl">Alunos</p>
                        <div class="mc-trend">
                            👥 <span id="alunos-trend">carregando...</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green fade-up">
                        <div class="mc-icon">🎓</div>
                        <div class="mc-val" id="totalAulas" data-target="">0</div>
                        <p class="mc-lbl">Aulas</p>
                        <div class="mc-trend">
                            👥 <span id="aulas-trend">carregando...</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="metric-card metric-card-rose shadow-sm">
                        <div class="metric-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="metric-value">{{ now()->format('d/m/Y') }}</div>
                        <p class="metric-label">Hoje</p>
                        <div class="metric-trend">
                            @php
                                $hora = now('America/Sao_Paulo')->hour;
                                if ($hora >= 5 && $hora < 12) {
                                    $icone = '🌅';
                                    $saudacao = 'Bom dia';
                                } elseif ($hora >= 12 && $hora < 18) {
                                    $icone = '☀️';
                                    $saudacao = 'Boa tarde';
                                } else {
                                    $icone = '🌙';
                                    $saudacao = 'Boa noite';
                                }
                            @endphp
                            {{ $icone }} {{ $saudacao }} — {{ now('America/Sao_Paulo')->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TABELA + SIDEBAR ── --}}
            <div class="row g-3">

                {{-- Tabela professores recentes --}}
                <div class="col-lg-8 fade-up">
                    <div class="recent-card card">
                        <div class="card-header">
                            <h5><i class="fas fa-chalkboard-user me-2 text-primary"></i>Professores Recentes</h5>
                            <a href="{{ route('admin.professores.index') }}">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table recent-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Professor</th>
                                        <th>Especialidade</th>
                                        <th>Nível</th>
                                        <th>Experiência</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($professoresRecentes as $prof)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($prof->foto_professor)
                                                        <img src="{{ asset('traducaidiomas/professor/' . $prof->foto_professor) }}"
                                                            class="prof-avatar" alt="{{ $prof->nome_professor }}">
                                                    @else
                                                        <div class="prof-avatar-placeholder">
                                                            {{ strtoupper(substr($prof->nome_professor, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-600" style="font-weight:600;font-size:.875rem;">
                                                            {{ $prof->nome_professor }}</div>
                                                        <div style="font-size:.72rem;color:#94a3b8;">
                                                            {{ $prof->email_professor }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $prof->especialidade_professor ?? '—' }}</td>
                                            <td>
                                                @php $nivel = strtolower($prof->nivel_professor ?? ''); @endphp
                                                <span class="badge-nivel
                                                    {{ $nivel == 'básico' || $nivel == 'basico'
                                                        ? 'badge-basico'
                                                        : ($nivel == 'intermediário' || $nivel == 'intermediario'
                                                            ? 'badge-intermediario'
                                                            : ($nivel == 'avançado' || $nivel == 'avancado'
                                                                ? 'badge-avancado'
                                                                : 'badge-fluente')) }}">
                                                    {{ $prof->nivel_professor ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $prof->experiencia_professor }}
                                                {{ $prof->experiencia_professor == 1 ? 'ano' : 'anos' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.professores.edit', $prof->id_professor) }}"
                                                    class="btn btn-sm btn-light rounded-circle" title="Editar">
                                                    <i class="fas fa-pen fa-xs"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                                Nenhum professor cadastrado ainda
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Sidebar direita --}}
                <div class="col-lg-4 fade-up">

                    {{-- Ações rápidas --}}
                    <div class="card recent-card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-bolt me-2 text-warning"></i>Ações Rápidas</h5>
                        </div>
                        <div class="card-body p-3">
                            <a href="{{ route('admin.professores.create') }}" class="quick-action">
                                <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Novo Professor</div>
                                    <p class="qa-desc">Cadastrar professor</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>
                            <a href="{{ route('admin.professores.index') }}" class="quick-action">
                                <div class="qa-icon" style="background:#ecfdf5;color:#059669;">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Ver Professores</div>
                                    <p class="qa-desc">Listar todos</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>
                            <a href="{{ route('admin.alunos.index') }}" class="quick-action">
                                <div class="qa-icon" style="background:#fffbeb;color:#d97706;">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Ver Alunos</div>
                                    <p class="qa-desc">Listar todos</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                            {{-- ── NOVO: Reagendamentos ── --}}
                            <a href="{{ route('admin.reagendamentos.index') }}" class="quick-action">
                                <div class="qa-icon" style="background:#fef3c7;color:#d97706;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <div class="qa-label d-flex align-items-center gap-2">
                                        Reagendamentos
                                        <span class="badge bg-warning text-dark d-none" id="badgeReagendamentos"
                                            style="font-size:.7rem;border-radius:20px;padding:2px 7px;">0</span>
                                    </div>
                                    <p class="qa-desc">Solicitações dos alunos</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                        </div>
                    </div>

                    {{-- Perfil do admin --}}
                    <div class="card recent-card">
                        <div class="card-header">
                            <h5><i class="fas fa-id-badge me-2 text-info"></i>{{ auth('admin')->user()->nome_professor }}</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-user me-2 text-muted"></i>Nome</span>
                                <span class="stat-mini-value" style="font-size:.8rem;">{{ auth('admin')->user()->nome_professor }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-envelope me-2 text-muted"></i>E-mail</span>
                                <span class="stat-mini-value" style="font-size:.75rem;">{{ auth('admin')->user()->email_professor }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-briefcase me-2 text-muted"></i>Experiência</span>
                                <span class="stat-mini-value">
                                    {{ auth('admin')->user()->experiencia_professor }}
                                    {{ auth('admin')->user()->experiencia_professor == 1 ? 'ano' : 'anos' }}
                                </span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-star me-2 text-muted"></i>Especialidade</span>
                                <span class="stat-mini-value" style="font-size:.8rem;">{{ auth('admin')->user()->especialidade_professor ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Relógio em tempo real --}}
    <script>
        function atualizarHora() {
            const agora = new Date();
            const horas = String(agora.getHours()).padStart(2, '0');
            const min   = String(agora.getMinutes()).padStart(2, '0');
            const seg   = String(agora.getSeconds()).padStart(2, '0');
            const el = document.getElementById('dash-hora');
            if (el) el.textContent = horas + ':' + min + ':' + seg;
        }
        atualizarHora();
        setInterval(atualizarHora, 1000);
    </script>

    {{-- Animação dos contadores --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Alunos
            var el = document.getElementById('totalAlunos');
            var trend = document.getElementById('alunos-trend');
            if (el) {
                var target = parseInt(el.dataset.target) || 0;
                var start  = performance.now();
                function tick(now) {
                    var p    = Math.min((now - start) / 1000, 1);
                    var ease = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.round(ease * target);
                    if (p < 1) requestAnimationFrame(tick);
                    else trend.textContent = target + (target !== 1 ? ' cadastrados' : ' cadastrado');
                }
                requestAnimationFrame(tick);
            }

            // Professores
            var elP    = document.getElementById('totalProfessores');
            var trendP = document.getElementById('prof-trend');
            if (elP) {
                var targetP = parseInt(elP.dataset.target) || 0;
                var startP  = performance.now();
                function tickP(now) {
                    var p    = Math.min((now - startP) / 1000, 1);
                    var ease = 1 - Math.pow(1 - p, 3);
                    elP.textContent = Math.round(ease * targetP);
                    if (p < 1) requestAnimationFrame(tickP);
                    else trendP.textContent = targetP + (targetP !== 1 ? ' cadastrados' : ' cadastrado');
                }
                requestAnimationFrame(tickP);
            }
        });
    </script>

    {{-- Badge de reagendamentos pendentes (atualiza a cada 30s) --}}
    <script>
        function atualizarBadgeReagendamento() {
            fetch('{{ route("admin.reagendamento.notificacoes") }}')
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('badgeReagendamentos');
                    if (!badge) return;
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                })
                .catch(() => {}); // silencia erros de rede
        }
        atualizarBadgeReagendamento();
        setInterval(atualizarBadgeReagendamento, 30000);
    </script>

@endsection