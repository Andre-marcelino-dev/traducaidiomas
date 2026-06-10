@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

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
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-blue shadow">
                    <div class="mc-val">{{ $totalAgendamentos ?? 0 }}</div>
                    <p>Total</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-amber shadow">
                    <div class="mc-val">{{ $agendamentosPendentes ?? 0 }}</div>
                    <p>Pendentes</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-rose shadow">
                    <div class="mc-val">{{ $reagendamentos ?? 0 }}</div>
                    <p>Reagendamentos</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-blue shadow">
                    <div class="mc-val">{{ $agendamentosHoje ?? 0 }}</div>
                    <p>Hoje</p>
                </div>
            </div>
        </div>

        {{-- TABELA --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Lista de Agendamentos</h5>

                <a href="{{ route('admin.agendas.create') }}" class="btn btn-success btn-sm">
                    Novo
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Professor</th>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($agendas as $agenda)
                            <tr>
                                <td>{{ $agenda->aluno?->nome_aluno ?? '—' }}</td>

                                <td>{{ $agenda->professor?->nome_professor ?? '—' }}</td>

                                <td>{{ $agenda->titulo_agenda }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i') }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower($agenda->status_agenda ?? '');

                                        $config = match (true) {
                                            str_contains($status, 'confirmado') => ['#22c55e', 'Confirmado'],
                                            str_contains($status, 'pendente') => ['#f59e0b', 'Pendente'],
                                            str_contains($status, 'cancelado') => ['#ef4444', 'Cancelado'],
                                            str_contains($status, 'reagend') => ['#6366f1', 'Reagendamento'],
                                            default => ['#94a3b8', $agenda->status_agenda ?? '—'],
                                        };
                                    @endphp

                                    <span style="background:{{ $config[0] }}20;color:{{ $config[0] }};padding:4px 10px;border-radius:20px;">
                                        {{ $config[1] }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.agendas.edit', $agenda->id_agenda) }}"
                                       class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalExcluir"
                                        data-id="{{ $agenda->id_agenda }}"
                                        data-titulo="{{ $agenda->titulo_agenda }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhum agendamento</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalExcluir">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5>Excluir</h5>
            </div>

            <div class="modal-body text-center">
                <p>Excluir agendamento:</p>
                <strong id="tituloAgendaModal"></strong>
            </div>

            <div class="modal-footer">
                <form id="formExcluir" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Sim</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('modalExcluir').addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const titulo = button.getAttribute('data-titulo');

    document.getElementById('tituloAgendaModal').textContent = titulo;
    document.getElementById('formExcluir').action = `/admin/agendas/${id}`;
});
</script>

@endsection