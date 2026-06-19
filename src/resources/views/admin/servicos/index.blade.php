@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Serviços</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Serviços</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-concierge-bell text-primary"></i> Lista de Serviços</h6>
                <a href="{{ route('admin.servicos.create') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Novo Serviço
                </a>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Título</th>
                            <th>Língua</th>
                            <th>Preço</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicos as $servico)
                            <tr>
                                <td>
                                    @if ($servico->imagem_servico)
                                        <img src="{{ asset($servico->imagem_servico) }}"
                                            alt="{{ $servico->titulo_servico }}" width="60" height="60"
                                            style="object-fit:cover;border-radius:10px;border:2px solid #e2e8f0;">
                                    @else
                                        <span style="color:#cbd5e1;font-size:.8rem;">Sem imagem</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:600;font-size:.875rem;">{{ $servico->titulo_servico }}</div>
                                    @if($servico->subtitulo_servico)
                                        <div style="font-size:.72rem;color:#94a3b8;">{{ $servico->subtitulo_servico }}</div>
                                    @endif
                                </td>
                                <td><span class="tbl-badge"><i class="fas fa-language me-1"></i>{{ $servico->lingua_servico }}</span></td>
                                <td><span style="font-weight:700;font-size:.875rem;">{{ $servico->preco_servico }}</span></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('admin.servicos.edit', $servico->id_servico) }}" class="tbl-btn-editar">
                                            <i class="fas fa-pen-to-square"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.servicos.destroy', $servico->id_servico) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="tbl-btn-excluir"
                                                data-titulo="{{ $servico->titulo_servico }}"
                                                onclick="abrirModalExcluir(this)">
                                                <i class="fas fa-trash-alt"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="tbl-empty">
                                        <i class="fas fa-concierge-bell tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhum serviço cadastrado ainda</span>
                                        <a href="{{ route('admin.servicos.create') }}" class="tbl-empty-btn">
                                            <i class="fas fa-plus"></i> Cadastrar primeiro serviço
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

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Serviço', 'delDescricao' => 'Você está prestes a excluir o serviço:'])

@endsection
