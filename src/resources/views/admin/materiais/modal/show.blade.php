@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Detalhes do Material</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materiais.index') }}">Materiais</a></li>
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
                    <div class="card recent-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>{{ $materiais->titulo_materiais }}</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.materiais.edit', $materiais->id_materiais) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-pen me-1"></i> Editar
                                </a>
                                <a href="{{ route('admin.materiais.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Voltar
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <p class="text-muted mb-1" style="font-size:.75rem;">PROFESSOR</p>
                                    <p class="fw-semibold mb-0">{{ $materiais->professor->nome_professor ?? '—' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="text-muted mb-1" style="font-size:.75rem;">CURSO</p>
                                    <p class="fw-semibold mb-0">{{ $materiais->curso->nome_curso ?? $materiais->curso_materiais ?? '—' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="text-muted mb-1" style="font-size:.75rem;">NÍVEL</p>
                                    <p class="fw-semibold mb-0">{{ $materiais->nivel_material ?? '—' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p class="text-muted mb-1" style="font-size:.75rem;">CADASTRADO EM</p>
                                    <p class="fw-semibold mb-0">
                                        {{ $materiais->criado_em_materiais ? $materiais->criado_em_materiais->format('d/m/Y H:i') : '—' }}
                                    </p>
                                </div>

                                @if($materiais->descricao_materiais)
                                    <div class="col-12">
                                        <p class="text-muted mb-1" style="font-size:.75rem;">DESCRIÇÃO</p>
                                        <p class="mb-0">{{ $materiais->descricao_materiais }}</p>
                                    </div>
                                @endif

                                @if($materiais->arquivo_materiais)
                                    <div class="col-12">
                                        <p class="text-muted mb-1" style="font-size:.75rem;">ARQUIVO</p>
                                        <a href="{{ asset($materiais->arquivo_materiais) }}" target="_blank"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-download me-1"></i> Baixar arquivo
                                        </a>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
