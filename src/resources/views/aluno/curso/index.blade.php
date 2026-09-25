@extends('aluno.layout.aluno')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Meu Curso</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Curso</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

                {{-- CARDS --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-3 fade-up">
                        <div class="mc mc-blue shadow">
                            <div class="mc-icon"><i class="fas fa-clock"></i></div>
                            <div class="mc-val">{{ intdiv($totalMinutos, 60) }}h{{ str_pad($totalMinutos % 60, 2, '0', STR_PAD_LEFT) }}</div>
                            <p class="mc-lbl">Carga Horária</p>
                            <div class="mc-trend"><i class="fas fa-hourglass-half me-1"></i>total do curso</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3 fade-up">
                        <div class="mc mc-amber shadow">
                            <div class="mc-icon"><i class="fas fa-chalkboard"></i></div>
                            <div class="mc-val">{{ $totalAulas }}</div>
                            <p class="mc-lbl">Aulas</p>
                            <div class="mc-trend"><i class="fas fa-layer-group me-1"></i>{{ $modulos->count() }} módulos</div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6 fade-up">
                        <div class="mc mc-green shadow">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="mc-lbl mb-0">Progresso Geral</p>
                                <span class="fw-bold" style="font-size:1.1rem;">{{ $percentualGeral }}%</span>
                            </div>
                            <div style="background:#e2e8f0;border-radius:99px;height:8px;">
                                <div style="width:{{ $percentualGeral }}%;background:#22c55e;height:8px;border-radius:99px;"></div>
                            </div>
                            <div class="mc-trend mt-2"><i class="fas fa-graduation-cap me-1"></i>{{ $matriculaAtual->nivel->nome_nivel ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- MÓDULOS --}}
                <div class="d-card fade-up">
                    <div class="d-card-header">
                        <h6><i class="fas fa-layer-group text-primary"></i> Conteúdo do Curso</h6>
                    </div>
                    <div class="card-body p-3">
                        @forelse($modulos as $modulo)
                            <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded"
                                 style="border:1px solid #e2e8f0; {{ !$modulo->liberado ? 'opacity:.6;' : '' }}">
                                <div style="width:40px;height:40px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                                    background:{{ $modulo->concluido ? '#dcfce7' : ($modulo->liberado ? '#dbeafe' : '#f1f5f9') }};">
                                    @if($modulo->concluido)
                                        <i class="fas fa-check text-success"></i>
                                    @elseif($modulo->liberado)
                                        <i class="fas fa-book-open text-primary"></i>
                                    @else
                                        <i class="fas fa-lock text-secondary"></i>
                                    @endif
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fw-bold" style="font-size:.9rem;">
                                            Módulo {{ $modulo->ordem_modulo }} - {{ $modulo->nome_modulo }}
                                        </span>
                                        @if($modulo->em_andamento)
                                            <span class="badge bg-primary-subtle text-primary" style="font-size:.65rem;">Você está aqui</span>
                                        @endif
                                    </div>
                                    <div style="font-size:.75rem;color:#94a3b8;">
                                        {{ $modulo->aulas_count }} aulas ·
                                        {{ intdiv($modulo->carga_horaria_minutos, 60) }}h{{ str_pad($modulo->carga_horaria_minutos % 60, 2, '0', STR_PAD_LEFT) }}
                                    </div>

                                    @if($modulo->liberado && !$modulo->concluido && $modulo->materiais_count > 0)
                                        <div style="background:#e2e8f0;border-radius:99px;height:5px;max-width:220px;" class="mt-2">
                                            <div style="width:{{ $modulo->percentual }}%;background:#6366f1;height:5px;border-radius:99px;"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="text-end" style="min-width:110px;">
                                    @if($modulo->concluido)
                                        <span class="tbl-status tbl-status-ativo"><span class="tbl-status-dot"></span>Concluído</span>
                                    @elseif($modulo->liberado)
                                        <span class="tbl-status tbl-status-congelado"><span class="tbl-status-dot"></span>Em andamento</span>
                                    @else
                                        <span class="tbl-status tbl-status-inativo"><span class="tbl-status-dot"></span>Bloqueado</span>
                                    @endif
                                </div>
                            </div>
                            @if(!$modulo->liberado)
                                <div class="text-muted mb-3" style="font-size:.72rem;margin-left:56px;margin-top:-6px;">
                                    Conclua o módulo anterior para avançar.
                                </div>
                            @endif
                        @empty
                            <div class="tbl-empty">
                                <i class="fas fa-layer-group tbl-empty-icon"></i>
                                <span class="tbl-empty-text">Nenhum módulo cadastrado ainda para este curso.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

        </div>
    </div>
@endsection
