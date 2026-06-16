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
                        <div class="mc-val" id="totalAulas" data-target="{{ $totalAulas }}">0</div>
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

            {{-- ── GRÁFICOS ── --}}
            <div class="row g-3 mb-4">

                {{-- Presença geral --}}
                <div class="col-lg-3 col-md-6 fade-up">
                    <div class="card recent-card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-user-check me-2 text-success"></i>Presença Geral</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center py-3">
                            <canvas id="chartPresenca" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Alunos por nível --}}
                <div class="col-lg-3 col-md-6 fade-up">
                    <div class="card recent-card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-layer-group me-2 text-info"></i>Alunos por Nível</h5>
                        </div>
                        <div class="card-body d-flex align-items-center justify-content-center py-3">
                            <canvas id="chartNiveis" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Distribuição de notas --}}
                <div class="col-lg-6 col-md-12 fade-up">
                    <div class="card recent-card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-star-half-stroke me-2 text-warning"></i>Distribuição de Notas</h5>
                        </div>
                        <div class="card-body py-3">
                            <canvas id="chartNotas" style="max-height:220px;"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Aulas por mês --}}
                <div class="col-12 fade-up">
                    <div class="card recent-card">
                        <div class="card-header">
                            <h5><i class="fas fa-calendar-days me-2 text-primary"></i>Aulas nos Últimos 6 Meses</h5>
                        </div>
                        <div class="card-body py-3">
                            <canvas id="chartAulas" style="max-height:200px;"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── ALUNOS RECENTES + PRÓXIMAS AULAS ── --}}
            <div class="row g-3 mb-4">

                {{-- Alunos recentes --}}
                <div class="col-lg-8 fade-up">
                    <div class="recent-card card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-user-graduate me-2 text-success"></i>Alunos Recentes</h5>
                            <a href="{{ route('admin.alunos.index') }}">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table recent-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Aluno</th>
                                        <th>Curso</th>
                                        <th>Nível</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($alunosRecentes as $aluno)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($aluno->foto_aluno)
                                                        <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                                            class="prof-avatar" alt="{{ $aluno->nome_aluno }}">
                                                    @else
                                                        <div class="prof-avatar-placeholder">
                                                            {{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div style="font-weight:600;font-size:.875rem;">{{ $aluno->nome_aluno }}</div>
                                                        <div style="font-size:.72rem;color:#94a3b8;">{{ $aluno->email_aluno }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $aluno->curso_aluno ?? '—' }}</td>
                                            <td>
                                                @php $nv = strtolower($aluno->nivel_aluno ?? ''); @endphp
                                                <span class="badge-nivel
                                                    {{ str_contains($nv,'básico') || str_contains($nv,'basico') || str_contains($nv,'inic')
                                                        ? 'badge-basico'
                                                        : (str_contains($nv,'inter') ? 'badge-intermediario'
                                                        : (str_contains($nv,'avan') ? 'badge-avancado' : 'badge-fluente')) }}">
                                                    {{ $aluno->nivel_aluno ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(strtoupper($aluno->status_aluno ?? '') === 'EM CURSO')
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size:.7rem;">Ativo</span>
                                                @elseif(in_array(strtoupper($aluno->status_aluno ?? ''), ['CONCLUÍDO', 'CONCLUIDO']))
                                                    <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size:.7rem;">Concluído</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size:.7rem;">Inativo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                                Nenhum aluno cadastrado ainda
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Próximas aulas --}}
                <div class="col-lg-4 fade-up">
                    <div class="card recent-card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-calendar-clock me-2 text-primary"></i>Próximas Aulas</h5>
                            <a href="{{ route('admin.aulas.index') }}">Ver todas <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                        <div class="card-body p-3">
                            @forelse($proximasAulas as $aula)
                                @php
                                    $data = \Carbon\Carbon::parse($aula->data_aulas);
                                    $hoje = now()->isToday() && $data->isToday();
                                @endphp
                                <div class="d-flex align-items-start gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                                    <div class="text-center rounded-2 px-2 py-1 flex-shrink-0"
                                        style="background:{{ $hoje ? '#eef3ff' : '#f8fafc' }};min-width:46px;">
                                        <div style="font-size:.65rem;color:#64748b;text-transform:uppercase;font-weight:600;">
                                            {{ $data->translatedFormat('M') }}
                                        </div>
                                        <div style="font-size:1.3rem;font-weight:700;color:{{ $hoje ? '#4f46e5' : '#1e293b' }};line-height:1.1;">
                                            {{ $data->format('d') }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $aula->titulo_aulas }}
                                        </div>
                                        <div style="font-size:.75rem;color:#94a3b8;">
                                            <i class="fas fa-clock me-1"></i>{{ substr($aula->hora_aulas, 0, 5) }}
                                            @if($aula->cursos_aulas)
                                                &nbsp;·&nbsp; {{ $aula->cursos_aulas }}
                                            @endif
                                        </div>
                                    </div>
                                    @if($hoje)
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2" style="font-size:.65rem;white-space:nowrap;">Hoje</span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-calendar-xmark fa-2x mb-2 d-block opacity-25"></i>
                                    <small>Nenhuma aula agendada</small>
                                </div>
                            @endforelse
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

                </div>
            </div>

            {{-- ── REAGENDAMENTOS PENDENTES + RANKING DE NOTAS ── --}}
            <div class="row g-3 mt-1 mb-4">

                {{-- Reagendamentos pendentes --}}
                <div class="col-lg-6 fade-up">
                    <div class="card recent-card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-calendar-xmark me-2 text-danger"></i>Reagendamentos Pendentes</h5>
                            <a href="{{ route('admin.reagendamentos.index') }}">Ver todos <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                        <div class="card-body p-3">
                            @forelse($reagendamentosPendentes as $reag)
                                <div class="d-flex align-items-start gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                                    <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:38px;height:38px;background:#fff1f2;color:#f43f5e;font-size:.8rem;font-weight:700;">
                                        {{ strtoupper(substr($reag->aluno->nome_aluno ?? '?', 0, 2)) }}
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div style="font-weight:600;font-size:.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $reag->aluno->nome_aluno ?? 'Aluno removido' }}
                                        </div>
                                        <div style="font-size:.75rem;color:#94a3b8;">
                                            <i class="fas fa-arrow-right-arrow-left me-1"></i>
                                            {{ $reag->data_original ? \Carbon\Carbon::parse($reag->data_original)->format('d/m') : '—' }}
                                            &rarr;
                                            {{ $reag->data_sugerida ? \Carbon\Carbon::parse($reag->data_sugerida)->format('d/m/Y') : '—' }}
                                        </div>
                                        @if($reag->motivo)
                                            <div style="font-size:.72rem;color:#cbd5e1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ Str::limit($reag->motivo, 50) }}
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.reagendamentos.index') }}"
                                        class="btn btn-sm flex-shrink-0"
                                        style="background:#fff1f2;color:#f43f5e;border:none;font-size:.72rem;padding:4px 10px;border-radius:20px;white-space:nowrap;">
                                        Responder
                                    </a>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-circle-check fa-2x mb-2 d-block text-success opacity-50"></i>
                                    <small>Nenhum reagendamento pendente</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Ranking de notas --}}
                <div class="col-lg-6 fade-up">
                    <div class="card recent-card h-100">
                        <div class="card-header">
                            <h5><i class="fas fa-trophy me-2 text-warning"></i>Ranking de Notas</h5>
                        </div>
                        <div class="card-body p-3">
                            @forelse($topAlunos as $i => $item)
                                @php
                                    $medalhas = ['🥇','🥈','🥉'];
                                    $medalha  = $medalhas[$i] ?? '#' . ($i + 1);
                                    $media    = round($item->media, 1);
                                    $pct      = min(100, $media * 10);
                                    $cor      = $media >= 9 ? '#22c55e' : ($media >= 7 ? '#3b82f6' : ($media >= 5 ? '#f59e0b' : '#f87171'));
                                @endphp
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span style="font-size:1.25rem;width:28px;text-align:center;flex-shrink:0;">{{ $medalha }}</span>
                                    <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center"
                                        style="width:36px;height:36px;background:#f8fafc;font-size:.75rem;font-weight:700;color:#475569;border:1px solid #e2e8f0;">
                                        {{ strtoupper(substr($item->aluno->nome_aluno ?? '?', 0, 2)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span style="font-size:.82rem;font-weight:600;">{{ $item->aluno->nome_aluno ?? 'Aluno' }}</span>
                                            <span style="font-size:.82rem;font-weight:700;color:{{ $cor }};">{{ $media }}</span>
                                        </div>
                                        <div style="height:5px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                                            <div style="width:{{ $pct }}%;height:100%;background:{{ $cor }};border-radius:99px;transition:width .6s;"></div>
                                        </div>
                                        <div style="font-size:.68rem;color:#94a3b8;">{{ $item->total_atividades }} {{ $item->total_atividades == 1 ? 'atividade' : 'atividades' }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-star fa-2x mb-2 d-block opacity-25"></i>
                                    <small>Nenhuma nota registrada ainda</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
        Chart.defaults.color = '#64748b';

        // ── 1. Presença (pizza) ──────────────────────────────────────────────
        new Chart(document.getElementById('chartPresenca'), {
            type: 'doughnut',
            data: {
                labels: ['Presentes', 'Ausentes'],
                datasets: [{
                    data: [{{ $presencaPresentes }}, {{ $presencaAusentes }}],
                    backgroundColor: ['#22c55e', '#f87171'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14 } }
                }
            }
        });

        // ── 2. Alunos por nível (rosca) ──────────────────────────────────────
        @php
            $niveisLabels = $alunosPorNivel->keys()->map(fn($n) => $n ?: 'Não informado');
            $niveisValues = $alunosPorNivel->values();
            $niveisColors = ['#6366f1','#0ea5e9','#f59e0b','#10b981','#f43f5e','#a78bfa'];
        @endphp
        new Chart(document.getElementById('chartNiveis'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($niveisLabels) !!},
                datasets: [{
                    data: {!! json_encode($niveisValues) !!},
                    backgroundColor: {!! json_encode(array_slice($niveisColors, 0, count($niveisValues))) !!},
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } }
                }
            }
        });

        // ── 3. Distribuição de notas (barras) ────────────────────────────────
        new Chart(document.getElementById('chartNotas'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($notasFaixas)) !!},
                datasets: [{
                    label: 'Atividades',
                    data: {!! json_encode(array_values($notasFaixas)) !!},
                    backgroundColor: ['#f87171','#fb923c','#facc15','#4ade80'],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // ── 4. Aulas por mês (linha) ─────────────────────────────────────────
        @php
            $mesesLabels = $aulasPorMes->keys()->map(function($m) {
                $meses = ['01'=>'Jan','02'=>'Fev','03'=>'Mar','04'=>'Abr','05'=>'Mai','06'=>'Jun',
                          '07'=>'Jul','08'=>'Ago','09'=>'Set','10'=>'Out','11'=>'Nov','12'=>'Dez'];
                [$ano, $num] = explode('-', $m);
                return ($meses[$num] ?? $num) . '/' . substr($ano, 2);
            });
        @endphp
        new Chart(document.getElementById('chartAulas'), {
            type: 'line',
            data: {
                labels: {!! json_encode($mesesLabels) !!},
                datasets: [{
                    label: 'Aulas realizadas',
                    data: {!! json_encode($aulasPorMes->values()) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,.12)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 5
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>

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

            // Aulas
            var elA    = document.getElementById('totalAulas');
            var trendA = document.getElementById('aulas-trend');
            if (elA) {
                var targetA = parseInt(elA.dataset.target) || 0;
                var startA  = performance.now();
                function tickA(now) {
                    var p    = Math.min((now - startA) / 1000, 1);
                    var ease = 1 - Math.pow(1 - p, 3);
                    elA.textContent = Math.round(ease * targetA);
                    if (p < 1) requestAnimationFrame(tickA);
                    else trendA.textContent = targetA + (targetA !== 1 ? ' cadastradas' : ' cadastrada');
                }
                requestAnimationFrame(tickA);
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
