@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Matrículas</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Matrículas</li>
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
                    <div class="mc-icon"><i class="fas fa-id-card-clip"></i></div>
                    <div class="mc-val">{{ $totalMatriculas ?? 0 }}</div>
                    <p class="mc-lbl">Total de Matrículas</p>
                    <div class="mc-trend"><i class="fas fa-database me-1"></i>cadastradas</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow">
                    <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="mc-val">{{ $alunosAtivos ?? 0 }}</div>
                    <p class="mc-lbl">Alunos Ativos</p>
                    <div class="mc-trend"><i class="fas fa-signal me-1"></i>em andamento</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow">
                    <div class="mc-icon"><i class="fas fa-user-xmark"></i></div>
                    <div class="mc-val">{{ $alunosInativos ?? 0 }}</div>
                    <p class="mc-lbl">Alunos Inativos</p>
                    <div class="mc-trend"><i class="fas fa-circle-xmark me-1"></i>cancelados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc shadow" style="background:linear-gradient(135deg,#fd7e14,#e65c00);">
                    <div class="mc-icon"><i class="fas fa-snowflake"></i></div>
                    <div class="mc-val">{{ $matriculasCongeladas ?? 0 }}</div>
                    <p class="mc-lbl">Congeladas</p>
                    <div class="mc-trend"><i class="fas fa-pause me-1"></i>em pausa</div>
                </div>
            </div>
        </div>

        {{-- FORMULÁRIO --}}
        <div class="d-card fade-up mb-4">
            <div class="d-card-header">
                <h6><i class="fas fa-{{ $matriculaEdit ? 'pen-to-square' : 'plus-circle' }} text-primary"></i> {{ $matriculaEdit ? 'Editar Matrícula' : 'Nova Matrícula' }}</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ $matriculaEdit ? route('admin.matriculas.update', $matriculaEdit->id_matricula) : route('admin.matriculas.store') }}" method="POST">
                    @csrf
                    @if ($matriculaEdit)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Aluno</label>
                            <select name="id_aluno" class="form-control">
                                <option value="">Selecione...</option>
                                @foreach ($alunos as $aluno)
                                    <option value="{{ $aluno->id_aluno }}" {{ $matriculaEdit?->id_aluno == $aluno->id_aluno ? 'selected' : '' }}>
                                        {{ $aluno->nome_aluno }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Curso</label>
                            <select name="id_curso" class="form-control">
                                <option value="">Selecione...</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id_curso }}" {{ $matriculaEdit?->id_curso == $curso->id_curso ? 'selected' : '' }}>
                                        {{ $curso->nome_curso }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nível</label>
                            <select name="id_nivel" class="form-control">
                                <option value="">Selecione...</option>
                                @foreach ($niveis as $nivel)
                                    <option value="{{ $nivel->id_nivel }}" {{ $matriculaEdit?->id_nivel == $nivel->id_nivel ? 'selected' : '' }}>
                                        {{ $nivel->nome_nivel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data</label>
                            <input type="date" name="data_matricula" class="form-control" value="{{ $matriculaEdit ? \Carbon\Carbon::parse($matriculaEdit->data_matricula)->format('Y-m-d') : '' }}">
                        </div>
                        @if ($matriculaEdit)
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status_matricula" class="form-control">
                                    <option value="ATIVO" {{ $matriculaEdit->status_matricula == 'ATIVO' ? 'selected' : '' }}>ATIVO</option>
                                    <option value="CONGELADO" {{ $matriculaEdit->status_matricula == 'CONGELADO' ? 'selected' : '' }}>CONGELADO</option>
                                    <option value="CANCELADO" {{ $matriculaEdit->status_matricula == 'CANCELADO' ? 'selected' : '' }}>CANCELADO</option>
                                </select>
                            </div>
                        @endif
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="tbl-btn-success">
                            <i class="fas fa-save"></i> {{ $matriculaEdit ? 'Salvar' : 'Cadastrar' }}
                        </button>
                        @if ($matriculaEdit)
                            <a href="{{ route('admin.matriculas.index') }}" class="del-btn-cancelar">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- TABELA --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-id-card-clip text-primary"></i> Lista de Matrículas</h6>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matriculas as $matricula)
                            <tr>
                                <td style="font-weight:600;">{{ $matricula->aluno?->nome_aluno ?? '—' }}</td>
                                <td><span class="tbl-badge">{{ $matricula->curso?->nome_curso ?? '—' }}</span></td>
                                <td>{{ $matricula->nivel?->nome_nivel ?? '—' }}</td>
                                <td>
                                    <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                    {{ \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.matriculas.updateStatus', $matricula->id_matricula) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        @php
                                            $stClass = match($matricula->status_matricula) {
                                                'ATIVO' => 'tbl-status-ativo',
                                                'CONGELADO' => 'tbl-status-congelado',
                                                'CANCELADO' => 'tbl-status-cancelado',
                                                default => 'tbl-status-inativo',
                                            };
                                        @endphp
                                        <div class="tbl-status {{ $stClass }}" style="position:relative;">
                                            <span class="tbl-status-dot"></span>
                                            <select name="status_matricula" onchange="this.form.submit()"
                                                style="all:unset;cursor:pointer;font:inherit;color:inherit;background:transparent;letter-spacing:inherit;text-transform:inherit;">
                                                <option value="ATIVO" {{ $matricula->status_matricula == 'ATIVO' ? 'selected' : '' }}>ATIVO</option>
                                                <option value="CONGELADO" {{ $matricula->status_matricula == 'CONGELADO' ? 'selected' : '' }}>CONGELADO</option>
                                                <option value="CANCELADO" {{ $matricula->status_matricula == 'CANCELADO' ? 'selected' : '' }}>CANCELADO</option>
                                            </select>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('admin.matriculas.edit', $matricula->id_matricula) }}" class="tbl-btn-editar">
                                            <i class="fas fa-pen-to-square"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.matriculas.destroy', $matricula->id_matricula) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="tbl-btn-excluir"
                                                data-nome="{{ $matricula->aluno?->nome_aluno }}"
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
                                        <i class="fas fa-id-card-clip tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhuma matrícula encontrada</span>
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

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Matrícula', 'delDescricao' => 'Você está prestes a excluir a matrícula de:'])

@endsection
