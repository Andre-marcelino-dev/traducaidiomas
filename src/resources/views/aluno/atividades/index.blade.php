@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Minhas Atividades</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Atividades</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- METRIC CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-4 fade-up">
                <div class="mc mc-blue shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-clipboard-list"></i></div>
                    <div class="mc-val">{{ $atividades->count() }}</div>
                    <p class="mc-lbl">Total de Atividades</p>
                    <div class="mc-trend"><i class="fas fa-list-check me-1"></i> recebidas</div>
                </div>
            </div>
            <div class="col-sm-4 fade-up">
                <div class="mc mc-amber shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-hourglass-half"></i></div>
                    <div class="mc-val">{{ $pendentes }}</div>
                    <p class="mc-lbl">Pendentes</p>
                    <div class="mc-trend"><i class="fas fa-clock me-1"></i> a fazer</div>
                </div>
            </div>
            <div class="col-sm-4 fade-up">
                <div class="mc mc-green shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="mc-val">{{ $concluidas }}</div>
                    <p class="mc-lbl">Concluídas</p>
                    <div class="mc-trend"><i class="fas fa-medal me-1"></i> enviadas</div>
                </div>
            </div>
        </div>

        {{-- ATIVIDADES --}}
        <div class="row g-3">
            @forelse($atividades as $atividade)
                @php
                    $resposta = $atividade->respostas->first();
                    $status = $resposta ? $resposta->status_resposta : 'PENDENTE';
                    $corConfig = match($status) {
                        'CORRIGIDA' => ['border' => '#10b981', 'bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fa-check-circle', 'label' => 'Corrigida'],
                        'ENVIADA'   => ['border' => '#f59e0b', 'bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fa-paper-plane', 'label' => 'Enviada'],
                        default     => ['border' => '#6366f1', 'bg' => '#eef3ff', 'color' => '#4f46e5', 'icon' => 'fa-pen-to-square', 'label' => 'Pendente'],
                    };
                @endphp
                <div class="col-md-6 col-xl-4 fade-up">
                    <div class="d-card h-100" style="border-top:3px solid {{ $corConfig['border'] }};">
                        <div class="card-body p-3">
                            {{-- Header --}}
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:38px;height:38px;border-radius:10px;background:{{ $corConfig['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas {{ $corConfig['icon'] }}" style="color:{{ $corConfig['color'] }};font-size:.9rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $atividade->titulo_atividade }}</div>
                                        <div style="font-size:.7rem;color:#94a3b8;">{{ $atividade->curso?->nome_curso ?? '' }}</div>
                                    </div>
                                </div>
                                <span class="tbl-status tbl-status-{{ $status === 'CORRIGIDA' ? 'confirmado' : ($status === 'ENVIADA' ? 'pendente' : 'congelado') }}">
                                    <span class="tbl-status-dot"></span> {{ $corConfig['label'] }}
                                </span>
                            </div>

                            {{-- Descrição --}}
                            @if($atividade->descricao_atividade)
                                <p style="font-size:.78rem;color:#64748b;line-height:1.5;margin-bottom:.75rem;">
                                    {{ Str::limit($atividade->descricao_atividade, 100) }}
                                </p>
                            @endif

                            {{-- Data de entrega --}}
                            <div style="font-size:.75rem;color:#94a3b8;margin-bottom:.75rem;">
                                <i class="fas fa-calendar-alt me-1" style="color:#6366f1;"></i>
                                Entrega: <strong style="color:#1e293b;">{{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}</strong>
                            </div>

                            {{-- Nota (se corrigida) --}}
                            @if($status === 'CORRIGIDA' && $resposta)
                                <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:10px;padding:.75rem;margin-bottom:.75rem;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#059669;margin-bottom:.15rem;">Sua Nota</div>
                                            <div style="font-size:1.4rem;font-weight:900;color:#059669;">{{ $resposta->nota }}<span style="font-size:.8rem;font-weight:500;">/10</span></div>
                                        </div>
                                        @php
                                            $notaCor = $resposta->nota >= 7 ? '#10b981' : ($resposta->nota >= 5 ? '#f59e0b' : '#ef4444');
                                            $notaPct = min(100, $resposta->nota * 10);
                                        @endphp
                                        <div style="width:50px;height:50px;border-radius:50%;background:conic-gradient({{ $notaCor }} {{ $notaPct }}%, #e2e8f0 0);display:flex;align-items:center;justify-content:center;">
                                            <div style="width:38px;height:38px;border-radius:50%;background:#ecfdf5;display:flex;align-items:center;justify-content:center;">
                                                <i class="fas fa-star" style="color:{{ $notaCor }};font-size:.7rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @if($resposta->feedback_professor)
                                        <div style="font-size:.73rem;color:#065f46;margin-top:.5rem;padding-top:.5rem;border-top:1px solid rgba(16,185,129,.2);">
                                            <i class="fas fa-comment-dots me-1"></i> {{ Str::limit($resposta->feedback_professor, 80) }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Botão --}}
                            @if($status !== 'ENVIADA' && $status !== 'CORRIGIDA')
                                <a href="{{ route('aluno.atividades.show', $atividade->id_atividade) }}" class="tbl-btn-success w-100 justify-content-center">
                                    <i class="fas fa-pen-to-square"></i> Fazer Atividade
                                </a>
                            @else
                                <a href="{{ route('aluno.atividades.show', $atividade->id_atividade) }}" class="tbl-btn-ver w-100 justify-content-center">
                                    <i class="fas fa-eye"></i> Ver Resposta
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 fade-up">
                    <div class="tbl-empty">
                        <i class="fas fa-clipboard-list tbl-empty-icon"></i>
                        <span class="tbl-empty-text">Nenhuma atividade disponível ainda.</span>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection
