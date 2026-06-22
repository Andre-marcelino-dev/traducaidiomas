@extends('admin.layout.admin')

@section('content')

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">Alunos</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Alunos</li>
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

            {{-- METRIC CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="mc-val">{{ $alunos->count() }}</div>
                        <p class="mc-lbl">Total de Alunos</p>
                        <div class="mc-trend"><i class="fas fa-arrow-trend-up me-1"></i>cadastrados</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green shadow">
                        <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                        <div class="mc-val">{{ $alunos->where('status_aluno', 'EM CURSO')->count() }}</div>
                        <p class="mc-lbl">Em Curso</p>
                        <div class="mc-trend"><i class="fas fa-book-open me-1"></i>estudando</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="mc-val">{{ $alunos->where('status_aluno', 'CONCLUIDO')->count() }}</div>
                        <p class="mc-lbl">Concluídos</p>
                        <div class="mc-trend"><i class="fas fa-medal me-1"></i>curso finalizado</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-user-xmark"></i></div>
                        <div class="mc-val">{{ $alunos->where('status_aluno', 'INATIVO')->count() }}</div>
                        <p class="mc-lbl">Inativos</p>
                        <div class="mc-trend"><i class="fas fa-clock me-1"></i>sem atividade</div>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="d-card fade-up">
                <div class="d-card-header">
                    <h6><i class="fas fa-user-graduate text-primary"></i> Lista de Alunos</h6>
                    <a href="{{ route('admin.alunos.create') }}" class="tbl-btn-novo">
                        <i class="fas fa-plus"></i> Novo Aluno
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Telefone</th>
                                <th>Curso</th>
                                <th>Nível</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alunos as $aluno)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($aluno->foto_aluno)
                                                <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                                    alt="{{ $aluno->nome_aluno }}" class="prof-avatar">
                                            @else
                                                <div class="prof-avatar-placeholder">{{ strtoupper(mb_substr($aluno->nome_aluno, 0, 2)) }}</div>
                                            @endif
                                            <div>
                                                <div style="font-weight:600;font-size:.875rem;">{{ $aluno->nome_aluno }}</div>
                                                <div style="font-size:.72rem;color:#94a3b8;">{{ $aluno->email_aluno }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $aluno->telefone_aluno ?? '—' }}</td>
                                    <td><span class="tbl-badge">{{ $aluno->curso_aluno ?? '—' }}</span></td>
                                    <td>{{ $aluno->nivel_aluno ?? '—' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($aluno->status_aluno) {
                                                'EM CURSO'  => 'tbl-status-emcurso',
                                                'CONCLUIDO' => 'tbl-status-concluido',
                                                'INATIVO'   => 'tbl-status-inativo',
                                                default     => 'tbl-status-pendente',
                                            };
                                        @endphp
                                        <form action="{{ route('admin.alunos.updateStatus', $aluno->id_aluno) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="tbl-status {{ $statusClass }}" style="position:relative;">
                                                <span class="tbl-status-dot"></span>
                                                <select name="status_aluno" onchange="this.form.submit()"
                                                    style="all:unset;cursor:pointer;font:inherit;color:inherit;background:transparent;letter-spacing:inherit;text-transform:inherit;">
                                                    <option value="EM CURSO" {{ $aluno->status_aluno == 'EM CURSO' ? 'selected' : '' }}>EM CURSO</option>
                                                    <option value="CONCLUIDO" {{ $aluno->status_aluno == 'CONCLUIDO' ? 'selected' : '' }}>CONCLUÍDO</option>
                                                    <option value="INATIVO" {{ $aluno->status_aluno == 'INATIVO' ? 'selected' : '' }}>INATIVO</option>
                                                </select>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('admin.alunos.edit', $aluno->id_aluno) }}" class="tbl-btn-editar">
                                                <i class="fas fa-pen-to-square"></i> Editar
                                            </a>
                                            <form action="{{ route('admin.alunos.destroy', $aluno->id_aluno) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="tbl-btn-excluir"
                                                    data-nome="{{ $aluno->nome_aluno }}"
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
                                            <i class="fas fa-user-graduate tbl-empty-icon"></i>
                                            <span class="tbl-empty-text">Nenhum aluno cadastrado ainda.</span>
                                            <a href="{{ route('admin.alunos.create') }}" class="tbl-empty-btn">
                                                <i class="fas fa-plus"></i> Cadastrar Aluno
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Aluno', 'delDescricao' => 'Você está prestes a excluir o aluno:'])

@endsection
