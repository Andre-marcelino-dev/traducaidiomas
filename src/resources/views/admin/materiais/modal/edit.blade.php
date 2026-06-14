@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Editar Material</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materiais.index') }}">Materiais</a></li>
                        <li class="breadcrumb-item active">Editar</li>
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
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-file-pen me-2 text-primary"></i>Editar Material</h5>
                        </div>
                        <div class="card-body p-4">

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.materiais.update', $materiais->id_materiais) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">

                                    {{-- Título --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                                        <input type="text" name="titulo_materiais"
                                               class="form-control @error('titulo_materiais') is-invalid @enderror"
                                               value="{{ old('titulo_materiais', $materiais->titulo_materiais) }}">
                                        @error('titulo_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Descrição --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Descrição</label>
                                        <textarea name="descricao_materiais" rows="3"
                                                  class="form-control @error('descricao_materiais') is-invalid @enderror">{{ old('descricao_materiais', $materiais->descricao_materiais) }}</textarea>
                                        @error('descricao_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Professor --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Professor <span class="text-danger">*</span></label>
                                        <select name="id_professor" class="form-select @error('id_professor') is-invalid @enderror">
                                            <option value="">Selecione...</option>
                                            @foreach($professores as $professor)
                                                <option value="{{ $professor->id_professor }}"
                                                    {{ old('id_professor', $materiais->id_professor) == $professor->id_professor ? 'selected' : '' }}>
                                                    {{ $professor->nome_professor }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_professor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Curso --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Curso</label>
                                        <select name="id_curso" class="form-select @error('id_curso') is-invalid @enderror">
                                            <option value="">Selecione...</option>
                                            @foreach($cursos as $curso)
                                                <option value="{{ $curso->id_curso }}"
                                                    {{ old('id_curso', $materiais->id_curso) == $curso->id_curso ? 'selected' : '' }}>
                                                    {{ $curso->nome_curso }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_curso')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Nível --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nível</label>
                                        <select name="nivel_material" class="form-select @error('nivel_material') is-invalid @enderror">
                                            <option value="">Selecione...</option>
                                            <option value="Básico"        {{ old('nivel_material', $materiais->nivel_material) == 'Básico'        ? 'selected' : '' }}>Básico</option>
                                            <option value="Intermediário" {{ old('nivel_material', $materiais->nivel_material) == 'Intermediário' ? 'selected' : '' }}>Intermediário</option>
                                            <option value="Avançado"      {{ old('nivel_material', $materiais->nivel_material) == 'Avançado'      ? 'selected' : '' }}>Avançado</option>
                                        </select>
                                        @error('nivel_material')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Curso texto livre --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Curso (descrição livre)</label>
                                        <input type="text" name="curso_materiais"
                                               class="form-control @error('curso_materiais') is-invalid @enderror"
                                               value="{{ old('curso_materiais', $materiais->curso_materiais) }}">
                                        @error('curso_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Arquivo --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Arquivo</label>

                                        @if($materiais->arquivo_materiais)
                                            <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light rounded">
                                                <i class="fas fa-file-alt text-primary"></i>
                                                <span style="font-size:.85rem;">Arquivo atual:</span>
                                                <a href="{{ asset($materiais->arquivo_materiais) }}"
                                                   target="_blank" class="text-primary fw-semibold" style="font-size:.85rem;">
                                                    Visualizar / Baixar
                                                </a>
                                            </div>
                                        @endif

                                        <input type="file" name="arquivo_materiais"
                                               class="form-control @error('arquivo_materiais') is-invalid @enderror"
                                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip">
                                        <div class="form-text">Deixe em branco para manter o arquivo atual. Máximo 20 MB.</div>
                                        @error('arquivo_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.materiais.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i> Atualizar Material
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
