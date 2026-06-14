@extends('aluno.layout.aluno')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Minhas Atividades</h3></div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#0d6efd,#0a58ca)">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $atividades->count() }}</h2>
                        <p class="mb-0">Total de Atividades</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#fd7e14,#e65c00)">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $pendentes }}</h2>
                        <p class="mb-0">Pendentes</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#28a745,#1e7e34)">
                    <div class="card-body text-center">
                        <h2 class="fw-bold">{{ $concluidas }}</h2>
                        <p class="mb-0">Concluídas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            @forelse($atividades as $atividade)
            @php
                $resposta = $atividade->respostas->first();
                $status = $resposta ? $resposta->status_resposta : 'PENDENTE';
            @endphp
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0">{{ $atividade->titulo_atividade }}</h5>
                            @if($status === 'CORRIGIDA')
                                <span class="badge bg-success">✅ Corrigida</span>
                            @elseif($status === 'ENVIADA')
                                <span class="badge bg-warning text-dark">⏳ Enviada</span>
                            @else
                                <span class="badge bg-danger">📌 Pendente</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">{{ $atividade->descricao_atividade }}</p>
                        <p class="small mb-3">
                            <i class="fas fa-calendar me-1"></i>
                            Entrega: {{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}
                        </p>
                        @if($status === 'CORRIGIDA')
                            <div class="alert alert-success py-2 mb-2">
                                <strong>Nota: {{ $resposta->nota }}</strong><br>
                                <small>{{ $resposta->feedback_professor }}</small>
                            </div>
                        @endif
                        @if($status !== 'ENVIADA' && $status !== 'CORRIGIDA')
                            <a href="{{ route('aluno.atividades.show', $atividade->id_atividade) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt me-1"></i> Fazer Atividade
                            </a>
                        @else
                            <a href="{{ route('aluno.atividades.show', $atividade->id_atividade) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-eye me-1"></i> Ver Resposta
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="fas fa-clipboard fa-3x mb-3"></i>
                <p>Nenhuma atividade disponível ainda.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
