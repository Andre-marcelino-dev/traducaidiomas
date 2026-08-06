@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">{{ $topico->titulo_topico }}</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.forum.index') }}">Fórum</a></li>
                        <li class="breadcrumb-item active">Tópico</li>
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

            {{-- TÓPICO --}}
            <div class="d-card fade-up mb-4">
                <div class="d-card-header">
                    <h6><i class="fas fa-comment text-primary"></i> Tópico</h6>
                    <form action="{{ route('admin.forum.destroy', $topico->id_topico) }}" method="POST" class="d-inline form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="tbl-btn-excluir"
                            data-titulo="{{ $topico->titulo_topico }}"
                            onclick="abrirModalExcluir(this)">
                            <i class="fas fa-trash-alt"></i> Excluir Tópico
                        </button>
                    </form>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $topico->aluno->nome_aluno ?? '—' }}</div>
                            <div style="font-size:.72rem;color:#94a3b8;">{{ $topico->criado_em?->format('d/m/Y H:i') }}</div>
                        </div>
                        <span class="tbl-badge">{{ $topico->curso->nome_curso ?? '—' }}</span>
                    </div>
                    <p style="font-size:.88rem;color:#334155;line-height:1.7;white-space:pre-line;">{{ $topico->descricao_topico }}</p>

                    @if($topico->anexo_topico)
                        <a href="{{ asset($topico->anexo_topico) }}" target="_blank" class="tbl-link-btn">
                            <i class="fas fa-paperclip"></i> Ver anexo
                        </a>
                    @endif
                </div>
            </div>

            {{-- RESPOSTAS --}}
            <div class="d-card fade-up mb-5">
                <div class="d-card-header">
                    <h6><i class="fas fa-reply-all text-success"></i> Respostas ({{ $topico->respostas->count() }})</h6>
                </div>
                <div class="card-body p-3">
                    @forelse($topico->respostas as $resposta)
                        <div class="d-flex align-items-start justify-content-between" style="padding:1rem;margin-bottom:.75rem;border-radius:12px;border:1.5px solid #f1f5f9;background:#fafbfc;">
                            <div>
                                <div style="font-weight:600;font-size:.85rem;color:#1e293b;">
                                    <i class="fas fa-user-circle me-1" style="color:#94a3b8;"></i>
                                    {{ $resposta->aluno->nome_aluno ?? '—' }}
                                    <span style="font-size:.7rem;color:#94a3b8;font-weight:400;">{{ $resposta->criado_em?->format('d/m/Y H:i') }}</span>
                                </div>
                                <div style="font-size:.85rem;color:#475569;line-height:1.6;white-space:pre-line;margin-top:.4rem;">{{ $resposta->conteudo_resposta }}</div>
                            </div>
                            <form action="{{ route('admin.forum.resposta.destroy', $resposta->id_resposta_forum) }}" method="POST" class="d-inline form-delete flex-shrink-0 ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="tbl-btn-excluir"
                                    data-titulo="Resposta de {{ $resposta->aluno->nome_aluno ?? 'aluno' }}"
                                    onclick="abrirModalExcluir(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="tbl-empty">
                            <i class="fas fa-comment-slash tbl-empty-icon"></i>
                            <span class="tbl-empty-text">Nenhuma resposta neste tópico.</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @include('admin.partials.modal-delete', ['delTitulo' => 'Excluir', 'delDescricao' => 'Você está prestes a excluir:'])

@endsection
