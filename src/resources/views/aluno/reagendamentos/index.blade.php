@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Meus Reagendamentos</h3>
            </div>
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
                <h6><i class="fas fa-calendar-alt text-warning"></i> Solicitações de Reagendamento</h6>
                <a href="{{ route('aluno.dash') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Nova Solicitação
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
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="#FFFFFF" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z" fill="#FFFFFF" />
                                    </svg>
                                    <span class="tbl-empty-text">Nenhuma solicitação de reagendamento.</span>
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