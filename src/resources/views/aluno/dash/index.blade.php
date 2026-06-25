@extends('aluno.layout.aluno')

@section('content')

{{-- HEADER --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Meu Painel</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
<div class="container-fluid aluno-dashboard">

    {{-- Alertas --}}
    @if (session('success'))
        <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;border:none;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ══ GREETING ══ --}}
    <div class="row mb-4 fade-up">
        <div class="col-12">
            <div class="dash-greeting-card shadow">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="dash-hora" id="dash-hora"></p>
                        <h2 class="dash-nome">
                            @php $h = now()->format('H'); $saudacao = $h < 12 ? 'Bom dia' : ($h < 18 ? 'Boa tarde' : 'Boa noite'); @endphp
                            {{ $saudacao }}, {{ explode(' ', $aluno->nome_aluno)[0] }}! 👋
                        </h2>
                        <p class="dash-sub">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                        <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">
                            <div class="dash-badge-prof"><span class="dash-live-dot me-1"></span> Online agora</div>
                            <div class="dash-badge-prof"><i class="fas fa-graduation-cap"></i> Aluno matriculado</div>
                            @if($aluno->nivel_aluno)
                                <div class="dash-badge-prof" style="background:rgba(99,102,241,.2);border-color:rgba(99,102,241,.3);color:#c7d2fe;">
                                    <i class="fas fa-star"></i> {{ $aluno->nivel_aluno }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 d-none d-md-flex justify-content-end">
                        @if ($aluno->foto_aluno)
                            <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                class="dash-photo" alt="{{ $aluno->nome_aluno }}">
                        @else
                            <div class="dash-photo d-flex align-items-center justify-content-center"
                                style="background:rgba(255,255,255,.1);font-size:2.5rem;font-weight:800;color:#fff;">
                                {{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- METRIC CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-blue shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-book-open-reader"></i></div>
                <div class="mc-val" id="ctr-aulas-aluno" data-target="{{ $totalAulas ?? 0 }}">0</div>
                <p class="mc-lbl">Minhas Aulas</p>
                <div class="mc-trend"><i class="fas fa-calendar-day me-1"></i> dispon&iacute;veis</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-green shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-language"></i></div>
                <div class="mc-val mc-val-text">@if($aluno->curso_aluno) {{ $aluno->curso_aluno }} @else &mdash; @endif</div>
                <p class="mc-lbl">Meu Curso</p>
                <div class="mc-trend"><i class="fas fa-circle-check me-1"></i> em andamento</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-amber shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-layer-group"></i></div>
                <div class="mc-val mc-val-text">@if($aluno->nivel_aluno) {{ $aluno->nivel_aluno }} @else &mdash; @endif</div>
                <p class="mc-lbl">Meu N&iacute;vel</p>
                <div class="mc-trend"><i class="fas fa-rocket me-1"></i> evolu&ccedil;&atilde;o ativa</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-rose shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-star-half-stroke"></i></div>
                <div class="mc-val" id="ctr-feedbacks-aluno" data-target="{{ $feedbacks->count() }}">0</div>
                <p class="mc-lbl">Feedbacks</p>
                <div class="mc-trend"><i class="fas fa-pen me-1"></i> enviados</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc shadow-sm h-100" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);">
                <div class="mc-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="mc-val" id="ctr-reag-aluno" data-target="{{ $reagendamentos->count() }}">0</div>
                <p class="mc-lbl">Reagendamentos</p>
                <div class="mc-trend"><i class="fas fa-clock me-1"></i><span id="mc-hora-live"></span></div>
            </div>
        </div>
    </div>
    <div class="dash-section-title fade-up">Vis&atilde;o de Hoje & Estudos</div>

    @php
        $proximasAulasAluno = $aulas
            ->filter(fn($aula) => $aula->data_aulas && $aula->data_aulas >= now()->toDateString())
            ->sortBy(fn($aula) => ($aula->data_aulas ?? '') . ' ' . ($aula->hora_aulas ?? ''))
            ->take(5);
        $proxAula = $proximasAulasAluno->first();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6>
                        <span style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;animation:pulse 2s infinite;"></span>
                        Pr&oacute;xima Aula
                    </h6>
                    @if($proxAula)
                        <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Ao vivo</span>
                    @endif
                </div>
                <div class="card-body p-3">
                    @if($proxAula)
                        @php
                            $proxData = \Carbon\Carbon::parse($proxAula->data_aulas);
                            $proxHora = $proxAula->hora_aulas ? \Carbon\Carbon::parse($proxAula->hora_aulas)->format('H:i') : '--:--';
                        @endphp
                        <div class="prox-card aluno-next-card" style="--accent:#6366f1;--accent-light:#818cf8;--accent-bg:rgba(99,102,241,.1);">
                            <div class="prox-card-accent"></div>
                            <div class="prox-card-date">
                                <div class="prox-card-month">{{ $proxData->translatedFormat('M') }}</div>
                                <div class="prox-card-day">{{ $proxData->format('d') }}</div>
                            </div>
                            <div class="prox-card-body">
                                <div class="prox-card-title">{{ $proxAula->titulo_aulas }}</div>
                                <div class="prox-card-meta">
                                    <span><i class="fas fa-clock"></i> {{ $proxHora }}</span>
                                    @if($proxAula->cursos_aulas)
                                        <span><i class="fas fa-language"></i> {{ $proxAula->cursos_aulas }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="prox-card-arrow"><i class="fas fa-chevron-right"></i></div>
                        </div>
                    @else
                        <div class="today-empty">
                            <i class="fas fa-calendar-check fa-2x opacity-25"></i>
                            <span>Nenhuma aula agendada</span>
                        </div>
                    @endif

                    <div class="student-platform-row">
                        @if($proxAula && $proxAula->link_teams)
                            <a href="{{ $proxAula->link_teams }}" target="_blank" class="aluno-plat-btn" style="--plat-color:#6366f1;">
                                <i class="fab fa-microsoft"></i> Teams
                            </a>
                        @else
                            <a href="https://teams.microsoft.com/" target="_blank" class="aluno-plat-btn" style="--plat-color:#6366f1;">
                                <i class="fab fa-microsoft"></i> Teams
                            </a>
                        @endif
                        <a href="https://meet.google.com/" target="_blank" class="aluno-plat-btn" style="--plat-color:#10b981;">
                            <i class="fab fa-google"></i> Meet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6><i class="fas fa-calendar-days text-primary"></i> Pr&oacute;ximas Aulas</h6>
                    <a href="{{ route('aluno.aulas.index') }}">Ver todas <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-3">
                    @forelse($proximasAulasAluno as $aula)
                        @php
                            $aulaData = \Carbon\Carbon::parse($aula->data_aulas);
                            $aulaHora = $aula->hora_aulas ? \Carbon\Carbon::parse($aula->hora_aulas)->format('H:i') : '--:--';
                        @endphp
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <div class="hour">{{ $aulaHora }}</div>
                            </div>
                            <div class="schedule-dot"></div>
                            <div class="schedule-info flex-grow-1 overflow-hidden">
                                <div class="title">{{ Str::limit($aula->titulo_aulas, 30) }}</div>
                                <div class="sub">
                                    {{ $aulaData->format('d/m/Y') }}
                                    @if($aula->cursos_aulas) &nbsp;&middot;&nbsp; {{ $aula->cursos_aulas }} @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="today-empty">
                            <i class="fas fa-mug-hot fa-2x opacity-25"></i>
                            <span>Sua agenda est&aacute; livre</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6><i class="fas fa-lightbulb text-warning"></i> Dicas de Estudo</h6>
                </div>
                <div class="card-body p-3">
                    <div class="study-tip-item">
                        <div class="tbl-icon-wrap green"><i class="fas fa-headphones"></i></div>
                        <div class="study-tip-copy"><strong>Pratique a escuta</strong><span>Ou&ccedil;a conte&uacute;dos no idioma todos os dias.</span></div>
                    </div>
                    <div class="study-tip-item">
                        <div class="tbl-icon-wrap"><i class="fas fa-pen-fancy"></i></div>
                        <div class="study-tip-copy"><strong>Escreva diariamente</strong><span>Frases curtas ajudam a fixar vocabul&aacute;rio.</span></div>
                    </div>
                    <div class="study-tip-item">
                        <div class="tbl-icon-wrap amber"><i class="fas fa-comments"></i></div>
                        <div class="study-tip-copy"><strong>Fale sem medo</strong><span>Errar faz parte do aprendizado.</span></div>
                    </div>
                    <div class="study-tip-item">
                        <div class="tbl-icon-wrap rose"><i class="fas fa-repeat"></i></div>
                        <div class="study-tip-copy"><strong>Revise sempre</strong><span>Retome o conte&uacute;do das aulas anteriores.</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- REAGENDAMENTOS --}}
    <div class="dash-section-title fade-up">Agenda & Reagendamentos</div>

    @if ($reagendamentos->isNotEmpty())
        <div class="row g-3 mb-4">
            <div class="col-12 fade-up">
                <div class="d-card">
                    <div class="d-card-header">
                        <h6><i class="fas fa-calendar-xmark text-danger"></i> Minhas Solicita&ccedil;&otilde;es</h6>
                        <a href="{{ route('aluno.reagendamentos.index') }}">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table recent-table mb-0">
                            <thead>
                                <tr>
                                    <th>Aula</th>
                                    <th>Motivo</th>
                                    <th>Nova Data</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reagendamentos as $r)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="prof-avatar-placeholder"><i class="fas fa-calendar-days"></i></div>
                                                <div class="overflow-hidden">
                                                    <div style="font-weight:600;font-size:.875rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                        {{ $r->aula?->titulo_aulas ?? 'Aula nao encontrada' }}
                                                    </div>
                                                    <div style="font-size:.7rem;color:#94a3b8;">
                                                        @if ($r->aula?->data_aulas)
                                                            {{ \Carbon\Carbon::parse($r->aula->data_aulas)->format('d/m/Y') }}
                                                            @if($r->aula->hora_aulas) &agrave;s {{ \Carbon\Carbon::parse($r->aula->hora_aulas)->format('H:i') }} @endif
                                                        @else
                                                            Sem data
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0" style="font-size:.82rem;color:#64748b;max-width:340px;line-height:1.4;">
                                                {{ Str::limit($r->motivo, 110) }}
                                            </p>
                                        </td>
                                        <td style="font-size:.82rem;color:#64748b;">
                                            @if ($r->data_nova)
                                                {{ \Carbon\Carbon::parse($r->data_nova)->format('d/m/Y') }} &agrave;s {{ \Carbon\Carbon::parse($r->data_nova)->format('H:i') }}
                                            @else
                                                A confirmar
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($r->status === 'confirmado')
                                                <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Confirmado</span>
                                            @elseif ($r->status === 'recusado')
                                                <span class="tbl-status tbl-status-cancelado"><span class="tbl-status-dot"></span> Recusado</span>
                                            @else
                                                <span class="tbl-status tbl-status-pendente"><span class="tbl-status-dot"></span> Pendente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row g-3 mb-4">
            <div class="col-12 fade-up">
                <div class="dash-ok-banner">
                    <div class="dash-ok-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="dash-ok-title">Tudo em dia!</div>
                        <div class="dash-ok-sub">Nenhum reagendamento pendente no momento</div>
                    </div>
                    <button type="button" class="tbl-btn-novo ms-auto" data-bs-toggle="modal" data-bs-target="#modalReagendamento">
                        <i class="fas fa-plus"></i> Solicitar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- FEEDBACK DOS CURSOS --}}
    @if($matriculas->isNotEmpty())
        <div class="dash-section-title fade-up">Feedback & Professores</div>
        <div class="row g-3 mb-4">
            <div class="col-12 fade-up">
                <div class="d-card">
                    <div class="d-card-header">
                        <h6><i class="fas fa-star-half-stroke text-warning"></i> Avalie seus Professores</h6>
                        <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> {{ $feedbacks->count() }} enviado{{ $feedbacks->count() === 1 ? '' : 's' }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table recent-table mb-0 student-feedback-table">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Professor</th>
                                    <th class="text-center">Status</th>
                                    <th>Avalia&ccedil;&atilde;o</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matriculas as $mat)
                                    @php $fb = $feedbacks[$mat->id_curso] ?? null; @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="tbl-icon-wrap"><i class="fas fa-language"></i></div>
                                                <div>
                                                    <div style="font-weight:600;font-size:.875rem;">{{ $mat->curso->nome_curso ?? $aluno->curso_aluno }}</div>
                                                    <div style="font-size:.7rem;color:#94a3b8;">Curso matriculado</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="font-size:.82rem;">{{ $mat->professor_nome }}</td>
                                        <td class="text-center">
                                            @if($fb)
                                                <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Enviado</span>
                                            @else
                                                <span class="tbl-status tbl-status-pendente"><span class="tbl-status-dot"></span> Pendente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fb)
                                                <div class="student-feedback-result student-feedback-result-inline">
                                                    <div class="fb-stars-display">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fa{{ $i <= $fb->nota ? 's' : 'r' }} fa-star" style="color:{{ $i <= $fb->nota ? '#f59e0b' : '#e2e8f0' }};"></i>
                                                        @endfor
                                                    </div>
                                                    @if($fb->comentario)
                                                        <p class="student-feedback-comment">"{{ Str::limit($fb->comentario, 80) }}"</p>
                                                    @endif
                                                    <button type="button" class="dash-edit-inline"
                                                        onclick="this.closest('td').querySelector('.fb-form').style.display='block'; this.style.display='none';">
                                                        <i class="fas fa-pen me-1"></i>Editar
                                                    </button>
                                                </div>
                                            @endif

                                            <form action="{{ route('aluno.feedback.store') }}" method="POST" class="fb-form student-feedback-table-form" style="{{ $fb ? 'display:none;' : '' }}">
                                                @csrf
                                                <input type="hidden" name="id_curso" value="{{ $mat->id_curso }}">
                                                <input type="hidden" name="id_professor" value="{{ $mat->professor_id }}">

                                                <div class="student-stars-field student-stars-field-inline">
                                                    <div class="fb-stars" data-curso="{{ $mat->id_curso }}">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <label class="student-star-label">
                                                                <input type="radio" name="nota" value="{{ $i }}" style="display:none;" {{ ($fb && $fb->nota == $i) ? 'checked' : '' }}>
                                                                <i class="far fa-star fb-star" data-value="{{ $i }}" style="color:#e2e8f0;transition:color .2s;"></i>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                </div>

                                                <div class="student-feedback-table-controls">
                                                    <textarea name="comentario" rows="2" class="form-control student-form-control"
                                                        placeholder="Coment&aacute;rio opcional" maxlength="500">{{ $fb?->comentario }}</textarea>
                                                    <button type="submit" class="tbl-btn-novo justify-content-center student-submit-btn">
                                                        <i class="fas fa-paper-plane"></i> @if($fb) Atualizar @else Enviar @endif
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="dash-section-title fade-up">A&ccedil;&otilde;es R&aacute;pidas & Perfil</div>

    {{-- ACOES RAPIDAS + PERFIL --}}
    <div class="row g-3 mb-5">
        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-bolt text-warning"></i> A&ccedil;&otilde;es R&aacute;pidas</h6></div>
                <div class="card-body p-3">
                    <a href="{{ route('aluno.aulas.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#ecfdf5;color:#059669;"><i class="fas fa-book-open"></i></div>
                        <div><div class="qa-label">Minhas Aulas</div><p class="qa-desc">Ver aulas dispon&iacute;veis</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('aluno.progresso.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;"><i class="fas fa-chart-line"></i></div>
                        <div><div class="qa-label">Meu Progresso</div><p class="qa-desc">Acompanhar evolu&ccedil;&atilde;o</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('aluno.atividades.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-clipboard-list"></i></div>
                        <div><div class="qa-label">Atividades</div><p class="qa-desc">Ver e enviar atividades</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#modalReagendamento">
                        <div class="qa-icon" style="background:#fff1f2;color:#e11d48;"><i class="fas fa-calendar-alt"></i></div>
                        <div><div class="qa-label">Reagendar Aula</div><p class="qa-desc">Solicitar novo hor&aacute;rio</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-user text-primary"></i> Meu Perfil</h6></div>
                <div class="card-body p-3">
                    <div class="student-profile-head">
                        @if ($aluno->foto_aluno)
                            <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}" class="prof-avatar student-profile-avatar" alt="">
                        @else
                            <div class="prof-avatar-placeholder student-profile-avatar">{{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}</div>
                        @endif
                        <div>
                            <div class="student-profile-name">{{ $aluno->nome_aluno }}</div>
                            <div class="student-profile-email">{{ $aluno->email_aluno }}</div>
                        </div>
                    </div>

                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-phone me-2" style="color:#6366f1;"></i>Telefone</span><span class="stat-mini-value">@if($aluno->telefone_aluno) {{ $aluno->telefone_aluno }} @else &mdash; @endif</span></div>
                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-book me-2" style="color:#10b981;"></i>Curso</span><span class="stat-mini-value">@if($aluno->curso_aluno) {{ $aluno->curso_aluno }} @else &mdash; @endif</span></div>
                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-layer-group me-2" style="color:#f59e0b;"></i>N&iacute;vel</span><span class="stat-mini-value">@if($aluno->nivel_aluno) {{ $aluno->nivel_aluno }} @else &mdash; @endif</span></div>
                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-cake-candles me-2" style="color:#e11d48;"></i>Nascimento</span><span class="stat-mini-value">@if($aluno->data_nasc_aluno) {{ $aluno->data_nasc_aluno }} @else &mdash; @endif</span></div>

                    <a href="{{ route('aluno.perfil') }}" class="tbl-btn-novo w-100 justify-content-center mt-3 student-submit-btn">
                        <i class="fas fa-pen-to-square"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card mb-3" id="card-frases-aluno">
                <div class="d-card-header student-language-header">
                    <h6><i class="fas fa-language" style="color:#6366f1;"></i> Frase do Momento</h6>
                </div>
                <div class="card-body p-4 text-center student-phrase-body">
                    <div id="al-frase-flag" style="font-size:1.4rem;margin-bottom:6px;">US</div>
                    <p id="al-frase-texto" class="student-phrase-text"></p>
                    <div id="al-frase-div" class="student-phrase-divider"></div>
                    <p id="al-frase-trad" class="student-phrase-translation"></p>
                </div>
            </div>

            <div class="dash-ok-banner student-rule-banner">
                <div class="dash-ok-icon student-rule-icon"><i class="fas fa-circle-info"></i></div>
                <div>
                    <div class="dash-ok-title student-rule-title">Regra de Reagendamento</div>
                    <div class="dash-ok-sub student-rule-sub">Envie a solicita&ccedil;&atilde;o com no m&iacute;nimo <strong>24h &uacute;teis de anteced&ecirc;ncia</strong>.</div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

{{-- MODAL DE REAGENDAMENTO --}}
<div class="modal fade" id="modalReagendamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-radius:16px 16px 0 0;padding:1.5rem 1.5rem 1rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-calendar-alt text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" style="color:#92400e;">Solicitar Reagendamento</h5>
                        <small style="color:#b45309;">Envie sua solicitação ao professor</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('aluno.reagendamento.solicitar') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-book-open me-1 text-warning"></i> Aula
                        </label>
                        <select name="aula_id" class="form-select @error('aula_id') is-invalid @enderror" style="border-radius:10px;">
                            <option value="">Selecione a aula...</option>
                            @foreach ($aulas as $aula)
                                <option value="{{ $aula->id_aulas }}" {{ old('aula_id') == $aula->id_aulas ? 'selected' : '' }}>
                                    {{ $aula->titulo_aulas }} — {{ $aula->data_aulas ? \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') : 'Sem data' }}
                                </option>
                            @endforeach
                        </select>
                        @error('aula_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-comment-alt me-1 text-warning"></i> Motivo
                        </label>
                        <textarea name="motivo" id="motivo" rows="3" class="form-control @error('motivo') is-invalid @enderror"
                            style="border-radius:10px;resize:none;" placeholder="Explique o motivo..." maxlength="500">{{ old('motivo') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            @error('motivo') <div class="invalid-feedback d-block">{{ $message }}</div> @else <small class="text-muted">Mínimo 10 caracteres</small> @enderror
                            <small class="text-muted" id="motivoCount">0/500</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="del-btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="tbl-btn-novo" style="border-radius:10px;background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .aluno-plat-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .6rem;
        border-radius: 10px;
        border: 1.5px solid color-mix(in srgb, var(--plat-color) 30%, transparent);
        background: color-mix(in srgb, var(--plat-color) 6%, #fff);
        color: var(--plat-color);
        font-weight: 600;
        font-size: .82rem;
        text-decoration: none;
        transition: all .2s;
    }
    .aluno-plat-btn:hover {
        background: var(--plat-color);
        color: #fff;
        border-color: var(--plat-color);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--plat-color) 30%, transparent);
        transform: translateY(-2px);
    }
    .aluno-dashboard .aluno-next-card {
        margin-bottom: 1rem;
        padding: 1rem;
    }
    .student-platform-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .6rem;
        margin-top: 1rem;
    }
    .study-tip-item {
        display: flex;
        align-items: flex-start;
        gap: .9rem;
        padding: .75rem 0;
        border-bottom: 1px solid var(--slate-100);
    }
    .study-tip-item:last-child { border-bottom: none; }
    .study-tip-copy strong {
        display: block;
        font-size: .86rem;
        color: var(--slate-800);
        line-height: 1.25;
    }
    .study-tip-copy span {
        display: block;
        margin-top: .15rem;
        font-size: .74rem;
        color: var(--slate-400);
        line-height: 1.35;
    }
    .student-status-card {
        background: #fff;
        border: 1.5px solid var(--slate-100);
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
        padding: 1rem;
        position: relative;
        overflow: hidden;
        transition: all .25s;
    }
    .student-status-card::before {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: var(--accent);
    }
    .student-status-card:hover {
        border-color: color-mix(in srgb, var(--accent) 35%, var(--slate-100));
        box-shadow: 0 8px 24px var(--accent-bg);
        transform: translateY(-3px);
    }
    .student-status-top {
        display: flex;
        align-items: flex-start;
        gap: .8rem;
    }
    .student-status-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: var(--accent-bg);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.05rem;
    }
    .student-status-main { flex: 1; min-width: 0; }
    .student-status-title {
        font-weight: 700;
        font-size: .9rem;
        color: var(--slate-800);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .student-status-meta {
        margin-top: .2rem;
        font-size: .73rem;
        color: var(--slate-400);
        line-height: 1.4;
    }
    .student-note-box {
        margin-top: .85rem;
        padding: .65rem .8rem;
        border-radius: 10px;
        background: var(--slate-50);
        border-left: 3px solid var(--accent);
        color: var(--slate-600);
        font-size: .76rem;
        line-height: 1.45;
    }
    .student-feedback-card .tbl-status { width: auto; }
    .student-feedback-head {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--slate-100);
    }
    .student-feedback-title-wrap { min-width: 0; }
    .student-feedback-title {
        font-weight: 700;
        font-size: .92rem;
        color: var(--slate-800);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .student-feedback-sub {
        margin-top: .15rem;
        font-size: .74rem;
        color: var(--slate-400);
    }
    .student-feedback-result {
        text-align: center;
        padding: .3rem 0 1rem;
    }
    .student-feedback-result .fb-stars-display {
        font-size: 1.45rem;
        margin-bottom: .45rem;
    }
    .student-feedback-comment {
        color: var(--slate-600);
        font-size: .8rem;
        font-style: italic;
        margin: 0 0 .6rem;
        line-height: 1.45;
    }
    .dash-edit-inline {
        border: 1.5px solid var(--slate-200);
        background: #fff;
        color: var(--slate-600);
        border-radius: 8px;
        padding: .38rem .75rem;
        font-size: .74rem;
        font-weight: 600;
        transition: all .2s;
    }
    .dash-edit-inline:hover {
        color: var(--indigo);
        border-color: var(--indigo);
        background: #f5f3ff;
        transform: translateY(-1px);
    }
    .student-stars-field {
        text-align: center;
        margin-bottom: .85rem;
    }
    .student-stars-field label:first-child {
        display: block;
        color: var(--slate-600);
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: .45rem;
    }
    .student-star-label {
        cursor: pointer;
        font-size: 1.55rem;
        margin: 0 2px;
        transition: transform .15s;
    }
    .student-star-label:hover { transform: translateY(-2px) scale(1.08); }
    .student-form-control {
        border-radius: 10px;
        resize: none;
        font-size: .82rem;
        border-color: var(--slate-200);
    }
    .student-form-control:focus {
        border-color: var(--indigo);
        box-shadow: 0 0 0 .2rem rgba(99,102,241,.12);
    }
    .student-submit-btn { border-radius: 10px; }
    .student-profile-head {
        display: flex;
        align-items: center;
        gap: .9rem;
        padding: .9rem;
        margin-bottom: .45rem;
        border-radius: 14px;
        background: linear-gradient(135deg,#f8faff,#eef3ff);
        border: 1px solid #e0e7ff;
    }
    .student-profile-avatar {
        width: 58px !important;
        height: 58px !important;
        border-radius: 14px !important;
        font-size: 1.05rem;
    }
    .student-profile-name {
        font-size: .95rem;
        color: var(--slate-800);
        font-weight: 800;
        line-height: 1.2;
    }
    .student-profile-email {
        margin-top: .2rem;
        color: var(--slate-400);
        font-size: .74rem;
        word-break: break-word;
    }
    .student-language-header { background: linear-gradient(135deg,#f8faff,#eef3ff); }
    .student-phrase-body {
        min-height: 154px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .student-phrase-text {
        font-size: .92rem;
        font-weight: 700;
        color: var(--slate-800);
        min-height: 40px;
        margin: 0 0 8px;
        line-height: 1.45;
        transition: opacity .4s;
    }
    .student-phrase-divider {
        width: 36px;
        height: 2px;
        background: linear-gradient(90deg,#6366f1,#a78bfa);
        border-radius: 99px;
        margin: 0 auto 8px;
        opacity: 0;
        transition: opacity .4s;
    }
    .student-phrase-translation {
        font-size: .8rem;
        color: var(--indigo);
        font-style: italic;
        min-height: 32px;
        margin: 0;
        opacity: 0;
        transition: opacity .4s;
    }
    .student-rule-banner {
        background: linear-gradient(135deg,#fffbeb,#fef3c7);
        border-color: #fde68a;
        align-items: flex-start;
    }
    .student-rule-icon {
        background: #fde68a;
        color: #b45309;
    }
    .student-rule-title { color: #92400e; }
    .student-rule-sub {
        color: #b45309;
        opacity: 1;
        line-height: 1.45;
    }
    @media (max-width: 575.98px) {
        .student-status-top { flex-wrap: wrap; }
        .student-status-top .tbl-status { margin-left: 50px; }
        .student-platform-row { grid-template-columns: 1fr; }
    }
    .aluno-dashboard .mc-val-text {
        font-size: 1.35rem;
        line-height: 1.1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    .student-feedback-table .tbl-status { width: auto; }
    .student-feedback-result-inline {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: .5rem .75rem;
        justify-content: flex-start;
        padding: 0 0 .55rem;
        text-align: left;
    }
    .student-feedback-result-inline .fb-stars-display {
        font-size: 1rem;
        margin: 0;
    }
    .student-feedback-result-inline .student-feedback-comment {
        flex-basis: 100%;
        margin: 0;
    }
    .student-feedback-table-form {
        min-width: 340px;
    }
    .student-stars-field-inline {
        margin-bottom: .45rem;
        text-align: left;
    }
    .student-stars-field-inline .student-star-label {
        font-size: 1.1rem;
    }
    .student-feedback-table-controls {
        display: grid;
        grid-template-columns: minmax(180px, 1fr) auto;
        gap: .55rem;
        align-items: stretch;
    }
    .student-feedback-table-controls .tbl-btn-novo {
        min-width: 104px;
        white-space: nowrap;
    }
</style>

<script>
    function atualizarHora() {
        var a = new Date();
        var el = document.getElementById('dash-hora');
        var el2 = document.getElementById('mc-hora-live');
        var t = String(a.getHours()).padStart(2,'0')+':'+String(a.getMinutes()).padStart(2,'0')+':'+String(a.getSeconds()).padStart(2,'0');
        if (el) el.textContent = t;
        if (el2) el2.textContent = t;
    }
    atualizarHora(); setInterval(atualizarHora, 1000);

    document.addEventListener('DOMContentLoaded', function(){
        ['ctr-aulas-aluno','ctr-feedbacks-aluno','ctr-reag-aluno'].forEach(function(id){
            var el = document.getElementById(id); if (!el) return;
            var target = parseInt(el.dataset.target || '0', 10);
            var start = performance.now();
            function tick(now){
                var p = Math.min((now - start) / 900, 1);
                var e = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(e * target);
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.fb-stars').forEach(function(container) {
            var stars = container.querySelectorAll('.fb-star');
            var radios = container.querySelectorAll('input[type=radio]');

            radios.forEach(function(r) {
                if (r.checked) {
                    var v = parseInt(r.value);
                    stars.forEach(function(s) {
                        var sv = parseInt(s.dataset.value);
                        s.className = sv <= v ? 'fas fa-star fb-star' : 'far fa-star fb-star';
                        s.style.color = sv <= v ? '#f59e0b' : '#e2e8f0';
                    });
                }
            });

            stars.forEach(function(star) {
                star.addEventListener('mouseenter', function() {
                    var val = parseInt(this.dataset.value);
                    stars.forEach(function(s) {
                        var sv = parseInt(s.dataset.value);
                        s.className = sv <= val ? 'fas fa-star fb-star' : 'far fa-star fb-star';
                        s.style.color = sv <= val ? '#f59e0b' : '#e2e8f0';
                    });
                });

                star.addEventListener('click', function() {
                    var val = parseInt(this.dataset.value);
                    container.querySelectorAll('input[value="'+val+'"]')[0].checked = true;
                });
            });

            container.addEventListener('mouseleave', function() {
                var checked = container.querySelector('input[type=radio]:checked');
                var val = checked ? parseInt(checked.value) : 0;
                stars.forEach(function(s) {
                    var sv = parseInt(s.dataset.value);
                    s.className = sv <= val ? 'fas fa-star fb-star' : 'far fa-star fb-star';
                    s.style.color = sv <= val ? '#f59e0b' : '#e2e8f0';
                });
            });
        });

        var motivo = document.getElementById('motivo');
        var count = document.getElementById('motivoCount');
        if (motivo && count) {
            var update = function() { count.textContent = motivo.value.length + '/500'; };
            motivo.addEventListener('input', update);
            update();
        }

        @if ($errors->any())
            new bootstrap.Modal(document.getElementById('modalReagendamento')).show();
        @endif
    });

    (function(){
        var fr=[
            {en:"Knowledge is power.",pt:"Conhecimento é poder."},
            {en:"Practice makes perfect.",pt:"A prática leva à perfeição."},
            {en:"Every expert was once a beginner.",pt:"Todo especialista já foi iniciante."},
            {en:"Language is the road map of a culture.",pt:"A língua é o mapa de uma cultura."},
            {en:"Fluency comes one word at a time.",pt:"A fluência vem uma palavra de cada vez."},
            {en:"Learning never exhausts the mind.",pt:"O aprendizado nunca esgota a mente."},
            {en:"The limits of my language are the limits of my world.",pt:"Os limites da minha língua são os limites do meu mundo."},
            {en:"Invest in yourself — it pays the best interest.",pt:"Invista em si mesmo — é o melhor retorno."}
        ];
        var idx=0;
        var eT=document.getElementById('al-frase-texto'),eP=document.getElementById('al-frase-trad'),eD=document.getElementById('al-frase-div'),eF=document.getElementById('al-frase-flag');
        function tw(el,text,cb){el.textContent='';var i=0;var t=setInterval(function(){el.textContent+=text[i++];if(i>=text.length){clearInterval(t);if(cb)cb();}},42);}
        function show(){var f=fr[idx%fr.length];eT.style.opacity=eP.style.opacity=eD.style.opacity='0';eF.textContent='🇺🇸';
            setTimeout(function(){eT.style.opacity='1';tw(eT,f.en,function(){setTimeout(function(){eD.style.opacity='1';eF.textContent='🇧🇷';setTimeout(function(){eP.style.opacity='1';tw(eP,f.pt,function(){setTimeout(function(){eT.style.opacity=eP.style.opacity=eD.style.opacity='0';setTimeout(function(){idx++;show();},500);},3000);});},200);},700);});},300);
        }
        document.addEventListener('DOMContentLoaded',show);
    })();
</script>
@endpush

@endsection
