@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">{{ $duvida->assunto_duvida }}</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.duvidas.index') }}">Dúvidas dos Alunos</a></li>
                        <li class="breadcrumb-item active">Dúvida</li>
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

            {{-- MENSAGEM DO ALUNO --}}
            <div class="d-card fade-up mb-4">
                <div class="d-card-header">
                    <h6><i class="fas fa-envelope text-primary"></i> Dúvida do Aluno</h6>
                    <form action="{{ route('admin.duvidas.destroy', $duvida->id_duvida) }}" method="POST" class="d-inline form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="tbl-btn-excluir"
                            data-titulo="Dúvida de {{ $duvida->aluno->nome_aluno ?? 'aluno' }}"
                            onclick="abrirModalExcluir(this)">
                            <i class="fas fa-trash-alt"></i> Excluir
                        </button>
                    </form>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $duvida->aluno->nome_aluno ?? '—' }}</div>
                            <div style="font-size:.72rem;color:#94a3b8;">{{ $duvida->criado_em?->format('d/m/Y H:i') }}</div>
                        </div>
                        @if($duvida->status_duvida === 'respondida')
                            <span class="tbl-badge">Respondida</span>
                        @else
                            <span class="tbl-badge amber">Pendente</span>
                        @endif
                    </div>

                    <p style="font-size:.88rem;color:#334155;line-height:1.7;white-space:pre-line;">{{ $duvida->mensagem_duvida }}</p>
                </div>
            </div>

            {{-- RESPOSTA DO PROFESSOR --}}
            <div class="d-card fade-up mb-5">
                <div class="d-card-header">
                    <h6><i class="fas fa-reply text-success"></i> Resposta</h6>
                </div>
                <div class="card-body p-3">
                    @if($duvida->status_duvida === 'respondida')
                        <div style="padding:1rem;border-radius:12px;border:1.5px solid #f1f5f9;background:#fafbfc;">
                            <div style="font-size:.72rem;color:#94a3b8;margin-bottom:.4rem;">
                                Respondido em {{ $duvida->respondido_em?->format('d/m/Y H:i') }}
                            </div>
                            <div style="font-size:.85rem;color:#475569;line-height:1.6;white-space:pre-line;">{{ $duvida->resposta_professor }}</div>
                        </div>
                    @else
                        <form action="{{ route('admin.duvidas.responder', $duvida->id_duvida) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <textarea name="resposta_professor" rows="5"
                                      class="form-control @error('resposta_professor') is-invalid @enderror"
                                      placeholder="Escreva a resposta para o aluno...">{{ old('resposta_professor') }}</textarea>
                            @error('resposta_professor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="tbl-btn-success">
                                    <i class="fas fa-paper-plane"></i> Enviar Resposta
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Dúvida', 'delDescricao' => 'Você está prestes a excluir a dúvida:'])

@endsection
