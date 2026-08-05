@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Mensagem de Contato</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.mensagens.index') }}">Mensagens</a></li>
                    <li class="breadcrumb-item active">{{ $mensagem->assunto }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-envelope-open-text text-primary"></i> {{ $mensagem->assunto }}</h6>
                <a href="{{ route('admin.mensagens.index') }}" class="tbl-btn-novo">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="p-4">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nome</small>
                        <span class="fw-semibold">{{ $mensagem->nome }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">E-mail</small>
                        <a href="mailto:{{ $mensagem->email }}" class="fw-semibold">{{ $mensagem->email }}</a>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Recebida em</small>
                    <span>{{ $mensagem->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block mb-1">Mensagem</small>
                    <p style="white-space:pre-line;line-height:1.6;">{{ $mensagem->mensagem }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="mailto:{{ $mensagem->email }}?subject=Re: {{ $mensagem->assunto }}" class="tbl-btn-success">
                        <i class="fas fa-reply"></i> Responder por e-mail
                    </a>
                    <form action="{{ route('admin.mensagens.destroy', $mensagem) }}" method="POST" class="d-inline form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="tbl-btn-excluir" style="background:#fee2e2;color:#dc2626;box-shadow:none;"
                            data-nome="{{ $mensagem->nome }}"
                            onclick="abrirModalExcluir(this)">
                            <i class="fas fa-trash"></i> Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Mensagem', 'delDescricao' => 'Você está prestes a excluir a mensagem de:'])

@endsection
