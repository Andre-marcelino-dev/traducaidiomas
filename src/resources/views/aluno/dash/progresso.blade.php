@extends('aluno.layout.aluno')

@section('content')

@php
    $totalAulas = (int) ($totalAulas ?? 0);
    $totalPresente = (int) ($totalPresente ?? 0);
    $totalFalta = (int) ($totalFalta ?? 0);

    $totalMateriais = (int) ($totalMateriais ?? 0);
    $materiaisVistos = (int) ($materiaisVistos ?? 0);
    $materiaisPendentes = max($totalMateriais - $materiaisVistos, 0);

    $totalRegistrosPresenca = $totalPresente + $totalFalta;

    $percPresenca = $totalRegistrosPresenca > 0
        ? (int) round(($totalPresente / $totalRegistrosPresenca) * 100)
        : 0;

    $percMateriais = $totalMateriais > 0
        ? (int) round(($materiaisVistos / $totalMateriais) * 100)
        : 0;

    $percPresenca = min(max($percPresenca, 0), 100);
    $percMateriais = min(max($percMateriais, 0), 100);

    $ultimasPresencas = $ultimasPresencas ?? collect();
    $frequenciaOk = $totalRegistrosPresenca > 0 && $percPresenca >= 75;
@endphp

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Meu Progresso</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Meu Progresso</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
<div class="container-fluid aluno-progresso-page">

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-blue shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-chalkboard-user"></i></div>
                <div class="mc-val" data-progress-counter="{{ $totalAulas }}">0</div>
                <p class="mc-lbl">Aulas Registradas</p>
                <div class="mc-trend"><i class="fas fa-database me-1"></i> no hist&oacute;rico</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-green shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                <div class="mc-val" data-progress-counter="{{ $totalPresente }}">0</div>
                <p class="mc-lbl">Presen&ccedil;as</p>
                <div class="mc-trend"><i class="fas fa-check me-1"></i> confirmadas</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-rose shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-circle-xmark"></i></div>
                <div class="mc-val" data-progress-counter="{{ $totalFalta }}">0</div>
                <p class="mc-lbl">Faltas</p>
                <div class="mc-trend"><i class="fas fa-ban me-1"></i> registradas</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc mc-amber shadow-sm h-100">
                <div class="mc-icon"><i class="fas fa-book-open"></i></div>
                <div class="mc-val mc-val-compact">{{ $materiaisVistos }}/{{ $totalMateriais }}</div>
                <p class="mc-lbl">Materiais</p>
                <div class="mc-trend"><i class="fas fa-layer-group me-1"></i> conclu&iacute;dos</div>
            </div>
        </div>
        <div class="col-6 col-xl fade-up">
            <div class="mc shadow-sm h-100" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);">
                <div class="mc-icon"><i class="fas fa-chart-line"></i></div>
                <div class="mc-val">{{ $percPresenca }}%</div>
                <p class="mc-lbl">Frequ&ecirc;ncia</p>
                <div class="mc-trend" style="margin-top:.4rem;">
                    <div class="progress-thin" style="background:rgba(255,255,255,.2);width:100%;margin:0;">
                        <div class="progress-thin-bar" style="width:{{ $percPresenca }}%;background:#10b981;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Resumo & Metas</div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8 fade-up">
            @if($totalRegistrosPresenca > 0)
                <div class="dash-ok-banner {{ $frequenciaOk ? '' : 'progress-warning-banner' }}">
                    <div class="dash-ok-icon {{ $frequenciaOk ? '' : 'progress-warning-icon' }}">
                        <i class="fas {{ $frequenciaOk ? 'fa-circle-check' : 'fa-triangle-exclamation' }}"></i>
                    </div>
                    <div>
                        <div class="dash-ok-title {{ $frequenciaOk ? '' : 'progress-warning-title' }}">
                            @if($frequenciaOk) Frequ&ecirc;ncia em dia! @else Aten&ccedil;&atilde;o &agrave; frequ&ecirc;ncia @endif
                        </div>
                        <div class="dash-ok-sub {{ $frequenciaOk ? '' : 'progress-warning-sub' }}">
                            Sua frequ&ecirc;ncia est&aacute; em <strong>{{ $percPresenca }}%</strong>.
                            @if($frequenciaOk) Continue mantendo essa consist&ecirc;ncia. @else Tente comparecer &agrave;s pr&oacute;ximas aulas. @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="dash-ok-banner progress-info-banner">
                    <div class="dash-ok-icon progress-info-icon"><i class="fas fa-circle-info"></i></div>
                    <div>
                        <div class="dash-ok-title progress-info-title">Progresso em constru&ccedil;&atilde;o</div>
                        <div class="dash-ok-sub progress-info-sub">Quando suas aulas forem finalizadas, sua frequ&ecirc;ncia aparecer&aacute; aqui.</div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header">
                    <h6><i class="fas fa-folder-open text-primary"></i> Materiais</h6>
                    <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> {{ $percMateriais }}%</span>
                </div>
                <div class="card-body p-3">
                    <div class="progress-goal-top">
                        <div>
                            <div class="progress-goal-title">Progresso geral</div>
                            <div class="progress-goal-sub">{{ $materiaisVistos }} de {{ $totalMateriais }} materiais conclu&iacute;dos</div>
                        </div>
                        <div class="progress-goal-value">{{ $percMateriais }}%</div>
                    </div>
                    <div class="progress-thin progress-goal-track">
                        <div class="progress-thin-bar" style="width:{{ $percMateriais }}%;"></div>
                    </div>
                    @if($totalMateriais === 0)
                        <div class="today-empty py-3">
                            <i class="fas fa-folder-open opacity-25 fa-2x"></i>
                            <span>Nenhum material dispon&iacute;vel</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">An&aacute;lises</div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-user-check text-success"></i> Frequ&ecirc;ncia nas Aulas</h6></div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center progress-chart-body">
                    @if($totalRegistrosPresenca > 0)
                        <div class="progress-chart-wrap">
                            <canvas id="chartPresenca"></canvas>
                            <div class="progress-chart-center">
                                <div class="progress-chart-value">{{ $percPresenca }}%</div>
                                <div class="progress-chart-label">Presen&ccedil;a</div>
                            </div>
                        </div>
                        <div class="progress-legend mt-3">
                            <span><i class="fas fa-circle" style="color:#10b981;"></i> Presente ({{ $totalPresente }})</span>
                            <span><i class="fas fa-circle" style="color:#f43f5e;"></i> Falta ({{ $totalFalta }})</span>
                        </div>
                    @else
                        <div class="today-empty">
                            <i class="fas fa-calendar-times fa-2x opacity-25"></i>
                            <span>Nenhuma frequ&ecirc;ncia registrada</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 fade-up">
            <div class="d-card h-100">
                <div class="d-card-header"><h6><i class="fas fa-book-open text-primary"></i> Progresso nos Materiais</h6></div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center progress-chart-body">
                    @if($totalMateriais > 0)
                        <div class="progress-chart-wrap">
                            <canvas id="chartMateriais"></canvas>
                            <div class="progress-chart-center">
                                <div class="progress-chart-value">{{ $percMateriais }}%</div>
                                <div class="progress-chart-label">Conclu&iacute;do</div>
                            </div>
                        </div>
                        <div class="progress-legend mt-3">
                            <span><i class="fas fa-circle" style="color:#6366f1;"></i> Conclu&iacute;dos ({{ $materiaisVistos }})</span>
                            <span><i class="fas fa-circle" style="color:#e2e8f0;"></i> Pendentes ({{ $materiaisPendentes }})</span>
                        </div>
                    @else
                        <div class="today-empty">
                            <i class="fas fa-folder-open fa-2x opacity-25"></i>
                            <span>Assim que novos materiais forem enviados, eles aparecer&atilde;o aqui.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="dash-section-title fade-up">Hist&oacute;rico</div>

    <div class="row g-3 mb-5">
        <div class="col-12 fade-up">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-clock-rotate-left text-primary"></i> &Uacute;ltimas Aulas</h6>
                    <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> {{ $ultimasPresencas->count() }} registro{{ $ultimasPresencas->count() === 1 ? '' : 's' }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Aula</th>
                                <th>Data</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasPresencas as $p)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tbl-icon-wrap"><i class="fas fa-chalkboard-user"></i></div>
                                            <div>
                                                <div style="font-weight:600;font-size:.875rem;">{{ $p->aula?->titulo_aulas ?? 'Aula nao encontrada' }}</div>
                                                <div style="font-size:.7rem;color:#94a3b8;">Registro de presen&ccedil;a</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:.82rem;">
                                        @if(!empty($p->data_registro_presenca))
                                            {{ \Carbon\Carbon::parse($p->data_registro_presenca)->format('d/m/Y') }}
                                        @else
                                            &mdash;
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($p->status_presenca == 'PRESENTE')
                                            <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Presente</span>
                                        @else
                                            <span class="tbl-status tbl-status-cancelado"><span class="tbl-status-dot"></span> Falta</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        <div class="tbl-empty">
                                            <i class="fas fa-inbox tbl-empty-icon"></i>
                                            <span class="tbl-empty-text">Nenhuma aula registrada ainda.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@push('scripts')
<style>
    .aluno-progresso-page .mc-val-compact {
        font-size: 1.55rem;
        white-space: nowrap;
    }
    .progress-warning-banner {
        background: linear-gradient(135deg,#fffbeb,#fef3c7);
        border-color: #fde68a;
    }
    .progress-warning-icon {
        background: #fde68a;
        color: #b45309;
    }
    .progress-warning-title { color: #92400e; }
    .progress-warning-sub {
        color: #b45309;
        opacity: 1;
    }
    .progress-info-banner {
        background: linear-gradient(135deg,#f8faff,#eef3ff);
        border-color: #e0e7ff;
    }
    .progress-info-icon {
        background: #eef3ff;
        color: #6366f1;
    }
    .progress-info-title { color: #312e81; }
    .progress-info-sub {
        color: #6366f1;
        opacity: .85;
    }
    .progress-goal-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: .8rem;
    }
    .progress-goal-title {
        color: var(--slate-800);
        font-size: .9rem;
        font-weight: 800;
    }
    .progress-goal-sub {
        color: var(--slate-400);
        font-size: .74rem;
        margin-top: .15rem;
    }
    .progress-goal-value {
        color: var(--indigo);
        font-size: 1.25rem;
        font-weight: 900;
        line-height: 1;
    }
    .progress-goal-track { height: 8px; }
    .progress-chart-body { min-height: 280px; }
    .progress-chart-wrap {
        position: relative;
        width: 190px;
        height: 190px;
    }
    .progress-chart-center {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
    .progress-chart-value {
        color: var(--slate-800);
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
    }
    .progress-chart-label {
        color: var(--slate-400);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .08em;
        margin-top: .35rem;
        text-transform: uppercase;
    }
    .progress-legend {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem 1rem;
        justify-content: center;
        color: var(--slate-600);
        font-size: .78rem;
        font-weight: 600;
    }
    .progress-legend span {
        align-items: center;
        display: inline-flex;
        gap: .35rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-progress-counter]').forEach(function(el) {
        var target = parseInt(el.dataset.progressCounter || '0', 10);
        var start = performance.now();
        function tick(now) {
            var p = Math.min((now - start) / 900, 1);
            var e = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(e * target);
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });

    if (!window.Chart) return;
    Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
    Chart.defaults.color = '#64748b';

    var canvasPresenca = document.getElementById('chartPresenca');
    if (canvasPresenca) {
        new Chart(canvasPresenca, {
            type: 'doughnut',
            data: {
                labels: ['Presente', 'Falta'],
                datasets: [{
                    data: [@json($totalPresente), @json($totalFalta)],
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

    var canvasMateriais = document.getElementById('chartMateriais');
    if (canvasMateriais) {
        new Chart(canvasMateriais, {
            type: 'doughnut',
            data: {
                labels: ['Concluidos', 'Pendentes'],
                datasets: [{
                    data: [@json($materiaisVistos), @json($materiaisPendentes)],
                    backgroundColor: ['#6366f1', '#e2e8f0'],
                    borderWidth: 3,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush

@endsection