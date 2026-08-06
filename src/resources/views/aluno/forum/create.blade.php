@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Novo Tópico</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('aluno.forum.index') }}">Fórum</a></li>
                    <li class="breadcrumb-item active">Novo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-card fade-up">
                    <div class="d-card-header">
                        <h6><i class="fas fa-comment-medical text-primary"></i> Criar Tópico</h6>
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

                        <form action="{{ route('aluno.forum.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">

                                {{-- Curso --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Curso <span class="text-danger">*</span></label>
                                    <select name="id_curso" class="form-select @error('id_curso') is-invalid @enderror">
                                        <option value="">Selecione...</option>
                                        @foreach($cursos as $curso)
                                            <option value="{{ $curso->id_curso }}" {{ old('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                                {{ $curso->nome_curso }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_curso')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Título --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                                    <input type="text" name="titulo_topico" class="form-control @error('titulo_topico') is-invalid @enderror"
                                           value="{{ old('titulo_topico') }}" placeholder="Ex: Dúvida sobre o uso do Present Perfect">
                                    @error('titulo_topico')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Descrição --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Descrição <span class="text-danger">*</span></label>
                                    <textarea name="descricao_topico" rows="5"
                                              class="form-control @error('descricao_topico') is-invalid @enderror"
                                              placeholder="Compartilhe sua dúvida ou conteúdo com os colegas do curso...">{{ old('descricao_topico') }}</textarea>
                                    @error('descricao_topico')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Anexo --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Anexo</label>
                                    <input type="file" name="anexo_topico"
                                           class="form-control @error('anexo_topico') is-invalid @enderror"
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.jpg,.jpeg,.png">
                                    <div class="form-text">Opcional. Formatos aceitos: PDF, Word, PowerPoint, Excel, ZIP, imagens. Máximo 20 MB.</div>
                                    @error('anexo_topico')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('aluno.forum.index') }}" class="del-btn-cancelar">
                                    <i class="fas fa-arrow-left me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="tbl-btn-success">
                                    <i class="fas fa-paper-plane"></i> Publicar Tópico
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
