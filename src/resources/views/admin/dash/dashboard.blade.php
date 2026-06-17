@extends('admin.layout.admin')

@section('content')

{{-- ══ BREADCRUMB ══ --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Dashboard</h3></div>
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

    {{-- ══ ROW 1 — GREETING ══ --}}
    <div class="row mb-4 fade-up">
        <div class="col-12">
            <div class="dash-greeting-card shadow">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="dash-hora" id="dash-hora"></p>
                        <h2 class="dash-nome">
                            @php $h = now()->format('H'); $saudacao = $h < 12 ? 'Bom dia' : ($h < 18 ? 'Boa tarde' : 'Boa noite'); @endphp
                            {{ $saudacao }}, {{ explode(' ', auth('admin')->user()->nome_professor)[0] }}! 👋
                        </h2>
                        <p class="dash-sub">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                        <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">
                            <div class="dash-badge-prof"><span class="dash-live-dot me-1"></span> Online agora</div>
                            <div class="dash-badge-prof"><i class="fas fa-shield-halved"></i> Administrador do sistema</div>
                            @if($totalReagendamentosPendentes > 0)
                                <a href="{{ route('admin.reagendamentos.index') }}" class="dash-badge-prof" style="background:rgba(244,63,94,.2);border-color:rgba(244,63,94,.3);color:#fda4af;text-decoration:none;">
                                    <i class="fas fa-bell"></i> {{ $totalReagendamentosPendentes }} reagendamento{{ $totalReagendamentosPendentes > 1 ? 's' : '' }} pendente{{ $totalReagendamentosPendentes > 1 ? 's' : '' }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 d-none d-md-flex justify-content-end">
                        <img src="{{ asset('traducaidiomas/professor/' . auth('admin')->user()->foto_professor) }}"
                            class="dash-photo"
                            alt="{{ auth('admin')->user()->nome_professor }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ROW 2 — MÉTRICAS ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-blue shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-chalkboard-user"></i></div>
                <div class="mc-val" id="ctr-prof" data-target="{{ $totalProfessores }}">0</div>
                <p class="mc-lbl">Professores</p>
                <div class="mc-trend"><i class="fas fa-circle me-1" style="font-size:.4rem;"></i> cadastrados</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-green shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-user-graduate"></i></div>
                <div class="mc-val" id="ctr-alunos" data-target="{{ $totalAlunos }}">0</div>
                <p class="mc-lbl">Alunos</p>
                <div class="mc-trend"><i class="fas fa-circle-check me-1"></i> {{ $alunosAtivos }} ativos</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-amber shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-book-open-reader"></i></div>
                <div class="mc-val" id="ctr-aulas" data-target="{{ $totalAulas }}">0</div>
                <p class="mc-lbl">Aulas</p>
                <div class="mc-trend"><i class="fas fa-calendar-day me-1"></i> {{ $aulasHoje->count() }} hoje</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-rose shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-id-card-clip"></i></div>
                <div class="mc-val" id="ctr-mat" data-target="{{ $matriculasAtivas }}">0</div>
                <p class="mc-lbl">Matrículas Ativas</p>
                <div class="mc-trend"><i class="fas fa-circle me-1" style="font-size:.4rem;"></i> em andamento</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc shadow-sm h-100" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);">
                <div class="mc-icon"><i class="fas fa-user-check"></i></div>
                <div class="mc-val">{{ $taxaPresenca }}%</div>
                <p class="mc-lbl">Taxa de Presença</p>
                <div class="mc-trend" style="margin-top:.4rem;">
                    <div style="flex:1;height:5px;background:rgba(255,255,255,.2);border-radius:99px;overflow:hidden;">
                        <div style="width:{{ $taxaPresenca }}%;height:100%;background:#10b981;border-radius:99px;transition:width 1s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Visão de Hoje & Tendências</div>

    {{-- ══ ROW 3 — AULAS HOJE + GRÁFICO MENSAL ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6>
                        <span style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;animation:pulse 2s infinite;"></span>
                        Aulas Hoje
                    </h6>
                    <span style="background:#f0fdf4;color:#065f46;font-size:.68rem;font-weight:700;padding:.2rem .65rem;border-radius:50px;">{{ $aulasHoje->count() }} aula{{ $aulasHoje->count() !== 1 ? 's' : '' }}</span>
                </div>
                <div class="card-body p-3">
                    @forelse($aulasHoje as $aula)
                        @php $ht = \Carbon\Carbon::parse($aula->hora_aulas); @endphp
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="hour">{{ $ht->format('H:i') }}</div>
                            </div>
                            <div class="schedule-dot"></div>
                            <div class="schedule-info flex-grow-1 overflow-hidden">
                                <div class="title">{{ Str::limit($aula->titulo_aulas, 28) }}</div>
                                <div class="sub">
                                    @if($aula->cursos_aulas) {{ $aula->cursos_aulas }} &nbsp;·&nbsp; @endif
                                    @if($aula->professor) {{ $aula->professor->nome_professor }} @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="today-empty">
                            <i class="fas fa-mug-hot fa-2x opacity-25"></i>
                            <span>Nenhuma aula agendada para hoje</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-8 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6><i class="fas fa-chart-line text-primary"></i> Aulas nos Últimos 6 Meses</h6>
                </div>
                <div class="card-body py-3 px-3">
                    <canvas id="chartAulas" style="max-height:220px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Análises & Distribuição</div>

    {{-- ══ ROW 4 — GRÁFICOS ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-user-check text-success"></i> Presença Geral</h6></div>
                <div class="card-body d-flex align-items-center justify-content-center py-2">
                    <canvas id="chartPresenca" style="max-height:200px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-layer-group text-info"></i> Alunos por Nível</h6></div>
                <div class="card-body d-flex align-items-center justify-content-center py-2">
                    <canvas id="chartNiveis" style="max-height:200px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-star-half-stroke text-warning"></i> Distribuição de Notas</h6></div>
                <div class="card-body py-2 px-3">
                    <canvas id="chartNotas" style="max-height:200px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-language" style="color:#6366f1;"></i> Alunos por Idioma</h6></div>
                <div class="card-body p-3">
                    @php $maxCurso = $alunosPorCurso->max('total') ?: 1; @endphp
                    @forelse($alunosPorCurso as $i => $curso)
                        @php $cores = ['#6366f1','#10b981','#f59e0b','#0ea5e9','#f43f5e','#8b5cf6']; $cor = $cores[$i % count($cores)]; $pct = round($curso->total / $maxCurso * 100); @endphp
                        <div class="course-bar-item">
                            <div class="course-bar-label">
                                <span>{{ $curso->nome_curso }}</span>
                                <span style="font-weight:700;color:{{ $cor }};">{{ $curso->total }}</span>
                            </div>
                            <div class="course-bar-track">
                                <div class="course-bar-fill" style="width:{{ $pct }}%;background:{{ $cor }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="today-empty"><i class="fas fa-inbox opacity-25"></i><span>Sem dados</span></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Alunos & Ações Rápidas</div>

    {{-- ══ ROW 5 — ALUNOS RECENTES + AÇÕES + FRASES ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8 fade-up">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-user-graduate text-success"></i> Alunos Recentes</h6>
                    <a href="{{ route('admin.alunos.index') }}">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead><tr><th>Aluno</th><th>Curso</th><th>Nível</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($alunosRecentes as $aluno)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($aluno->foto_aluno)
                                                <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}" class="prof-avatar" alt="">
                                            @else
                                                <div class="prof-avatar-placeholder">{{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}</div>
                                            @endif
                                            <div>
                                                <div style="font-weight:600;font-size:.875rem;">{{ $aluno->nome_aluno }}</div>
                                                <div style="font-size:.7rem;color:#94a3b8;">{{ $aluno->email_aluno }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:.82rem;">{{ $aluno->curso_aluno ?? '—' }}</td>
                                    <td>
                                        @php $nv = strtolower($aluno->nivel_aluno ?? ''); @endphp
                                        <span class="badge-nivel {{ str_contains($nv,'básic')||str_contains($nv,'basic')||str_contains($nv,'inic') ? 'badge-basico' : (str_contains($nv,'inter') ? 'badge-intermediario' : (str_contains($nv,'avan') ? 'badge-avancado' : 'badge-fluente')) }}">
                                            {{ $aluno->nivel_aluno ?? '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(strtoupper($aluno->status_aluno ?? '') === 'EM CURSO')
                                            <span class="tbl-status tbl-status-ativo"><span class="tbl-status-dot"></span> Ativo</span>
                                        @elseif(in_array(strtoupper($aluno->status_aluno ?? ''),['CONCLUÍDO','CONCLUIDO']))
                                            <span class="tbl-status tbl-status-concluido"><span class="tbl-status-dot"></span> Concluído</span>
                                        @else
                                            <span class="tbl-status tbl-status-inativo"><span class="tbl-status-dot"></span> Inativo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>Nenhum aluno cadastrado ainda</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card mb-3">
                <div class="d-card-header"><h6><i class="fas fa-bolt text-warning"></i> Ações Rápidas</h6></div>
                <div class="card-body p-3">
                    <a href="{{ route('admin.professores.create') }}" class="quick-action">
                        <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;"><i class="fas fa-user-plus"></i></div>
                        <div><div class="qa-label">Novo Professor</div><p class="qa-desc">Cadastrar professor</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('admin.alunos.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#ecfdf5;color:#059669;"><i class="fas fa-user-graduate"></i></div>
                        <div><div class="qa-label">Ver Alunos</div><p class="qa-desc">Listar todos</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('admin.presenca.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-clipboard-check"></i></div>
                        <div><div class="qa-label">Registrar Presença</div><p class="qa-desc">Chamada de aulas</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('admin.reagendamentos.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="qa-label d-flex align-items-center gap-2">
                                Reagendamentos
                                <span class="badge bg-warning text-dark d-none" id="badgeReagendamentos" style="font-size:.65rem;border-radius:20px;padding:1px 6px;">0</span>
                            </div>
                            <p class="qa-desc">Solicitações dos alunos</p>
                        </div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                </div>
            </div>

            <div class="d-card" id="card-frases">
                <div class="d-card-header" style="background:linear-gradient(135deg,#f8faff,#eef3ff);">
                    <h6><i class="fas fa-language" style="color:#6366f1;"></i> Frase do Momento</h6>
                </div>
                <div class="card-body p-4 text-center" style="min-height:140px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div id="frase-flag" style="font-size:1.4rem;margin-bottom:6px;">🇺🇸</div>
                    <p id="frase-texto" style="font-size:.92rem;font-weight:600;color:#1e293b;min-height:40px;margin:0 0 8px;line-height:1.45;transition:opacity .4s;"></p>
                    <div id="frase-divisor" style="width:36px;height:2px;background:linear-gradient(90deg,#6366f1,#a78bfa);border-radius:99px;margin:0 auto 8px;opacity:0;transition:opacity .4s;"></div>
                    <p id="frase-traducao" style="font-size:.8rem;color:#6366f1;font-style:italic;min-height:32px;margin:0;opacity:0;transition:opacity .4s;"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Equipe & Desempenho</div>

    {{-- ══ ROW 6 — PROFESSORES + RANKING ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8 fade-up">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-chalkboard-user text-primary"></i> Professores Recentes</h6>
                    <a href="{{ route('admin.professores.index') }}">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead><tr><th>Professor</th><th>Especialidade</th><th>Nível</th><th>Experiência</th><th></th></tr></thead>
                        <tbody>
                            @forelse($professoresRecentes as $prof)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($prof->foto_professor)
                                                <img src="{{ asset('traducaidiomas/professor/' . $prof->foto_professor) }}" class="prof-avatar" alt="">
                                            @else
                                                <div class="prof-avatar-placeholder">{{ strtoupper(substr($prof->nome_professor, 0, 2)) }}</div>
                                            @endif
                                            <div>
                                                <div style="font-weight:600;font-size:.875rem;">{{ $prof->nome_professor }}</div>
                                                <div style="font-size:.7rem;color:#94a3b8;">{{ $prof->email_professor }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:.82rem;">{{ $prof->especialidade_professor ?? '—' }}</td>
                                    <td>
                                        @php $nivel = strtolower($prof->nivel_professor ?? ''); @endphp
                                        <span class="badge-nivel {{ in_array($nivel,['basico','básico']) ? 'badge-basico' : (in_array($nivel,['intermediario','intermediário']) ? 'badge-intermediario' : (in_array($nivel,['avancado','avançado']) ? 'badge-avancado' : 'badge-fluente')) }}">
                                            {{ $prof->nivel_professor ?? '—' }}
                                        </span>
                                    </td>
                                    <td style="font-size:.82rem;">{{ $prof->experiencia_professor }} {{ $prof->experiencia_professor == 1 ? 'ano' : 'anos' }}</td>
                                    <td><a href="{{ route('admin.professores.edit', $prof->id_professor) }}" class="dash-edit-btn" title="Editar"><i class="fas fa-pen"></i></a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>Nenhum professor cadastrado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-trophy text-warning"></i> Ranking de Notas</h6></div>
                <div class="card-body p-3">
                    @forelse($topAlunos as $i => $item)
                        @php $medalhas = ['🥇','🥈','🥉']; $medalha = $medalhas[$i] ?? '#'.($i+1); $media = round($item->media, 1); $cor = $media >= 9 ? '#22c55e' : ($media >= 7 ? '#3b82f6' : ($media >= 5 ? '#f59e0b' : '#f87171')); @endphp
                        <div class="rank-item">
                            <span class="rank-medal">{{ $medalha }}</span>
                            <div class="rank-avatar">{{ strtoupper(substr($item->aluno->nome_aluno ?? '?', 0, 2)) }}</div>
                            <div class="rank-info">
                                <div class="name">{{ $item->aluno->nome_aluno ?? 'Aluno' }}</div>
                                <div class="acts">{{ $item->total_atividades }} {{ $item->total_atividades == 1 ? 'atividade' : 'atividades' }}</div>
                            </div>
                            <div class="rank-score" style="color:{{ $cor }};">{{ $media }}</div>
                        </div>
                    @empty
                        <div class="today-empty"><i class="fas fa-star opacity-25 fa-2x"></i><span>Nenhuma nota registrada</span></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Agenda & Reagendamentos</div>

    {{-- ══ ROW 7 — REAGENDAMENTOS ══ --}}
    @if($reagendamentosPendentes->count() > 0)
    <div class="row g-3 mb-4">
        <div class="col-12 fade-up">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-calendar-xmark text-danger"></i> Reagendamentos Pendentes</h6>
                    <span class="alert-pill"><i class="fas fa-circle-exclamation"></i> {{ $totalReagendamentosPendentes }}</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @foreach($reagendamentosPendentes as $reag)
                        <div class="col-md-6 col-xl-3">
                            <div class="reag-card">
                                <div class="reag-card-top">
                                    <div class="reag-card-avatar">{{ strtoupper(substr($reag->aluno->nome_aluno ?? '?', 0, 2)) }}</div>
                                    <div class="reag-card-info">
                                        <div class="reag-card-name">{{ $reag->aluno->nome_aluno ?? 'Aluno removido' }}</div>
                                        <div class="reag-card-dates">
                                            <i class="fas fa-arrow-right-arrow-left me-1"></i>
                                            {{ $reag->data_original ? \Carbon\Carbon::parse($reag->data_original)->format('d/m') : '—' }} &rarr;
                                            {{ $reag->data_sugerida ? \Carbon\Carbon::parse($reag->data_sugerida)->format('d/m/Y') : '—' }}
                                        </div>
                                    </div>
                                </div>
                                @if($reag->motivo)
                                    <div class="reag-card-motivo">{{ Str::limit($reag->motivo, 60) }}</div>
                                @endif
                                <a href="{{ route('admin.reagendamentos.index') }}" class="reag-card-btn">
                                    <i class="fas fa-reply me-1"></i> Responder
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row g-3 mb-4">
        <div class="col-12 fade-up">
            <div class="dash-ok-banner">
                <div class="dash-ok-icon"><i class="fas fa-circle-check"></i></div>
                <div>
                    <div class="dash-ok-title">Tudo em dia!</div>
                    <div class="dash-ok-sub">Nenhum reagendamento pendente no momento</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══ ROW 8 — PRÓXIMAS AULAS (CARDS) ══ --}}
    <div class="row g-3 mb-5">
        @forelse($proximasAulas as $i => $aula)
            @php
                $data = \Carbon\Carbon::parse($aula->data_aulas);
                $cores = [
                    ['#6366f1','#818cf8','rgba(99,102,241,.1)'],
                    ['#059669','#10b981','rgba(16,185,129,.1)'],
                    ['#d97706','#f59e0b','rgba(245,158,11,.1)'],
                    ['#0284c7','#0ea5e9','rgba(14,165,233,.1)'],
                    ['#7c3aed','#a78bfa','rgba(167,139,250,.1)'],
                ];
                $cor = $cores[$i % count($cores)];
            @endphp
            <div class="col-md-6 col-xl fade-up">
                <div class="prox-card" style="--accent:{{ $cor[0] }};--accent-light:{{ $cor[1] }};--accent-bg:{{ $cor[2] }};">
                    <div class="prox-card-accent"></div>
                    <div class="prox-card-date">
                        <div class="prox-card-month">{{ $data->translatedFormat('M') }}</div>
                        <div class="prox-card-day">{{ $data->format('d') }}</div>
                    </div>
                    <div class="prox-card-body">
                        <div class="prox-card-title">{{ $aula->titulo_aulas }}</div>
                        <div class="prox-card-meta">
                            <span><i class="fas fa-clock"></i> {{ substr($aula->hora_aulas, 0, 5) }}</span>
                            @if($aula->cursos_aulas)
                                <span><i class="fas fa-language"></i> {{ $aula->cursos_aulas }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="prox-card-arrow"><i class="fas fa-chevron-right"></i></div>
                </div>
            </div>
        @empty
            <div class="col-12 fade-up">
                <div class="dash-ok-banner" style="background:linear-gradient(135deg,#f8fafc,#eef3ff);border-color:#e0e7ff;">
                    <div class="dash-ok-icon" style="background:#eef3ff;color:#6366f1;"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="dash-ok-title">Agenda livre</div>
                        <div class="dash-ok-sub">Nenhuma aula agendada nos próximos dias</div>
                    </div>
                    <a href="{{ route('admin.aulas.create') }}" class="tbl-btn-novo ms-auto">
                        <i class="fas fa-plus"></i> Nova Aula
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
</div>

{{-- ══ SCRIPTS ══ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
Chart.defaults.color = '#64748b';

new Chart(document.getElementById('chartPresenca'), {
    type: 'doughnut',
    data: { labels: ['Presentes','Ausentes'], datasets: [{ data: [{{ $presencaPresentes }}, {{ $presencaAusentes }}], backgroundColor: ['#10b981','#f43f5e'], borderWidth: 3, borderColor: '#fff' }] },
    options: { cutout: '68%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12 } } } }
});

@php $niveisLabels = $alunosPorNivel->keys()->map(fn($n) => $n ?: 'N/A'); $niveisValues = $alunosPorNivel->values(); $niveisColors = ['#6366f1','#0ea5e9','#f59e0b','#10b981','#f43f5e','#a78bfa']; @endphp
new Chart(document.getElementById('chartNiveis'), {
    type: 'doughnut',
    data: { labels: {!! json_encode($niveisLabels) !!}, datasets: [{ data: {!! json_encode($niveisValues) !!}, backgroundColor: {!! json_encode(array_slice($niveisColors, 0, count($niveisValues))) !!}, borderWidth: 3, borderColor: '#fff' }] },
    options: { cutout: '68%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12 } } } }
});

new Chart(document.getElementById('chartNotas'), {
    type: 'bar',
    data: { labels: {!! json_encode(array_keys($notasFaixas)) !!}, datasets: [{ label: 'Atividades', data: {!! json_encode(array_values($notasFaixas)) !!}, backgroundColor: ['#f87171','#fb923c','#facc15','#4ade80'], borderRadius: 8, borderSkipped: false }] },
    options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
});

@php $mesesLabels = $aulasPorMes->keys()->map(function($m) { $ms = ['01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Abr','05'=>'Mai','06'=>'Jun','07'=>'Jul','08'=>'Ago','09'=>'Set','10'=>'Out','11'=>'Nov','12'=>'Dez']; [$a,$n] = explode('-',$m); return ($ms[$n]??$n).'/'.substr($a,2); }); @endphp
new Chart(document.getElementById('chartAulas'), {
    type: 'line',
    data: { labels: {!! json_encode($mesesLabels) !!}, datasets: [{ label: 'Aulas', data: {!! json_encode($aulasPorMes->values()) !!}, borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,.1)', fill: true, tension: 0.4, pointBackgroundColor: '#6366f1', pointRadius: 5, pointHoverRadius: 7 }] },
    options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
});
</script>

<script>
function atualizarHora() {
    const a = new Date(), el = document.getElementById('dash-hora');
    if (el) el.textContent = String(a.getHours()).padStart(2,'0')+':'+String(a.getMinutes()).padStart(2,'0')+':'+String(a.getSeconds()).padStart(2,'0');
}
atualizarHora(); setInterval(atualizarHora, 1000);

document.addEventListener('DOMContentLoaded', function(){
    [['ctr-prof',{{ $totalProfessores }}],['ctr-alunos',{{ $totalAlunos }}],['ctr-aulas',{{ $totalAulas }}],['ctr-mat',{{ $matriculasAtivas }}]].forEach(function([id, target]){
        const el = document.getElementById(id); if (!el) return;
        const s = performance.now();
        (function tick(now){ const p = Math.min((now-s)/900,1), e = 1-Math.pow(1-p,3); el.textContent = Math.round(e*target); if(p<1) requestAnimationFrame(tick); })(s);
    });
});

function atualizarBadge() {
    fetch('{{ route("admin.reagendamento.notificacoes") }}').then(r=>r.json()).then(d=>{ const b=document.getElementById('badgeReagendamentos'); if(!b)return; d.count>0?(b.textContent=d.count,b.classList.remove('d-none')):b.classList.add('d-none'); }).catch(()=>{});
}
atualizarBadge(); setInterval(atualizarBadge, 30000);

(function(){
    const fr=[{en:"Knowledge is power.",pt:"Conhecimento é poder."},{en:"Practice makes perfect.",pt:"A prática leva à perfeição."},{en:"Every expert was once a beginner.",pt:"Todo especialista já foi iniciante."},{en:"Language is the road map of a culture.",pt:"A língua é o mapa de uma cultura."},{en:"To have another language is to possess a second soul.",pt:"Ter outro idioma é possuir uma segunda alma."},{en:"Learning never exhausts the mind.",pt:"O aprendizado nunca esgota a mente."},{en:"The limits of my language are the limits of my world.",pt:"Os limites da minha língua são os limites do meu mundo."},{en:"Fluency comes one word at a time.",pt:"A fluência vem uma palavra de cada vez."}];
    let idx=0;
    const eT=document.getElementById('frase-texto'),eP=document.getElementById('frase-traducao'),eD=document.getElementById('frase-divisor'),eF=document.getElementById('frase-flag');
    function tw(el,text,cb){el.textContent='';let i=0;const t=setInterval(()=>{el.textContent+=text[i++];if(i>=text.length){clearInterval(t);cb&&cb();}},42);}
    function show(){const f=fr[idx%fr.length];eT.style.opacity=eP.style.opacity=eD.style.opacity='0';eF.textContent='🇺🇸';setTimeout(()=>{eT.style.opacity='1';tw(eT,f.en,()=>{setTimeout(()=>{eD.style.opacity='1';eF.textContent='🇧🇷';setTimeout(()=>{eP.style.opacity='1';tw(eP,f.pt,()=>{setTimeout(()=>{eT.style.opacity=eP.style.opacity=eD.style.opacity='0';setTimeout(()=>{idx++;show();},500);},3000);});},200);},700);});},300);}
    document.addEventListener('DOMContentLoaded', show);
})();
</script>

@endsection
