@extends('aluno.layout.aluno')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Material de Estudo</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('aluno.materiais.index') }}">Materiais</a></li>
                        <li class="breadcrumb-item active">Detalhes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card recent-card shadow-sm border-0" style="border-radius:14px;">

                        {{-- Banner topo --}}
                        <div style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:14px 14px 0 0;padding:2rem;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-file-alt text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-white fw-bold mb-1">{{ $materiais->titulo_materiais }}</h4>
                                    <span style="color:rgba(255,255,255,.75);font-size:.8rem;">
                                        <i class="fas fa-chalkboard-user me-1"></i>
                                        {{ $materiais->professor->nome_professor ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            {{-- Badges --}}
                            <div class="d-flex gap-2 flex-wrap mb-4">
                                @if($materiais->nivel_material)
                                    @php
                                        $cor = match(strtolower($materiais->nivel_material)) {
                                            'básico','basico'               => 'danger',
                                            'intermediário','intermediario' => 'warning',
                                            'avançado','avancado'           => 'success',
                                            default                         => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $cor }}-subtle text-{{ $cor }} border border-{{ $cor }}-subtle px-3 py-2">
                                        <i class="fas fa-signal me-1"></i>{{ $materiais->nivel_material }}
                                    </span>
                                @endif
                                @if($materiais->curso)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                        <i class="fas fa-book me-1"></i>{{ $materiais->curso->nome_curso }}
                                    </span>
                                @endif
                            </div>

                            {{-- Descrição --}}
                            @if($materiais->descricao_materiais)
                                <div class="mb-4">
                                    <p class="text-muted fw-semibold mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Descrição</p>
                                    <p class="mb-0" style="line-height:1.7;">{{ $materiais->descricao_materiais }}</p>
                                </div>
                            @endif

                            {{-- Arquivo --}}
                            @if($materiais->arquivo_materiais)
                                <div class="p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-arrow-down text-primary fa-lg"></i>
                                            <div>
                                                <p class="mb-0 fw-semibold" style="font-size:.875rem;">Arquivo disponível</p>
                                                <p class="mb-0 text-muted" style="font-size:.72rem;">Clique para baixar o material</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('aluno.materiais.download', $materiais->id_materiais) }}"
                                           class="btn btn-primary btn-sm px-4">
                                            <i class="fas fa-download me-1"></i> Baixar
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 rounded text-center text-muted" style="background:#f8fafc;border:1px dashed #e2e8f0;">
                                    <i class="fas fa-folder-open mb-1 d-block opacity-50"></i>
                                    <span style="font-size:.8rem;">Nenhum arquivo anexado a este material.</span>
                                </div>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('aluno.materiais.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Voltar para materiais
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
