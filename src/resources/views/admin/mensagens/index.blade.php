@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Mensagens de Contato</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Mensagens</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-envelope text-primary"></i> Mensagens Recebidas pelo Site</h6>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Assunto</th>
                            <th class="text-center">Recebida em</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mensagens as $mensagem)
                            <tr>
                                <td style="{{ !$mensagem->lida ? 'font-weight:600;' : '' }}">{{ $mensagem->nome }}</td>
                                <td>{{ $mensagem->email }}</td>
                                <td>{{ $mensagem->assunto }}</td>
                                <td class="text-center">
                                    <div style="font-size:.8rem;">{{ $mensagem->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="text-center">
                                    @if ($mensagem->lida)
                                        <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Lida</span>
                                    @else
                                        <span class="tbl-status tbl-status-pendente"><span class="tbl-status-dot"></span> Não lida</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.mensagens.show', $mensagem) }}" class="tbl-btn-success">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.mensagens.destroy', $mensagem) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="tbl-btn-excluir" style="background:#fee2e2;color:#dc2626;box-shadow:none;"
                                                data-nome="{{ $mensagem->nome }}"
                                                onclick="abrirModalExcluir(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="tbl-empty">
                                        <i class="fas fa-envelope-open tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhuma mensagem recebida.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($mensagens->hasPages())
                <div class="p-3">
                    {{ $mensagens->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Mensagem', 'delDescricao' => 'Você está prestes a excluir a mensagem de:'])

@endsection
