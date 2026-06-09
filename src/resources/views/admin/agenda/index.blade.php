@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

{{-- HEADER --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Agendamentos</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Agendamentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow">
                    <div class="mc-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="mc-val">{{ $totalAgendamentos }}</div>
                    <p class="mc-lbl">Total de Agendamentos</p>
                    <div class="mc-trend"><i class="fas fa-calendar me-1"></i>cadastrados no sistema</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow">
                    <div class="mc-icon"><i class="fas fa-clock"></i></div>
                    <div class="mc-val">{{ $agendamentosPendentes }}</div>
                    <p class="mc-lbl">Pendentes</p>
                    <div class="mc-trend"><i class="fas fa-hourglass-half me-1"></i>aguardando confirmação</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow">
                    <div class="mc-icon"><i class="fas fa-calendar-xmark"></i></div>
                    <div class="mc-val">{{ $reagendamentos }}</div>
                    <p class="mc-lbl">Reagendamentos</p>
                    <div class="mc-trend"><i class="fas fa-rotate me-1"></i>solicitados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow">
                    <div class="mc-icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="mc-val">{{ $agendamentosHoje }}</div>
                    <p class="mc-lbl">Hoje</p>
                    <div class="mc-trend"><i class="fas fa-sun me-1"></i>agendamentos para hoje</div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- TABELA --}}
<div class="row fade-up">
    <div class="col-12">
        <div class="card recent-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>Lista de Agendamentos</h5>
                <a href="{{ route('admin.agendas.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    <span class="d-none d-sm-inline" style="font-weight:600; color: white;">Novo Agendamento</span>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Professor</th>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendas as $agenda)
                        <tr>
                            {{-- 1. Nome do Aluno --}}
                            <td>
                                <div style="font-weight:600;font-size:.875rem;">
                                    {{ $agenda->aluno->nome_aluno ?? '—' }}
                                </div>
                            </td>

                            {{-- 2. Nome do Professor --}}
                            <td>
                                <div style="font-size:.875rem;">
                                    {{ $agenda->professor->nome_professor ?? '—' }}
                                </div>
                            </td>

                            {{-- 3. Título --}}
                            <td>{{ $agenda->titulo_agenda }}</td>

                            {{-- 4. Data Formatada --}}
                            <td>{{ \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y') }}</td>

                            {{-- 5. Horário --}}
                            <td>
                                {{ \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i') }}
                            </td>

                            {{-- 6. STATUS DINÂMICO CORES (AQUI ESTÁ A CORREÇÃO) --}}
                            <td>
                                @php
                                $status = strtolower($agenda->status_agenda ?? '');
                                $config = match (true) {
                                str_contains($status, 'confirmado') => ['cor' => '#22c55e', 'label' => 'Confirmado'],
                                str_contains($status, 'pendente') => ['cor' => '#f59e0b', 'label' => 'Pendente'],
                                str_contains($status, 'cancelado') => ['cor' => '#ef4444', 'label' => 'Cancelado'],
                                str_contains($status, 'reagend') => ['cor' => '#6366f1', 'label' => 'Reagendamento'],
                                default => ['cor' => '#94a3b8', 'label' => $agenda->status_agenda ?? '—'],
                                };
                                @endphp
                                <span style="background:{{ $config['cor'] }}20; color:{{ $config['cor'] }}; padding:4px 12px; border-radius:99px; font-size:.75rem; font-weight:600; display:inline-block;">
                                    {{ $config['label'] }}
                                </span>
                            </td>

                            {{-- 7. Ações --}}
                            <td class="text-center">
                                <a href="{{ route('admin.agendas.edit', $agenda->id_agenda) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="fas fa-pen fa-xs"></i> Editar
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal" data-bs-target="#modalExcluir"
                                    data-id="{{ $agenda->id_agenda }}"
                                    data-titulo="{{ $agenda->titulo_agenda }}">
                                    <i class="fas fa-trash fa-xs"></i> Excluir
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                Nenhum agendamento cadastrado ainda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Excluir -->
<div class="modal fade" id="modalExcluir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-calendar-x-fill text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3 fs-5">Tem certeza que deseja excluir o agendamento</p>
                <strong id="tituloAgendaModal" class="fs-5"></strong>
                <p class="text-muted mt-2">Esta ação não poderá ser desfeita.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formExcluir" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sim, excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('modalExcluir').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const titulo = button.getAttribute('data-titulo');

        document.getElementById('tituloAgendaModal').textContent = titulo;
        document.getElementById('formExcluir').action = `/admin/agendas/${id}`;
    });
</script>
@endpush
@endsection