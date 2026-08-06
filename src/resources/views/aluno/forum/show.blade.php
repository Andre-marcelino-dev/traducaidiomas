@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">{{ $topico->titulo_topico }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('aluno.forum.index') }}">Fórum</a></li>
                    <li class="breadcrumb-item active">Tópico</li>
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="fas fa-triangle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TÓPICO --}}
        <div class="d-card fade-up mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:42px;height:42px;border-radius:12px;background:#eef3ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-user" style="color:#4f46e5;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $topico->aluno->nome_aluno ?? 'Aluno' }}</div>
                            <div style="font-size:.72rem;color:#94a3b8;">{{ $topico->criado_em?->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <span class="tbl-badge">{{ $topico->curso?->nome_curso ?? '' }}</span>
                </div>

                <p style="font-size:.88rem;color:#334155;line-height:1.7;white-space:pre-line;margin-top:1rem;">{{ $topico->descricao_topico }}</p>

                @if($topico->anexo_topico)
                    <a href="{{ route('aluno.forum.download', $topico->id_topico) }}" class="tbl-link-btn">
                        <i class="fas fa-paperclip"></i> Baixar anexo
                    </a>
                @endif
            </div>
        </div>

        {{-- RESPOSTAS --}}
        <div class="d-card fade-up mb-4">
            <div class="d-card-header">
                <h6><i class="fas fa-reply-all text-success"></i> Respostas ({{ $topico->respostas->count() }})</h6>
            </div>
            <div class="card-body p-3">
                @forelse($topico->respostas as $resposta)
                    <div style="padding:1rem;margin-bottom:.75rem;border-radius:12px;border:1.5px solid #f1f5f9;background:#fafbfc;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div style="font-weight:600;font-size:.85rem;color:#1e293b;">
                                <i class="fas fa-user-circle me-1" style="color:#94a3b8;"></i>
                                {{ $resposta->aluno->nome_aluno ?? 'Aluno' }}
                            </div>
                            <div style="font-size:.7rem;color:#94a3b8;">{{ $resposta->criado_em?->format('d/m/Y H:i') }}</div>
                        </div>
                        <div style="font-size:.85rem;color:#475569;line-height:1.6;white-space:pre-line;">{{ $resposta->conteudo_resposta }}</div>
                    </div>
                @empty
                    <div class="tbl-empty">
                        <i class="fas fa-comment-slash tbl-empty-icon"></i>
                        <span class="tbl-empty-text">Seja o primeiro a responder este tópico.</span>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- FORMULÁRIO DE RESPOSTA --}}
        <div class="d-card fade-up mb-5">
            <div class="d-card-header">
                <h6><i class="fas fa-pen text-primary"></i> Responder</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('aluno.forum.responder', $topico->id_topico) }}" method="POST">
                    @csrf
                    <textarea name="conteudo_resposta" class="form-control mb-3" rows="3"
                              placeholder="Escreva sua resposta para ajudar seus colegas..." required></textarea>
                    <button type="submit" class="tbl-btn-success">
                        <i class="fas fa-paper-plane"></i> Enviar Resposta
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
