@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Fórum</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Fórum</li>
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

        {{-- METRIC CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-4 fade-up">
                <div class="mc mc-blue shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-comments"></i></div>
                    <div class="mc-val">{{ $topicos->total() }}</div>
                    <p class="mc-lbl">Tópicos</p>
                    <div class="mc-trend"><i class="fas fa-message me-1"></i> nos seus cursos</div>
                </div>
            </div>
            <div class="col-sm-4 fade-up">
                <div class="mc mc-green shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-reply-all"></i></div>
                    <div class="mc-val">{{ $topicos->sum(fn($t) => $t->respostas->count()) }}</div>
                    <p class="mc-lbl">Respostas</p>
                    <div class="mc-trend"><i class="fas fa-users me-1"></i> trocadas entre alunos</div>
                </div>
            </div>
            <div class="col-sm-4 fade-up">
                <div class="mc mc-amber shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="mc-val">{{ $cursos->count() }}</div>
                    <p class="mc-lbl">Seus Cursos</p>
                    <div class="mc-trend"><i class="fas fa-book me-1"></i> matriculado(a)</div>
                </div>
            </div>
        </div>

        {{-- FILTROS + NOVO TÓPICO --}}
        <div class="d-card fade-up mb-3">
            <div class="d-card-header">
                <h6><i class="fas fa-comments text-primary"></i> Tópicos do Fórum</h6>
                <a href="{{ route('aluno.forum.create') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Novo Tópico
                </a>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('aluno.forum.index') }}" method="GET" class="row g-2">
                    <div class="col-sm-5">
                        <select name="id_curso" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Todos os cursos</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id_curso }}" {{ request('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                    {{ $curso->nome_curso }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="busca" class="form-control form-control-sm" placeholder="Buscar por título..." value="{{ request('busca') }}">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-magnifying-glass"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TÓPICOS --}}
        <div class="row g-3">
            @forelse($topicos as $topico)
                <div class="col-md-6 col-xl-4 fade-up">
                    <div class="d-card h-100" style="border-top:3px solid #6366f1;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:38px;height:38px;border-radius:10px;background:#eef3ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-comment-dots" style="color:#4f46e5;font-size:.9rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $topico->titulo_topico }}</div>
                                        <div style="font-size:.7rem;color:#94a3b8;">{{ $topico->curso?->nome_curso ?? '' }}</div>
                                    </div>
                                </div>
                                <span class="tbl-badge blue">
                                    <i class="fas fa-reply me-1"></i>{{ $topico->respostas->count() }}
                                </span>
                            </div>

                            <p style="font-size:.78rem;color:#64748b;line-height:1.5;margin-bottom:.75rem;">
                                {{ Str::limit($topico->descricao_topico, 100) }}
                            </p>

                            <div style="font-size:.75rem;color:#94a3b8;margin-bottom:.75rem;">
                                <i class="fas fa-user me-1" style="color:#6366f1;"></i>
                                {{ $topico->aluno->nome_aluno ?? 'Aluno' }}
                                &nbsp;·&nbsp;
                                {{ $topico->criado_em?->format('d/m/Y H:i') }}
                            </div>

                            <a href="{{ route('aluno.forum.show', $topico->id_topico) }}" class="tbl-btn-ver w-100 justify-content-center">
                                <i class="fas fa-eye"></i> Ver Tópico
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 fade-up">
                    <div class="tbl-empty">
                        <i class="fas fa-comments tbl-empty-icon"></i>
                        <span class="tbl-empty-text">Nenhum tópico criado ainda.</span>
                        <a href="{{ route('aluno.forum.create') }}" class="tbl-empty-btn">
                            <i class="fas fa-plus"></i> Criar primeiro tópico
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($topicos->hasPages())
            <div class="mt-4">
                {{ $topicos->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
