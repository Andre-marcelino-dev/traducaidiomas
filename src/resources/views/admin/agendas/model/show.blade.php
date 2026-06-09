@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Detalhes do Agendamento</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.agendas.index') }}">Agendamentos</a></li>
                        <li class="breadcrumb-item active">Detalhes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row fade-up">
                <div class="col-12">
                    <div class="card recent-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>{{ $agenda->titulo_agenda }}</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.agendas.edit', $agenda->id_agenda) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pen me-1"></i> Editar
                                </a>
                                <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Voltar
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-4">

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Aluno</p>
                                        <p class="mb-0 fw-semibold">{{ $agenda->aluno->nome ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Professor</p>
                                        <p class="mb-0 fw-semibold">{{ $agenda->professor->nome_professor ?? '—' }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Data do Evento</p>
                                        <p class="mb-0 fw-semibold">{{ \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Horário</p>
                                        <p class="mb-0 fw-semibold">
                                            {{ \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i') }}
                                            –
                                            {{ \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Status</p>
                                        @php
                                            $status = strtolower($agenda->status_agenda ?? '');
                                            $config = match (true) {
                                                str_contains($status, 'confirmado') => ['cor' => '#22c55e', 'label' => 'Confirmado'],
                                                str_contains($status, 'pendente')   => ['cor' => '#f59e0b', 'label' => 'Pendente'],
                                                str_contains($status, 'cancelado')  => ['cor' => '#ef4444', 'label' => 'Cancelado'],
                                                str_contains($status, 'reagend')    => ['cor' => '#6366f1', 'label' => 'Reagendamento'],
                                                default => ['cor' => '#94a3b8', 'label' => $agenda->status_agenda ?? '—'],
                                            };
                                        @endphp
                                        <span style="background:{{ $config['cor'] }}20; color:{{ $config['cor'] }}; padding:4px 12px; border-radius:99px; font-size:.8rem; font-weight:600;">
                                            {{ $config['label'] }}
                                        </span>
                                    </div>
                                </div>

                                @if($agenda->descricao_agenda)
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Descrição</p>
                                        <p class="mb-0">{{ $agenda->descricao_agenda }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($agenda->link_aula_agenda)
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Link da Aula</p>
                                        <a href="{{ $agenda->link_aula_agenda }}" target="_blank">
                                            <i class="fas fa-link me-1"></i> {{ $agenda->link_aula_agenda }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Solicitação de Reagendamento</p>
                                        <p class="mb-0 fw-semibold">
                                            @if($agenda->solicitacao_reagendamento)
                                                <span class="text-warning"><i class="fas fa-circle-exclamation me-1"></i>Sim</span>
                                            @else
                                                <span class="text-muted">Não</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                                        <p class="mb-1" style="font-size:.72rem; color:#94a3b8; font-weight:600; text-transform:uppercase;">Criado em</p>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($agenda->criado_em_agenda)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection