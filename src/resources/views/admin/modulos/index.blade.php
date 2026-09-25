@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Módulos</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Módulos</li>
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
                    <div class="mc-icon"><i class="fas fa-layer-group"></i></div>
                    <div class="mc-val">{{ $totalModulos ?? 0 }}</div>
                    <p class="mc-lbl">Total de Módulos</p>
                    <div class="mc-trend"><i class="fas fa-database me-1"></i>cadastrados</div>
                </div>
            </div>
        </div>

        {{-- FORMULÁRIO --}}
        <div class="d-card fade-up mb-4">
            <div class="d-card-header">
                <h6><i class="fas fa-{{ $moduloEdit ? 'pen-to-square' : 'plus-circle' }} text-primary"></i> {{ $moduloEdit ? 'Editar Módulo' : 'Novo Módulo' }}</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ $moduloEdit ? route('admin.modulos.update', $moduloEdit->id_modulo) : route('admin.modulos.store') }}" method="POST">
                    @csrf
                    @if ($moduloEdit)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Curso</label>
                            <select name="id_curso" class="form-control">
                                <option value="">Selecione...</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id_curso }}" {{ old('id_curso', $moduloEdit?->id_curso) == $curso->id_curso ? 'selected' : '' }}>
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
                                    <option value="{{ $nivel->id_nivel }}" {{ old('id_nivel', $moduloEdit?->id_nivel) == $nivel->id_nivel ? 'selected' : '' }}>
                                        {{ $nivel->nome_nivel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ordem</label>
                            <input type="number" name="ordem_modulo" min="1" class="form-control" value="{{ old('ordem_modulo', $moduloEdit?->ordem_modulo) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nome do módulo</label>
                            <input type="text" name="nome_modulo" class="form-control" placeholder="Ex: Fundamentos" value="{{ old('nome_modulo', $moduloEdit?->nome_modulo) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao_modulo" rows="2" class="form-control">{{ old('descricao_modulo', $moduloEdit?->descricao_modulo) }}</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Carga horária (minutos)</label>
                            <input type="number" name="carga_horaria_minutos" min="0" class="form-control" value="{{ old('carga_horaria_minutos', $moduloEdit?->carga_horaria_minutos ?? 0) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status_modulo" class="form-control">
                                <option value="ATIVO" {{ old('status_modulo', $moduloEdit?->status_modulo ?? 'ATIVO') == 'ATIVO' ? 'selected' : '' }}>ATIVO</option>
                                <option value="INATIVO" {{ old('status_modulo', $moduloEdit?->status_modulo) == 'INATIVO' ? 'selected' : '' }}>INATIVO</option>
                            </select>
                        </div>
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
                            <i class="fas fa-save"></i> {{ $moduloEdit ? 'Salvar' : 'Cadastrar' }}
                        </button>
                        @if ($moduloEdit)
                            <a href="{{ route('admin.modulos.index') }}" class="del-btn-cancelar">Cancelar</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- TABELA --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-layer-group text-primary"></i> Lista de Módulos</h6>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Ordem</th>
                            <th>Nome</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Carga horária</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modulos as $modulo)
                            <tr>
                                <td>{{ $modulo->ordem_modulo }}</td>
                                <td style="font-weight:600;">{{ $modulo->nome_modulo }}</td>
                                <td><span class="tbl-badge">{{ $modulo->curso?->nome_curso ?? '—' }}</span></td>
                                <td>{{ $modulo->nivel?->nome_nivel ?? '—' }}</td>
                                <td>{{ intdiv($modulo->carga_horaria_minutos, 60) }}h{{ str_pad($modulo->carga_horaria_minutos % 60, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="tbl-status {{ $modulo->status_modulo == 'ATIVO' ? 'tbl-status-ativo' : 'tbl-status-inativo' }}">
                                        <span class="tbl-status-dot"></span>{{ $modulo->status_modulo }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('admin.modulos.edit', $modulo->id_modulo) }}" class="tbl-btn-editar">
                                            <i class="fas fa-pen-to-square"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.modulos.destroy', $modulo->id_modulo) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="tbl-btn-excluir"
                                                data-nome="{{ $modulo->nome_modulo }}"
                                                onclick="abrirModalExcluir(this)">
                                                <i class="fas fa-trash-alt"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="tbl-empty">
                                        <i class="fas fa-layer-group tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhum módulo cadastrado ainda.</span>
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

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Módulo', 'delDescricao' => 'Você está prestes a excluir o módulo:'])

@endsection
