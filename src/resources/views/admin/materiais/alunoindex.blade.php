@extends('aluno.layout.aluno')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Materiais de Estudo</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Materiais</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- FILTROS --}}
            <div class="card recent-card mb-4 fade-up">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('aluno.materiais.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">Buscar</label>
                            <input type="text" name="busca" class="form-control form-control-sm"
                                   placeholder="Título do material..." value="{{ request('busca') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">Nível</label>
                            <select name="nivel" class="form-select form-select-sm">
                                <option value="">Todos os níveis</option>
                                <option value="Básico"        {{ request('nivel') == 'Básico'        ? 'selected' : '' }}>Básico</option>
                                <option value="Intermediário" {{ request('nivel') == 'Intermediário' ? 'selected' : '' }}>Intermediário</option>
                                <option value="Avançado"      {{ request('nivel') == 'Avançado'      ? 'selected' : '' }}>Avançado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-1" style="font-size:.8rem;">Curso</label>
                            <select name="id_curso" class="form-select form-select-sm">
                                <option value="">Todos os cursos</option>
                                @foreach($materiais->unique('id_curso') as $m)
                                    @if($m->curso)
                                        <option value="{{ $m->id_curso }}" {{ request('id_curso') == $m->id_curso ? 'selected' : '' }}>
                                            {{ $m->curso->nome_curso }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-search me-1"></i> Filtrar
                            </button>
                            <a href="{{ route('aluno.materiais.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-xmark"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CARDS DE MATERIAIS --}}
            <div class="row g-3">
                @forelse($materiais as $material)
                    <div class="col-md-6 col-xl-4 fade-up">
                        <div class="card h-100 shadow-sm border-0" style="border-radius:12px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div style="width:44px;height:44px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-file-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="font-size:.9rem;">{{ $material->titulo_materiais }}</h6>
                                        <span style="font-size:.72rem;color:#94a3b8;">
                                            {{ $material->professor->nome_professor ?? '—' }}
                                        </span>
                                    </div>
                                </div>

                                @if($material->descricao_materiais)
                                    <p class="text-muted mb-3" style="font-size:.8rem;line-height:1.5;">
                                        {{ Str::limit($material->descricao_materiais, 80) }}
                                    </p>
                                @endif

                                <div class="d-flex gap-2 mb-3 flex-wrap">
                                    @if($material->nivel_material)
                                        @php
                                            $cor = match(strtolower($material->nivel_material)) {
                                                'básico','basico'               => 'danger',
                                                'intermediário','intermediario' => 'warning',
                                                'avançado','avancado'           => 'success',
                                                default                         => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $cor }}-subtle text-{{ $cor }} border border-{{ $cor }}-subtle"
                                              style="font-size:.7rem;">
                                            {{ $material->nivel_material }}
                                        </span>
                                    @endif
                                    @if($material->curso)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                              style="font-size:.7rem;">
                                            {{ $material->curso->nome_curso }}
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('aluno.materiais.show', $material->id_materiais) }}"
                                       class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    @if($material->arquivo_materiais)
                                        <a href="{{ route('aluno.materiais.download', $material->id_materiais) }}"
                                           class="btn btn-primary btn-sm flex-fill">
                                            <i class="fas fa-download me-1"></i> Baixar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                        Nenhum material encontrado.
                    </div>
                @endforelse
            </div>

            {{-- PAGINAÇÃO --}}
            @if($materiais->hasPages())
                <div class="mt-4">
                    {{ $materiais->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
