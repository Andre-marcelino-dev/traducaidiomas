@extends('aluno.layout.aluno')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Meu Painel</h3>
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
            <div class="row mb-4">
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
                                    {{ $saudacao }}, {{ explode(' ', $aluno->nome_aluno)[0] }}! 👋
                                </h2>
                                <p class="dash-sub">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                                <div class="dash-badge-prof">
                                    <i class="fas fa-graduation-cap"></i>
                                    Aluno matriculado
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-flex justify-content-end">
                                @if($aluno->foto_aluno)
                                    <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                        style="width:120px;height:120px;object-fit:cover;border-radius:16px;border:3px solid rgba(255,255,255,.15);"
                                        alt="{{ $aluno->nome_aluno }}">
                                @else
                                    <div style="width:120px;height:120px;border-radius:16px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:700;color:#fff;">
                                        {{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── METRIC CARDS ── --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="mc mc-blue">
                        <div class="mc-icon">📚</div>
                        <div class="mc-val">{{ $totalAulas ?? 0 }}</div>
                        <p class="mc-lbl">Minhas Aulas</p>
                        <div class="mc-trend">🎓 Aulas disponíveis</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="mc mc-green">
                        <div class="mc-icon">🌍</div>
                        <div class="mc-val">{{ $aluno->curso_aluno ?? '—' }}</div>
                        <p class="mc-lbl">Meu Curso</p>
                        <div class="mc-trend">📖 Em andamento</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="mc mc-blue">
                        <div class="mc-icon">⭐</div>
                        <div class="mc-val">{{ $aluno->nivel_aluno ?? '—' }}</div>
                        <p class="mc-lbl">Meu Nível</p>
                        <div class="mc-trend">🚀 Continue evoluindo</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
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
                                    $icone = '🌅'; $saudacao = 'Bom dia';
                                } elseif ($hora >= 12 && $hora < 18) {
                                    $icone = '☀️'; $saudacao = 'Boa tarde';
                                } else {
                                    $icone = '🌙'; $saudacao = 'Boa noite';
                                }
                            @endphp
                            {{ $icone }} {{ $saudacao }} — {{ now('America/Sao_Paulo')->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PERFIL + AÇÕES ── --}}
            <div class="row g-3">

                {{-- Perfil do aluno --}}
                <div class="col-lg-8">
                    <div class="card recent-card">
                        <div class="card-header">
                            <h5><i class="fas fa-id-badge me-2 text-info"></i>Meu Perfil</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-user me-2 text-muted"></i>Nome</span>
                                <span class="stat-mini-value">{{ $aluno->nome_aluno }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-envelope me-2 text-muted"></i>E-mail</span>
                                <span class="stat-mini-value">{{ $aluno->email_aluno }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-phone me-2 text-muted"></i>Telefone</span>
                                <span class="stat-mini-value">{{ $aluno->telefone_aluno ?? '—' }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-cake-candles me-2 text-muted"></i>Nascimento</span>
                                <span class="stat-mini-value">{{ $aluno->data_nasc_aluno ?? '—' }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-star me-2 text-muted"></i>Nível</span>
                                <span class="stat-mini-value">{{ $aluno->nivel_aluno ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ações rápidas --}}
                <div class="col-lg-4">
                    <div class="card recent-card">
                        <div class="card-header">
                            <h5><i class="fas fa-bolt me-2 text-warning"></i>Ações Rápidas</h5>
                        </div>
                        <div class="card-body p-3">
                            <a href="{{ route('aluno.perfil') }}" class="quick-action">
                                <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;">
                                    <i class="fas fa-user-pen"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Meu Perfil</div>
                                    <p class="qa-desc">Ver e editar dados</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>
                            <a href="#" class="quick-action">
                                <div class="qa-icon" style="background:#ecfdf5;color:#059669;">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Minhas Aulas</div>
                                    <p class="qa-desc">Ver aulas disponíveis</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>
                            <a href="{{ route('aluno.logout') }}" class="quick-action"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="qa-icon" style="background:#fff0f0;color:#e53e3e;">
                                    <i class="fas fa-right-from-bracket"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Sair</div>
                                    <p class="qa-desc">Encerrar sessão</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>
                            <form id="logout-form" action="{{ route('aluno.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

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

@endsection
