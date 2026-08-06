@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">Fórum</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Fórum</li>
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

            {{-- CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-comments"></i></div>
                        <div class="mc-val">{{ $topicos->total() }}</div>
                        <p class="mc-lbl">Total de Tópicos</p>
                        <div class="mc-trend"><i class="fas fa-message me-1"></i>criados</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="mc-val">{{ $topicos->unique('id_curso')->count() }}</div>
                        <p class="mc-lbl">Cursos Envolvidos</p>
                        <div class="mc-trend"><i class="fas fa-book me-1"></i>com atividade</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green shadow">
                        <div class="mc-icon"><i class="fas fa-reply-all"></i></div>
                        <div class="mc-val">{{ $topicos->sum(fn($t) => $t->respostas->count()) }}</div>
                        <p class="mc-lbl">Respostas</p>
                        <div class="mc-trend"><i class="fas fa-users me-1"></i>entre alunos</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- FILTRO --}}
    <div class="row fade-up mb-3">
        <div class="col-12">
            <div class="d-card">
                <div class="card-body p-3">
                    <form action="{{ route('admin.forum.index') }}" method="GET" class="row g-2">
                        <div class="col-sm-4">
                            <select name="id_curso" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todos os cursos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id_curso }}" {{ request('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                        {{ $curso->nome_curso }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="row fade-up">
        <div class="col-12">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-comments text-primary"></i> Tópicos do Fórum</h6>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Curso</th>
                                <th>Autor</th>
                                <th>Respostas</th>
                                <th>Criado em</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topicos as $topico)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;font-size:.875rem;">{{ $topico->titulo_topico }}</div>
                                        <div style="font-size:.72rem;color:#94a3b8;">{{ Str::limit($topico->descricao_topico, 60) }}</div>
                                    </td>
                                    <td><span class="tbl-badge">{{ $topico->curso->nome_curso ?? '—' }}</span></td>
                                    <td>{{ $topico->aluno->nome_aluno ?? '—' }}</td>
                                    <td><span class="tbl-badge blue">{{ $topico->respostas->count() }}</span></td>
                                    <td>{{ $topico->criado_em?->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('admin.forum.show', $topico->id_topico) }}" class="tbl-btn-ver">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <form action="{{ route('admin.forum.destroy', $topico->id_topico) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="tbl-btn-excluir"
                                                    data-titulo="{{ $topico->titulo_topico }}"
                                                    onclick="abrirModalExcluir(this)">
                                                    <i class="fas fa-trash-alt"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tbl-empty">
                                            <i class="fas fa-comments tbl-empty-icon"></i>
                                            <span class="tbl-empty-text">Nenhum tópico criado ainda.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($topicos->hasPages())
                    <div class="card-footer">
                        {{ $topicos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Tópico', 'delDescricao' => 'Você está prestes a excluir o tópico e todas as suas respostas:'])

@endsection
