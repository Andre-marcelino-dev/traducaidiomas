@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Atividades</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
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

        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-clipboard-list text-primary"></i> Lista de Atividades</h6>
                <a href="{{ route('admin.atividades.create') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Nova Atividade
                </a>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Curso</th>
                            <th>Entrega</th>
                            <th>Respostas</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atividades as $atividade)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="tbl-icon-wrap"><i class="fas fa-clipboard-list"></i></div>
                                    <span style="font-weight:600;font-size:.875rem;">{{ $atividade->titulo_atividade }}</span>
                                </div>
                            </td>
                            <td><span class="tbl-badge">{{ $atividade->curso?->nome_curso ?? '—' }}</span></td>
                            <td>
                                <div style="font-weight:600;font-size:.84rem;">
                                    <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                    {{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td><span class="tbl-badge blue">{{ $atividade->respostas->count() }} enviadas</span></td>
                            <td>
                                @php
                                    $st = strtolower($atividade->status_atividade ?? '');
                                    $stClass = match(true) {
                                        str_contains($st, 'ativ') || str_contains($st, 'abert') => 'tbl-status-ativo',
                                        str_contains($st, 'inativ') || str_contains($st, 'fechad') => 'tbl-status-inativo',
                                        str_contains($st, 'cancel') => 'tbl-status-cancelado',
                                        str_contains($st, 'conclu') => 'tbl-status-concluido',
                                        default => 'tbl-status-ativo',
                                    };
                                @endphp
                                <span class="tbl-status {{ $stClass }}">
                                    <span class="tbl-status-dot"></span>
                                    {{ $atividade->status_atividade }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.atividades.show', $atividade->id_atividade) }}" class="tbl-btn-ver">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <form action="{{ route('admin.atividades.destroy', $atividade->id_atividade) }}" method="POST" class="d-inline form-delete">
                                        @csrf @method('DELETE')
                                        <button type="button" class="tbl-btn-excluir"
                                            data-titulo="{{ $atividade->titulo_atividade }}"
                                            onclick="abrirModalExcluir(this)">
                                            <i class="fas fa-trash-alt"></i> Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="tbl-empty">
                                    <i class="fas fa-clipboard-list tbl-empty-icon"></i>
                                    <span class="tbl-empty-text">Nenhuma atividade cadastrada.</span>
                                    <a href="{{ route('admin.atividades.create') }}" class="tbl-empty-btn">
                                        <i class="fas fa-plus"></i> Cadastrar atividade
                                    </a>
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

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Atividade', 'delDescricao' => 'Você está prestes a excluir a atividade:'])

@endsection
