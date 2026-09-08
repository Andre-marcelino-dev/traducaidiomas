@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Minhas Dúvidas</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Minhas Dúvidas</li>
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

        <div class="d-card fade-up mb-3">
            <div class="d-card-header">
                <h6><i class="fas fa-envelope text-primary"></i> Dúvidas Enviadas</h6>
                <a href="{{ route('aluno.duvidas.create') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Tirar Dúvida
                </a>
            </div>
        </div>

        <div class="row g-3">
            @forelse($duvidas as $duvida)
                <div class="col-md-6 fade-up">
                    <div class="d-card h-100" style="border-top:3px solid #6366f1;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $duvida->assunto_duvida }}</div>
                                @if($duvida->status_duvida === 'respondida')
                                    <span class="tbl-badge">Respondida</span>
                                @else
                                    <span class="tbl-badge amber">Pendente</span>
                                @endif
                            </div>

                            <p style="font-size:.78rem;color:#64748b;line-height:1.5;margin-bottom:.5rem;">
                                {{ Str::limit($duvida->mensagem_duvida, 140) }}
                            </p>

                            @if($duvida->status_duvida === 'respondida')
                                <div style="background:#f8fafc;border-left:3px solid #22c55e;padding:.6rem .75rem;border-radius:8px;">
                                    <div style="font-size:.7rem;font-weight:700;color:#16a34a;margin-bottom:.2rem;">
                                        <i class="fas fa-reply me-1"></i>Resposta do professor
                                    </div>
                                    <div style="font-size:.78rem;color:#475569;line-height:1.5;">
                                        {{ Str::limit($duvida->resposta_professor, 160) }}
                                    </div>
                                </div>
                            @endif

                            <div style="font-size:.72rem;color:#94a3b8;margin-top:.6rem;">
                                Enviado em {{ $duvida->criado_em?->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 fade-up">
                    <div class="tbl-empty">
                        <i class="fas fa-envelope tbl-empty-icon"></i>
                        <span class="tbl-empty-text">Você ainda não enviou nenhuma dúvida.</span>
                        <a href="{{ route('aluno.duvidas.create') }}" class="tbl-empty-btn">
                            <i class="fas fa-plus"></i> Tirar dúvida com o professor
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($duvidas->hasPages())
            <div class="mt-4">
                {{ $duvidas->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
