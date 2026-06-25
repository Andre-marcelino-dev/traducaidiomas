@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Meus Reagendamentos</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reagendamentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid aluno-dashboard">

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-clock"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'pendente')->count() }}</div>
                    <p class="mc-lbl">Pendentes</p>
                    <div class="mc-trend"><i class="fas fa-hourglass-half me-1"></i> aguardando resposta</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'confirmado')->count() }}</div>
                    <p class="mc-lbl">Confirmados</p>
                    <div class="mc-trend"><i class="fas fa-check me-1"></i> aprovados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-circle-xmark"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'recusado')->count() }}</div>
                    <p class="mc-lbl">Recusados</p>
                    <div class="mc-trend"><i class="fas fa-ban me-1"></i> negados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-calendar-days"></i></div>
                    <div class="mc-val">{{ $reagendamentos->count() }}</div>
                    <p class="mc-lbl">Total</p>
                    <div class="mc-trend"><i class="fas fa-database me-1"></i> registrados</div>
                </div>
            </div>
        </div>

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

        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-calendar-alt text-warning"></i> Solicita??es de Reagendamento</h6>
                <a href="{{ route('aluno.dash') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Nova Solicita??o
                </a>
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
                        @forelse($reagendamentos as $reagendamento)
                            <tr>
                                <td>
                                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;">
                                        {{ $reagendamento->aula?->titulo_aulas ?? 'Aula nao encontrada' }}
                                    </div>
                                    <div style="font-size:.74rem;color:#94a3b8;">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        @if($reagendamento->aula?->data_aulas)
                                            {{ \Carbon\Carbon::parse($reagendamento->aula->data_aulas)->format('d/m/Y') }}
                                            @if($reagendamento->aula->hora_aulas)
                                                &agrave;s {{ \Carbon\Carbon::parse($reagendamento->aula->hora_aulas)->format('H:i') }}
                                            @endif
                                        @else
                                            Sem data
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0" style="font-size:.82rem;color:#64748b;max-width:360px;line-height:1.45;">
                                        {{ Str::limit($reagendamento->motivo, 120) }}
                                    </p>
                                </td>
                                <td>
                                    <span style="font-size:.82rem;color:#64748b;">
                                        @if($reagendamento->data_nova)
                                            {{ \Carbon\Carbon::parse($reagendamento->data_nova)->format('d/m/Y') }} &agrave;s {{ \Carbon\Carbon::parse($reagendamento->data_nova)->format('H:i') }}
                                        @else
                                            A confirmar
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($reagendamento->status === 'pendente')
                                        <span class="tbl-status tbl-status-pendente"><span class="tbl-status-dot"></span> Pendente</span>
                                    @elseif ($reagendamento->status === 'confirmado')
                                        <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Confirmado</span>
                                    @else
                                        <span class="tbl-status tbl-status-cancelado"><span class="tbl-status-dot"></span> Recusado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="tbl-empty">
                                        <i class="fas fa-calendar-check tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhuma solicita??o de reagendamento.</span>
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

@endsection