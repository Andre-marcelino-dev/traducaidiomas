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
<div class="container-fluid">

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

    {{-- ══ METRIC CARDS ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3 fade-up">
            <div class="mc mc-blue shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-book-open-reader"></i></div>
                <div class="mc-val">{{ $totalAulas ?? 0 }}</div>
                <p class="mc-lbl">Minhas Aulas</p>
                <div class="mc-trend"><i class="fas fa-chalkboard me-1"></i> disponíveis</div>
            </div>
        </div>
        <div class="col-6 col-xl-3 fade-up">
            <div class="mc mc-green shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-language"></i></div>
                <div class="mc-val" style="font-size:1.4rem;">{{ $aluno->curso_aluno ?? '—' }}</div>
                <p class="mc-lbl">Meu Curso</p>
                <div class="mc-trend"><i class="fas fa-signal me-1"></i> em andamento</div>
            </div>
        </div>
        <div class="col-6 col-xl-3 fade-up">
            <div class="mc mc-amber shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-layer-group"></i></div>
                <div class="mc-val" style="font-size:1.4rem;">{{ $aluno->nivel_aluno ?? '—' }}</div>
                <p class="mc-lbl">Meu Nível</p>
                <div class="mc-trend"><i class="fas fa-rocket me-1"></i> evolua!</div>
            </div>
        </div>
        <div class="col-6 col-xl-3 fade-up">
            <div class="mc shadow-sm h-100" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);">
                <div class="mc-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="mc-val" style="font-size:1.4rem;">{{ now()->format('d/m') }}</div>
                <p class="mc-lbl">Hoje</p>
                <div class="mc-trend">
                    <i class="fas fa-clock me-1"></i>
                    <span id="mc-hora-live"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Aulas & Plataformas</div>

    {{-- ══ PRÓXIMA AULA + AULAS RECENTES ══ --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-5 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6><i class="fas fa-video text-danger"></i> Próxima Aula Ao Vivo</h6>
                </div>
                <div class="card-body p-3">
                    @php
                        $proxAula = $aulas->where('data_aulas', '>=', now()->toDateString())->sortBy('data_aulas')->sortBy('hora_aulas')->first();
                    @endphp
                    @if($proxAula)
                        <div style="background:linear-gradient(135deg,#f8fafc,#eef3ff);border-radius:12px;padding:1.2rem;border-left:4px solid #6366f1;margin-bottom:1rem;">
                            <div style="font-weight:700;font-size:.95rem;color:#1e293b;margin-bottom:.3rem;">{{ $proxAula->titulo_aulas }}</div>
                            <div style="font-size:.78rem;color:#64748b;">
                                <i class="fas fa-calendar-alt me-1" style="color:#6366f1;"></i>
                                {{ \Carbon\Carbon::parse($proxAula->data_aulas)->format('d/m/Y') }}
                                <i class="fas fa-clock ms-2 me-1" style="color:#6366f1;"></i>
                                {{ \Carbon\Carbon::parse($proxAula->hora_aulas)->format('H:i') }}
                            </div>
                            @if($proxAula->cursos_aulas)
                                <span class="tbl-badge mt-2" style="display:inline-flex;"><i class="fas fa-language me-1"></i>{{ $proxAula->cursos_aulas }}</span>
                            @endif
                        </div>
                    @else
                        <div style="background:#f8fafc;border-radius:12px;padding:1.2rem;text-align:center;color:#94a3b8;">
                            <i class="fas fa-calendar-check fa-2x mb-2 d-block" style="opacity:.3;"></i>
                            Nenhuma aula agendada
                        </div>
                    @endif

                    <p style="font-size:.78rem;color:#94a3b8;margin-bottom:.75rem;">Acesse a plataforma combinada com seu professor:</p>
                    <div class="row g-2">
                        <div class="col-6">
                            @if($proxAula && $proxAula->link_teams)
                                <a href="{{ $proxAula->link_teams }}" target="_blank" class="aluno-plat-btn" style="--plat-color:#6366f1;">
                                    <i class="fab fa-microsoft"></i> Teams
                                </a>
                            @else
                                <a href="https://teams.microsoft.com/" target="_blank" class="aluno-plat-btn" style="--plat-color:#6366f1;">
                                    <i class="fab fa-microsoft"></i> Teams
                                </a>
                            @endif
                        </div>
                        <div class="col-6">
                            <a href="https://meet.google.com/" target="_blank" class="aluno-plat-btn" style="--plat-color:#10b981;">
                                <i class="fab fa-google"></i> Meet
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6><i class="fas fa-lightbulb text-warning"></i> Dicas de Estudo</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="tbl-icon-wrap green" style="min-width:38px;"><i class="fas fa-headphones"></i></div>
                        <div>
                            <div style="font-weight:600;font-size:.88rem;color:#1e293b;">Pratique a escuta</div>
                            <div style="font-size:.75rem;color:#94a3b8;">Ouça podcasts ou músicas no idioma que está estudando todos os dias.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="tbl-icon-wrap" style="min-width:38px;"><i class="fas fa-pen-fancy"></i></div>
                        <div>
                            <div style="font-weight:600;font-size:.88rem;color:#1e293b;">Escreva diariamente</div>
                            <div style="font-size:.75rem;color:#94a3b8;">Mesmo frases curtas ajudam a fixar vocabulário e gramática.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="tbl-icon-wrap amber" style="min-width:38px;"><i class="fas fa-comments"></i></div>
                        <div>
                            <div style="font-weight:600;font-size:.88rem;color:#1e293b;">Fale sem medo</div>
                            <div style="font-size:.75rem;color:#94a3b8;">Errar faz parte do aprendizado. Pratique conversação sempre que puder.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="tbl-icon-wrap rose" style="min-width:38px;"><i class="fas fa-repeat"></i></div>
                        <div>
                            <div style="font-weight:600;font-size:.88rem;color:#1e293b;">Revise sempre</div>
                            <div style="font-size:.75rem;color:#94a3b8;">Revisar o conteúdo das aulas anteriores acelera sua evolução.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ REAGENDAMENTOS ══ --}}
    @if ($reagendamentos->isNotEmpty())
        <div class="dash-section-title fade-up">Meus Reagendamentos</div>
        <div class="row g-3 mb-4">
            @foreach ($reagendamentos as $r)
                <div class="col-md-6 col-xl-4 fade-up">
                    <div class="d-card h-100" style="border-left:4px solid {{ $r->status === 'confirmado' ? '#10b981' : '#f59e0b' }};">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                    background:{{ $r->status === 'confirmado' ? '#ecfdf5' : '#fffbeb' }};">
                                    <i class="fas {{ $r->status === 'confirmado' ? 'fa-check-circle' : 'fa-clock' }}"
                                        style="font-size:1.1rem;color:{{ $r->status === 'confirmado' ? '#059669' : '#d97706' }};"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $r->aula?->titulo_aulas ?? 'Aula não encontrada' }}
                                    </div>
                                    <div style="font-size:.73rem;color:#94a3b8;">
                                        @if ($r->status === 'confirmado')
                                            <i class="fas fa-calendar-check me-1 text-success"></i>
                                            Nova data:
                                            @if ($r->data_nova)
                                                {{ \Carbon\Carbon::parse($r->data_nova)->format('d/m/Y \à\s H:i') }}
                                            @elseif ($r->aula?->data_aulas)
                                                {{ \Carbon\Carbon::parse($r->aula->data_aulas)->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($r->aula->hora_aulas)->format('H:i') }}
                                            @else
                                                A confirmar
                                            @endif
                                        @else
                                            <i class="fas fa-hourglass-half me-1 text-warning"></i>
                                            Aguardando confirmação
                                        @endif
                                    </div>
                                </div>
                                @if ($r->status === 'confirmado')
                                    <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Confirmado</span>
                                @else
                                    <span class="tbl-status tbl-status-pendente"><span class="tbl-status-dot"></span> Pendente</span>
                                @endif
                            </div>
                            @if($r->motivo)
                                <div style="font-size:.75rem;color:#64748b;background:#f8fafc;padding:.5rem .75rem;border-radius:8px;border-left:3px solid #e2e8f0;">
                                    {{ Str::limit($r->motivo, 80) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ══ FEEDBACK DOS CURSOS ══ --}}
    @if($matriculas->isNotEmpty())
        <div class="dash-section-title fade-up">Avalie seus Professores</div>
        <div class="row g-3 mb-4">
            @foreach($matriculas as $mat)
                @php $fb = $feedbacks[$mat->id_curso] ?? null; @endphp
                <div class="col-md-6 col-xl-4 fade-up">
                    <div class="d-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                                <div style="width:44px;height:44px;border-radius:12px;background:#eef3ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-language" style="font-size:1.2rem;color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $mat->curso->nome_curso ?? $aluno->curso_aluno }}</div>
                                    <div style="font-size:.75rem;color:#94a3b8;">
                                        <i class="fas fa-chalkboard-user me-1"></i>{{ $mat->professor_nome }}
                                    </div>
                                </div>
                            </div>

                            @if($fb)
                                <div style="text-align:center;padding:.5rem 0;">
                                    <div class="fb-stars-display" style="font-size:1.5rem;margin-bottom:.5rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $fb->nota ? 's' : 'r' }} fa-star" style="color:{{ $i <= $fb->nota ? '#f59e0b' : '#e2e8f0' }};"></i>
                                        @endfor
                                    </div>
                                    @if($fb->comentario)
                                        <p style="font-size:.8rem;color:#64748b;font-style:italic;margin:0;">
                                            "{{ Str::limit($fb->comentario, 100) }}"
                                        </p>
                                    @endif
                                    <span style="display:inline-block;margin-top:.5rem;font-size:.7rem;color:#10b981;font-weight:600;">
                                        <i class="fas fa-check-circle me-1"></i>Avaliação enviada
                                    </span>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;border-radius:8px;"
                                            onclick="this.closest('.d-card').querySelector('.fb-form').style.display='block'; this.style.display='none';">
                                            <i class="fas fa-pen me-1"></i>Editar avaliação
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('aluno.feedback.store') }}" method="POST" class="fb-form" style="{{ $fb ? 'display:none;' : '' }}">
                                @csrf
                                <input type="hidden" name="id_curso" value="{{ $mat->id_curso }}">
                                <input type="hidden" name="id_professor" value="{{ $mat->professor_id }}">

                                <div style="text-align:center;margin-bottom:.75rem;">
                                    <label style="font-size:.8rem;font-weight:600;color:#475569;display:block;margin-bottom:.5rem;">
                                        Como foi sua experiência?
                                    </label>
                                    <div class="fb-stars" data-curso="{{ $mat->id_curso }}">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label style="cursor:pointer;font-size:1.6rem;margin:0 2px;transition:transform .15s;">
                                                <input type="radio" name="nota" value="{{ $i }}" style="display:none;" {{ ($fb && $fb->nota == $i) ? 'checked' : '' }}>
                                                <i class="far fa-star fb-star" data-value="{{ $i }}" style="color:#e2e8f0;transition:color .2s;"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <textarea name="comentario" rows="2" class="form-control" style="border-radius:10px;resize:none;font-size:.82rem;"
                                        placeholder="Deixe um comentário sobre a aula... (opcional)" maxlength="500">{{ $fb?->comentario }}</textarea>
                                </div>

                                <button type="submit" class="tbl-btn-novo w-100 justify-content-center" style="border-radius:10px;">
                                    <i class="fas fa-paper-plane"></i> {{ $fb ? 'Atualizar Avaliação' : 'Enviar Avaliação' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="dash-section-title fade-up">Ações Rápidas & Perfil</div>

    {{-- ══ AÇÕES RÁPIDAS + PERFIL ══ --}}
    <div class="row g-3 mb-5">
        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-bolt text-warning"></i> Ações Rápidas</h6></div>
                <div class="card-body p-3">
                    <a href="{{ route('aluno.aulas.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#ecfdf5;color:#059669;"><i class="fas fa-book-open"></i></div>
                        <div><div class="qa-label">Minhas Aulas</div><p class="qa-desc">Ver aulas disponíveis</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('aluno.progresso.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;"><i class="fas fa-chart-line"></i></div>
                        <div><div class="qa-label">Meu Progresso</div><p class="qa-desc">Acompanhar evolução</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('aluno.atividades.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-clipboard-list"></i></div>
                        <div><div class="qa-label">Atividades</div><p class="qa-desc">Ver e enviar atividades</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#modalReagendamento">
                        <div class="qa-icon" style="background:#fff1f2;color:#e11d48;"><i class="fas fa-calendar-alt"></i></div>
                        <div><div class="qa-label">Reagendar Aula</div><p class="qa-desc">Solicitar novo horário</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-user text-primary"></i> Meu Perfil</h6></div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                        @if ($aluno->foto_aluno)
                            <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}" class="prof-avatar" style="width:56px;height:56px;border-radius:14px;" alt="">
                        @else
                            <div class="prof-avatar-placeholder" style="width:56px;height:56px;font-size:1.1rem;border-radius:14px;">{{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}</div>
                        @endif
                        <div>
                            <div style="font-weight:700;font-size:.95rem;color:#1e293b;">{{ $aluno->nome_aluno }}</div>
                            <div style="font-size:.75rem;color:#94a3b8;">{{ $aluno->email_aluno }}</div>
                        </div>
                    </div>

                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-phone me-2" style="color:#6366f1;"></i>Telefone</span><span class="stat-mini-value">{{ $aluno->telefone_aluno ?? '—' }}</span></div>
                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-book me-2" style="color:#10b981;"></i>Curso</span><span class="stat-mini-value">{{ $aluno->curso_aluno ?? '—' }}</span></div>
                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-layer-group me-2" style="color:#f59e0b;"></i>Nível</span><span class="stat-mini-value">{{ $aluno->nivel_aluno ?? '—' }}</span></div>
                    <div class="stat-mini"><span class="stat-mini-label"><i class="fas fa-cake-candles me-2" style="color:#e11d48;"></i>Nascimento</span><span class="stat-mini-value">{{ $aluno->data_nasc_aluno ?? '—' }}</span></div>

                    <a href="{{ route('aluno.perfil') }}" class="tbl-btn-novo w-100 justify-content-center mt-3">
                        <i class="fas fa-pen-to-square"></i> Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card mb-3" id="card-frases-aluno">
                <div class="d-card-header" style="background:linear-gradient(135deg,#f8faff,#eef3ff);">
                    <h6><i class="fas fa-language" style="color:#6366f1;"></i> Frase do Momento</h6>
                </div>
                <div class="card-body p-4 text-center" style="min-height:140px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div id="al-frase-flag" style="font-size:1.4rem;margin-bottom:6px;">🇺🇸</div>
                    <p id="al-frase-texto" style="font-size:.92rem;font-weight:600;color:#1e293b;min-height:40px;margin:0 0 8px;line-height:1.45;transition:opacity .4s;"></p>
                    <div id="al-frase-div" style="width:36px;height:2px;background:linear-gradient(90deg,#6366f1,#a78bfa);border-radius:99px;margin:0 auto 8px;opacity:0;transition:opacity .4s;"></div>
                    <p id="al-frase-trad" style="font-size:.8rem;color:#6366f1;font-style:italic;min-height:32px;margin:0;opacity:0;transition:opacity .4s;"></p>
                </div>
            </div>

            <div class="d-card">
                <div class="card-body p-3" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-radius:16px;">
                    <div class="d-flex align-items-start gap-2">
                        <div style="width:36px;height:36px;border-radius:10px;background:#fde68a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-circle-info" style="color:#b45309;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.82rem;color:#92400e;margin-bottom:.2rem;">Regra de Reagendamento</div>
                            <p style="font-size:.73rem;color:#b45309;margin:0;line-height:1.5;">
                                Envie a solicitação com no mínimo <strong>24h úteis de antecedência</strong>.
                            </p>
                        </div>
                    </div>
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
