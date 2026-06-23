@extends('admin.layout.admin')

@section('content')

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
                        <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.75 9C12.75 8.58579 12.4142 8.25 12 8.25C11.5858 8.25 11.25 8.58579 11.25 9L11.25 11.25H9C8.58579 11.25 8.25 11.5858 8.25 12C8.25 12.4142 8.58579 12.75 9 12.75H11.25V15C11.25 15.4142 11.5858 15.75 12 15.75C12.4142 15.75 12.75 15.4142 12.75 15L12.75 12.75H15C15.4142 12.75 15.75 12.4142 15.75 12C15.75 11.5858 15.4142 11.25 15 11.25H12.75V9Z" fill="#2563eb"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.0574 1.25H11.9426C9.63424 1.24999 7.82519 1.24998 6.41371 1.43975C4.96897 1.63399 3.82895 2.03933 2.93414 2.93414C2.03933 3.82895 1.63399 4.96897 1.43975 6.41371C1.24998 7.82519 1.24999 9.63422 1.25 11.9426V12.0574C1.24999 14.3658 1.24998 16.1748 1.43975 17.5863C1.63399 19.031 2.03933 20.1711 2.93414 21.0659C3.82895 21.9607 4.96897 22.366 6.41371 22.5603C7.82519 22.75 9.63423 22.75 11.9426 22.75H12.0574C14.3658 22.75 16.1748 22.75 17.5863 22.5603C19.031 22.366 20.1711 21.9607 21.0659 21.0659C21.9607 20.1711 22.366 19.031 22.5603 17.5863C22.75 16.1748 22.75 14.3658 22.75 12.0574V11.9426C22.75 9.63423 22.75 7.82519 22.5603 6.41371C22.366 4.96897 21.9607 3.82895 21.0659 2.93414C20.1711 2.03933 19.031 1.63399 17.5863 1.43975C16.1748 1.24998 14.3658 1.24999 12.0574 1.25ZM3.9948 3.9948C4.56445 3.42514 5.33517 3.09825 6.61358 2.92637C7.91356 2.75159 9.62177 2.75 12 2.75C14.3782 2.75 16.0864 2.75159 17.3864 2.92637C18.6648 3.09825 19.4355 3.42514 20.0052 3.9948C20.5749 4.56445 20.9018 5.33517 21.0736 6.61358C21.2484 7.91356 21.25 9.62177 21.25 12C21.25 14.3782 21.2484 16.0864 21.0736 17.3864C20.9018 18.6648 20.5749 19.4355 20.0052 20.0052C19.4355 20.5749 18.6648 20.9018 17.3864 21.0736C16.0864 21.2484 14.3782 21.25 12 21.25C9.62177 21.25 7.91356 21.2484 6.61358 21.0736C5.33517 20.9018 4.56445 20.5749 3.9948 20.0052C3.42514 19.4355 3.09825 18.6648 2.92637 17.3864C2.75159 16.0864 2.75 14.3782 2.75 12C2.75 9.62177 2.75159 7.91356 2.92637 6.61358C3.09825 5.33517 3.42514 4.56445 3.9948 3.9948Z" fill="#2563eb"/>
</svg></i></div>
                        <div><div class="qa-label">Novo Professor</div><p class="qa-desc">Cadastrar professor</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('admin.alunos.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#ecfdf5;color:#059669;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="#2563eb"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z" fill="#2563eb"/>
</svg></i></div>
                        <div><div class="qa-label">Ver Alunos</div><p class="qa-desc">Listar todos</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('admin.presenca.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#f0fdf4;color:#16a34a;"><svg stroke="#2563eb" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path opacity="0.4" d="M3.41016 22C3.41016 18.13 7.26015 15 12.0002 15C12.9602 15 13.8902 15.13 14.7602 15.37" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M22 18C22 18.32 21.96 18.63 21.88 18.93C21.79 19.33 21.63 19.72 21.42 20.06C20.73 21.22 19.46 22 18 22C16.97 22 16.04 21.61 15.34 20.97C15.04 20.71 14.78 20.4 14.58 20.06C14.21 19.46 14 18.75 14 18C14 16.92 14.43 15.93 15.13 15.21C15.86 14.46 16.88 14 18 14C19.18 14 20.25 14.51 20.97 15.33C21.61 16.04 22 16.98 22 18Z" stroke="#2563eb" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M19.4897 17.98H16.5098" stroke="#2563eb" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M18 16.52V19.51" stroke="#2563eb" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
</svg></i></div>
                        <div><div class="qa-label">Registrar Presença</div><p class="qa-desc">Chamada de aulas</p></div>
                        <i class="fas fa-chevron-right qa-arrow"></i>
                    </a>
                    <a href="{{ route('admin.reagendamentos.index') }}" class="quick-action">
                        <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M7 1.75C7.41421 1.75 7.75 2.08579 7.75 2.5V3.26272C8.41203 3.24999 9.1414 3.24999 9.94358 3.25H14.0564C14.8586 3.24999 15.588 3.24999 16.25 3.26272V2.5C16.25 2.08579 16.5858 1.75 17 1.75C17.4142 1.75 17.75 2.08579 17.75 2.5V3.32709C18.0099 3.34691 18.2561 3.37182 18.489 3.40313C19.6614 3.56076 20.6104 3.89288 21.3588 4.64124C22.1071 5.38961 22.4392 6.33855 22.5969 7.51098C22.6472 7.88567 22.681 8.29459 22.7037 8.74007C22.7337 8.82106 22.75 8.90861 22.75 9C22.75 9.06932 22.7406 9.13644 22.723 9.20016C22.75 10.0021 22.75 10.9128 22.75 11.9436V14.0564C22.75 15.8942 22.75 17.3498 22.5969 18.489C22.4392 19.6614 22.1071 20.6104 21.3588 21.3588C20.6104 22.1071 19.6614 22.4392 18.489 22.5969C17.3498 22.75 15.8942 22.75 14.0564 22.75H9.94359C8.10583 22.75 6.65019 22.75 5.51098 22.5969C4.33856 22.4392 3.38961 22.1071 2.64124 21.3588C1.89288 20.6104 1.56076 19.6614 1.40314 18.489C1.24997 17.3498 1.24998 15.8942 1.25 14.0564V11.9436C1.24999 10.9127 1.24998 10.0021 1.27701 9.20017C1.25941 9.13645 1.25 9.06932 1.25 9C1.25 8.90862 1.26634 8.82105 1.29627 8.74006C1.31895 8.29458 1.35276 7.88566 1.40314 7.51098C1.56076 6.33856 1.89288 5.38961 2.64124 4.64124C3.38961 3.89288 4.33856 3.56076 5.51098 3.40313C5.7439 3.37182 5.99006 3.34691 6.25 3.32709V2.5C6.25 2.08579 6.58579 1.75 7 1.75ZM2.76309 9.75C2.75032 10.4027 2.75 11.146 2.75 12V14C2.75 15.9068 2.75159 17.2615 2.88976 18.2892C3.02502 19.2952 3.27869 19.8749 3.7019 20.2981C4.12511 20.7213 4.70476 20.975 5.71085 21.1102C6.73851 21.2484 8.09318 21.25 10 21.25H14C15.9068 21.25 17.2615 21.2484 18.2892 21.1102C19.2952 20.975 19.8749 20.7213 20.2981 20.2981C20.7213 19.8749 20.975 19.2952 21.1102 18.2892C21.2484 17.2615 21.25 15.9068 21.25 14V12C21.25 11.146 21.2497 10.4027 21.2369 9.75H2.76309ZM21.1683 8.25H2.83168C2.8477 8.06061 2.86685 7.88123 2.88976 7.71085C3.02502 6.70476 3.27869 6.12511 3.7019 5.7019C4.12511 5.27869 4.70476 5.02502 5.71085 4.88976C6.73851 4.75159 8.09318 4.75 10 4.75H14C15.9068 4.75 17.2615 4.75159 18.2892 4.88976C19.2952 5.02502 19.8749 5.27869 20.2981 5.7019C20.7213 6.12511 20.975 6.70476 21.1102 7.71085C21.1331 7.88123 21.1523 8.06061 21.1683 8.25Z" fill="#2563eb"/>
</svg></i></div>
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
                                    <td><a href="{{ route('admin.professores.edit', $prof->id_professor) }}" class="dash-edit-btn" title="Editar"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25L13.5 1.25C13.9142 1.25 14.25 1.58579 14.25 2C14.25 2.41421 13.9142 2.75 13.5 2.75H12C9.62178 2.75 7.91356 2.75159 6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12V10.5C21.25 10.0858 21.5858 9.75 22 9.75C22.4142 9.75 22.75 10.0858 22.75 10.5V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM16.7705 2.27592C18.1384 0.908029 20.3562 0.908029 21.7241 2.27592C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27592ZM20.6634 3.33658C19.8813 2.55448 18.6133 2.55448 17.8312 3.33658L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33658ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z" fill="#2563eb"/>
</svg></a></td>
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
                <div class="dash-ok-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16.0303 10.0303C16.3232 9.73744 16.3232 9.26256 16.0303 8.96967C15.7374 8.67678 15.2626 8.67678 14.9697 8.96967L10.5 13.4393L9.03033 11.9697C8.73744 11.6768 8.26256 11.6768 7.96967 11.9697C7.67678 12.2626 7.67678 12.7374 7.96967 13.0303L9.96967 15.0303C10.2626 15.3232 10.7374 15.3232 11.0303 15.0303L16.0303 10.0303Z" fill="#22C55E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.0574 1.25H11.9426C9.63424 1.24999 7.82519 1.24998 6.41371 1.43975C4.96897 1.63399 3.82895 2.03933 2.93414 2.93414C2.03933 3.82895 1.63399 4.96897 1.43975 6.41371C1.24998 7.82519 1.24999 9.63422 1.25 11.9426V12.0574C1.24999 14.3658 1.24998 16.1748 1.43975 17.5863C1.63399 19.031 2.03933 20.1711 2.93414 21.0659C3.82895 21.9607 4.96897 22.366 6.41371 22.5603C7.82519 22.75 9.63423 22.75 11.9426 22.75H12.0574C14.3658 22.75 16.1748 22.75 17.5863 22.5603C19.031 22.366 20.1711 21.9607 21.0659 21.0659C21.9607 20.1711 22.366 19.031 22.5603 17.5863C22.75 16.1748 22.75 14.3658 22.75 12.0574V11.9426C22.75 9.63423 22.75 7.82519 22.5603 6.41371C22.366 4.96897 21.9607 3.82895 21.0659 2.93414C20.1711 2.03933 19.031 1.63399 17.5863 1.43975C16.1748 1.24998 14.3658 1.24999 12.0574 1.25ZM3.9948 3.9948C4.56445 3.42514 5.33517 3.09825 6.61358 2.92637C7.91356 2.75159 9.62177 2.75 12 2.75C14.3782 2.75 16.0864 2.75159 17.3864 2.92637C18.6648 3.09825 19.4355 3.42514 20.0052 3.9948C20.5749 4.56445 20.9018 5.33517 21.0736 6.61358C21.2484 7.91356 21.25 9.62177 21.25 12C21.25 14.3782 21.2484 16.0864 21.0736 17.3864C20.9018 18.6648 20.5749 19.4355 20.0052 20.0052C19.4355 20.5749 18.6648 20.9018 17.3864 21.0736C16.0864 21.2484 14.3782 21.25 12 21.25C9.62177 21.25 7.91356 21.2484 6.61358 21.0736C5.33517 20.9018 4.56445 20.5749 3.9948 20.0052C3.42514 19.4355 3.09825 18.6648 2.92637 17.3864C2.75159 16.0864 2.75 14.3782 2.75 12C2.75 9.62177 2.75159 7.91356 2.92637 6.61358C3.09825 5.33517 3.42514 4.56445 3.9948 3.9948Z" fill="#22C55E"/>
</svg></div>
                <div>
                    <div class="dash-ok-title">Tudo em dia!</div>
                    <div class="dash-ok-sub">Nenhum reagendamento pendente no momento</div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
