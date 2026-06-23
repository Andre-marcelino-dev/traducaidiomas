@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<style>
    .material-form-page {
        padding-bottom: 34px;
    }

    .material-form-card {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .08);
        background: #fff;
    }

    .material-form-header {
        padding: 22px 24px;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-bottom: 1px solid #e5edf8;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .material-form-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: #163d8f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 10px 22px rgba(22, 61, 143, .18);
    }

    .material-form-header h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
    }

    .material-form-header span {
        display: block;
        margin-top: 2px;
        font-size: .82rem;
        color: #64748b;
    }

    .material-form-body {
        padding: 26px;
    }

    .form-section-title {
        font-size: .78rem;
        font-weight: 800;
        color: #163d8f;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin: 4px 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #163d8f;
        display: inline-block;
    }

    .form-label {
        font-size: .86rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 13px;
        border: 1px solid #dbe3ef;
        min-height: 44px;
        font-size: .92rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #163d8f;
        box-shadow: 0 0 0 .18rem rgba(22, 61, 143, .12);
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .material-hint {
        color: #94a3b8;
        font-size: .76rem;
        margin-top: 5px;
    }

    .material-current-file {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 16px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .material-current-icon {
        width: 42px;
        height: 42px;
        border-radius: 13px;
        background: #eef3ff;
        color: #163d8f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .material-current-title {
        font-size: .85rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .material-current-link {
        font-size: .82rem;
        font-weight: 700;
        color: #163d8f;
        text-decoration: none;
    }

    .material-current-link:hover {
        text-decoration: underline;
    }

    .material-file-box {
        border: 1px dashed #cbd5e1;
        background: #f8fafc;
        border-radius: 18px;
        padding: 18px;
        transition: .2s ease;
    }

    .material-file-box:hover {
        border-color: #163d8f;
        background: #f8fbff;
    }

    .material-file-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .material-file-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: #eef3ff;
        color: #163d8f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .material-file-title {
        font-weight: 800;
        color: #1e293b;
        font-size: .92rem;
    }

    .material-file-sub {
        color: #64748b;
        font-size: .78rem;
        margin-top: 1px;
    }

    .material-file-selected {
        margin-top: 10px;
        padding: 10px 12px;
        background: #ecfdf5;
        color: #047857;
        border-radius: 12px;
        font-size: .82rem;
        font-weight: 700;
        display: none;
    }

    .material-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 24px;
        border-top: 1px solid #edf2f7;
    }

    .btn-material-cancelar {
        border-radius: 12px;
        padding: 10px 18px;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 10px 22px rgba(239, 68, 68, .12);
        transition: .22s ease;
    }

    .btn-material-cancelar:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(239, 68, 68, .22);
    }

    .btn-material-salvar {
        border-radius: 12px;
        padding: 10px 22px;
        background: #10b981;
        color: #fff;
        border: 0;
        font-weight: 800;
        box-shadow: 0 10px 20px rgba(16, 185, 129, .18);
    }

    .btn-material-salvar:hover {
        background: #059669;
        color: #fff;
    }

    .alert {
        border: 0;
        border-radius: 15px;
    }

    @media (max-width: 768px) {
        .material-form-body {
            padding: 18px;
        }

        .material-actions {
            flex-direction: column-reverse;
        }

        .material-actions .btn,
        .material-actions a {
            width: 100%;
            text-align: center;
        }

        .material-current-file {
            align-items: flex-start;
        }
    }
</style>

<div class="material-form-page">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Editar Material</h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dash') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.materiais.index') }}">Materiais</a>
                        </li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3">
                            <strong>Verifique os campos abaixo:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card material-form-card">
                        <div class="material-form-header">
                            <div class="material-form-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                </svg>
                            </div>

                            <div>
                                <h5>Editar Material</h5>
                                <span>Atualize as informações, vínculos e arquivo do material.</span>
                            </div>
                        </div>

                        <div class="material-form-body">
                            <form action="{{ route('admin.materiais.update', $materiais->id_materiais) }}"
                                  method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-section-title">Informações do material</div>

                                <div class="row g-3">

                                    {{-- Título --}}
                                    <div class="col-12">
                                        <label class="form-label">
                                            Título <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            name="titulo_materiais"
                                            class="form-control @error('titulo_materiais') is-invalid @enderror"
                                            value="{{ old('titulo_materiais', $materiais->titulo_materiais) }}"
                                            placeholder="Ex: Apostila de Inglês Básico"
                                        >

                                        @error('titulo_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Descrição --}}
                                    <div class="col-12">
                                        <label class="form-label">Descrição</label>

                                        <textarea
                                            name="descricao_materiais"
                                            rows="3"
                                            class="form-control @error('descricao_materiais') is-invalid @enderror"
                                            placeholder="Descreva brevemente o conteúdo do material..."
                                        >{{ old('descricao_materiais', $materiais->descricao_materiais) }}</textarea>

                                        @error('descricao_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">Vínculos e classificação</div>

                                <div class="row g-3">

                                    {{-- Professor --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Professor <span class="text-danger">*</span>
                                        </label>

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
                                        <label class="form-label">Curso</label>

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
                                        <label class="form-label">Nível</label>

                                        <select name="nivel_material" class="form-select @error('nivel_material') is-invalid @enderror">
                                            <option value="">Selecione...</option>

                                            <option value="Básico" {{ old('nivel_material', $materiais->nivel_material) == 'Básico' ? 'selected' : '' }}>
                                                Básico
                                            </option>

                                            <option value="Intermediário" {{ old('nivel_material', $materiais->nivel_material) == 'Intermediário' ? 'selected' : '' }}>
                                                Intermediário
                                            </option>

                                            <option value="Avançado" {{ old('nivel_material', $materiais->nivel_material) == 'Avançado' ? 'selected' : '' }}>
                                                Avançado
                                            </option>
                                        </select>

                                        @error('nivel_material')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Curso texto livre --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Curso (descrição livre)</label>

                                        <input
                                            type="text"
                                            name="curso_materiais"
                                            class="form-control @error('curso_materiais') is-invalid @enderror"
                                            value="{{ old('curso_materiais', $materiais->curso_materiais) }}"
                                            placeholder="Ex: Inglês, Espanhol, Italiano..."
                                        >

                                        @error('curso_materiais')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="material-hint">
                                            Use esse campo caso o curso não esteja listado acima.
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section-title mt-4">Arquivo</div>

                                @if($materiais->arquivo_materiais)
                                    <div class="material-current-file">
                                        <div class="material-current-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 24 24" fill="none" stroke="#163d8f"
                                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <path d="M14 2v6h6"></path>
                                            </svg>
                                        </div>

                                        <div>
                                            <div class="material-current-title">Arquivo atual</div>

                                            <a href="{{ asset($materiais->arquivo_materiais) }}"
                                               target="_blank"
                                               class="material-current-link">
                                                Visualizar / Baixar
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div class="material-file-box">
                                    <div class="material-file-info">
                                        <div class="material-file-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 24 24" fill="none" stroke="#163d8f"
                                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <path d="M17 8l-5-5-5 5"></path>
                                                <path d="M12 3v12"></path>
                                            </svg>
                                        </div>

                                        <div>
                                            <div class="material-file-title">Substituir arquivo do material</div>
                                            <div class="material-file-sub">
                                                Deixe em branco para manter o arquivo atual. Máximo 20 MB.
                                            </div>
                                        </div>
                                    </div>

                                    <input
                                        type="file"
                                        name="arquivo_materiais"
                                        id="arquivo_materiais"
                                        class="form-control @error('arquivo_materiais') is-invalid @enderror"
                                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip"
                                        onchange="mostrarArquivoSelecionado(event)"
                                    >

                                    @error('arquivo_materiais')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div id="arquivoSelecionado" class="material-file-selected"></div>
                                </div>

                                <div class="material-actions">
                                    <a href="{{ route('admin.materiais.index') }}" class="btn-material-cancelar">
                                        Cancelar
                                    </a>

                                    <button type="submit" class="btn-material-salvar">
                                        Atualizar Material
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function mostrarArquivoSelecionado(event) {
    const input = event.target;
    const box = document.getElementById('arquivoSelecionado');

    if (!box) {
        return;
    }

    if (input.files && input.files[0]) {
        const arquivo = input.files[0];
        const tamanhoMb = (arquivo.size / 1024 / 1024).toFixed(2);

        box.style.display = 'block';
        box.textContent = 'Novo arquivo selecionado: ' + arquivo.name + ' (' + tamanhoMb + ' MB)';
    } else {
        box.style.display = 'none';
        box.textContent = '';
    }
}
</script>

@endsection