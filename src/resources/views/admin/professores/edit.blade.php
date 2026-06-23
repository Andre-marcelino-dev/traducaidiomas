@extends('admin.layout.admin')

@section('content')

<style>
    .prof-form-page {
        padding-bottom: 34px;
    }

    .prof-form-header-page {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .prof-form-header-page h1 {
        font-size: 1.65rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .prof-form-header-page p {
        color: #64748b;
        margin: 4px 0 0;
        font-size: .92rem;
    }

    .prof-form-card {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .08);
        background: #fff;
    }

    .prof-form-card-header {
        padding: 22px 24px;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-bottom: 1px solid #e5edf8;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .prof-form-icon {
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

    .prof-form-card-header h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
    }

    .prof-form-card-header span {
        display: block;
        margin-top: 2px;
        font-size: .82rem;
        color: #64748b;
    }

    .prof-form-body {
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
        min-height: 35px;
        font-size: .92rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #163d8f;
        box-shadow: 0 0 0 .18rem rgba(22, 61, 143, .12);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .input-group-text {
        border-color: #dbe3ef;
        border-radius: 0 13px 13px 0;
        font-weight: 700;
        color: #64748b;
        background: #f8fafc;
    }

    .prof-hint {
        color: #94a3b8;
        font-size: .76rem;
        margin-top: 5px;
    }

    .prof-photo-box {
        min-height: 180px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        text-align: center;
        transition: .2s ease;
    }

    .prof-photo-box:hover {
        border-color: #163d8f;
        background: #f8fbff;
    }

    .prof-photo-box img {
        max-width: 100%;
        max-height: 150px;
        border-radius: 14px;
        object-fit: cover;
    }

    .prof-current-photo {
        width: 115px;
        height: 115px;
        object-fit: cover;
        border-radius: 50% !important;
        border: 4px solid #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .15);
    }

    .prof-preview-img {
        display: none;
    }

    .prof-preview-empty {
        color: #94a3b8;
        font-size: .86rem;
    }

    .prof-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 24px;
        border-top: 1px solid #edf2f7;
    }

    .btn-prof-cancelar {
        border-radius: 12px;
        padding: 10px 18px;
        background: #dc2626;
        color: #fff;
        border: 0;
        font-weight: 700;
        text-decoration: none;
    }

    .btn-prof-cancelar:hover {
        background: #475569;
        color: #fff;
    }

    .btn-prof-salvar {
        border-radius: 12px;
        padding: 10px 22px;
        background: #10b981;
        color: #fff;
        border: 0;
        font-weight: 800;
        box-shadow: 0 10px 20px rgba(16, 185, 129, .18);
    }

    .btn-prof-salvar:hover {
        background: #059669;
        color: #fff;
    }

    .alert {
        border: 0;
        border-radius: 15px;
    }

    @media (max-width: 768px) {
        .prof-form-header-page {
            flex-direction: column;
            align-items: flex-start;
        }

        .prof-form-header-page .btn-prof-cancelar {
            width: 100%;
            text-align: center;
        }

        .prof-form-body {
            padding: 18px;
        }

        .prof-actions {
            flex-direction: column-reverse;
        }

        .prof-actions .btn,
        .prof-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid prof-form-page">

    <div class="prof-form-header-page">
        <div>
            <h1>Editar Professor</h1>
            <p>Atualize os dados cadastrais do professor.</p>
        </div>

        <a href="{{ route('admin.professores.index') }}" class="btn-prof-cancelar">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Ops! Existem erros no formulário.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card prof-form-card">
        <div class="prof-form-card-header">
            <div class="prof-form-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                </svg>
            </div>

            <div>
                <h5>Dados do Professor</h5>
                <span>Editando: {{ $professor->nome_professor }}</span>
            </div>
        </div>

        <div class="prof-form-body">
            <form 
                action="{{ route('admin.professores.update', $professor->id_professor) }}" 
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="form-section-title">Informações principais</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome_professor" class="form-label">
                            Nome <span class="text-danger">*</span>
                        </label>

                        <input 
                            type="text" 
                            id="nome_professor"
                            name="nome_professor" 
                            class="form-control @error('nome_professor') is-invalid @enderror"
                            value="{{ old('nome_professor', $professor->nome_professor) }}"
                            required
                            autocomplete="name"
                            placeholder="Ex: Ana Souza"
                        >

                        @error('nome_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email_professor" class="form-label">
                            E-mail <span class="text-danger">*</span>
                        </label>

                        <input 
                            type="email" 
                            id="email_professor"
                            name="email_professor" 
                            class="form-control @error('email_professor') is-invalid @enderror"
                            value="{{ old('email_professor', $professor->email_professor) }}"
                            required
                            autocomplete="email"
                            placeholder="professor@email.com"
                        >

                        @error('email_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="telefone_professor" class="form-label">Telefone</label>

                        <input 
                            type="text" 
                            id="telefone_professor"
                            name="telefone_professor" 
                            class="form-control @error('telefone_professor') is-invalid @enderror"
                            value="{{ old('telefone_professor', $professor->telefone_professor) }}"
                            placeholder="(11) 99999-9999"
                            autocomplete="tel"
                        >

                        @error('telefone_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="especialidade_professor" class="form-label">Especialidade</label>

                        <input 
                            type="text" 
                            id="especialidade_professor"
                            name="especialidade_professor" 
                            class="form-control @error('especialidade_professor') is-invalid @enderror"
                            value="{{ old('especialidade_professor', $professor->especialidade_professor) }}"
                            placeholder="Ex: Inglês, Espanhol, Italiano"
                        >

                        @error('especialidade_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Curso e experiência</div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="curso_professor" class="form-label">Curso</label>

                        <input 
                            type="text" 
                            id="curso_professor"
                            name="curso_professor" 
                            class="form-control @error('curso_professor') is-invalid @enderror"
                            value="{{ old('curso_professor', $professor->curso_professor) }}"
                            placeholder="Ex: Inglês"
                        >

                        @error('curso_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="nivel_professor" class="form-label">Nível</label>

                        <select 
                            id="nivel_professor"
                            name="nivel_professor" 
                            class="form-select @error('nivel_professor') is-invalid @enderror"
                        >
                            <option value="">Selecione</option>

                            <option value="Básico" 
                                {{ old('nivel_professor', $professor->nivel_professor) == 'Básico' ? 'selected' : '' }}>
                                Básico
                            </option>

                            <option value="Intermediário" 
                                {{ old('nivel_professor', $professor->nivel_professor) == 'Intermediário' ? 'selected' : '' }}>
                                Intermediário
                            </option>

                            <option value="Avançado" 
                                {{ old('nivel_professor', $professor->nivel_professor) == 'Avançado' ? 'selected' : '' }}>
                                Avançado
                            </option>

                            <option value="Fluente" 
                                {{ old('nivel_professor', $professor->nivel_professor) == 'Fluente' ? 'selected' : '' }}>
                                Fluente
                            </option>
                        </select>

                        @error('nivel_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="experiencia_professor" class="form-label">Experiência</label>

                        <div class="input-group">
                            <input 
                                type="number" 
                                id="experiencia_professor"
                                name="experiencia_professor" 
                                class="form-control @error('experiencia_professor') is-invalid @enderror"
                                value="{{ old('experiencia_professor', $professor->experiencia_professor) }}"
                                min="0"
                                max="60"
                                placeholder="Ex: 3"
                            >

                            <span class="input-group-text">anos</span>

                            @error('experiencia_professor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section-title mt-4">Apresentação</div>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="bio_professor" class="form-label">Biografia</label>

                        <textarea 
                            id="bio_professor"
                            name="bio_professor" 
                            class="form-control @error('bio_professor') is-invalid @enderror"
                            rows="5"
                            placeholder="Escreva uma breve apresentação do professor..."
                        >{{ old('bio_professor', $professor->bio_professor) }}</textarea>

                        @error('bio_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Acesso do professor</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="senha_professor" class="form-label">Nova Senha</label>

                        <input 
                            type="password" 
                            id="senha_professor"
                            name="senha_professor" 
                            class="form-control @error('senha_professor') is-invalid @enderror"
                            minlength="6"
                            placeholder="Deixe em branco para não alterar"
                            autocomplete="new-password"
                        >

                        @error('senha_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="prof-hint">
                            Só preencha este campo se quiser alterar a senha.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="senha_professor_confirmation" class="form-label">Confirmar Nova Senha</label>

                        <input 
                            type="password" 
                            id="senha_professor_confirmation"
                            name="senha_professor_confirmation" 
                            class="form-control"
                            minlength="6"
                            placeholder="Confirme a nova senha"
                            autocomplete="new-password"
                        >
                    </div>
                </div>

                <div class="form-section-title mt-4">Foto do professor</div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-4">
                        <label class="form-label">Foto Atual</label>

                        <div class="prof-photo-box">
                            @if ($professor->foto_professor)
                                <img 
                                    src="{{ asset('traducaidiomas/professor/' . $professor->foto_professor) }}"
                                    alt="{{ $professor->nome_professor }}" 
                                    class="prof-current-photo"
                                >
                            @else
                                <span class="prof-preview-empty">
                                    Nenhuma foto cadastrada
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="foto_professor" class="form-label">Nova Foto</label>

                        <input 
                            type="file" 
                            id="foto_professor"
                            name="foto_professor" 
                            class="form-control @error('foto_professor') is-invalid @enderror"
                            accept="image/*"
                            onchange="previewFoto(event)"
                        >

                        @error('foto_professor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="prof-hint">
                            Formatos aceitos: JPG, PNG ou WebP.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pré-visualização</label>

                        <div class="prof-photo-box">
                            <img 
                                id="previewImagem" 
                                src="" 
                                alt="Pré-visualização da nova foto" 
                                class="prof-preview-img"
                            >

                            <span id="previewTexto" class="prof-preview-empty">
                                Nenhuma nova foto selecionada
                            </span>
                        </div>
                    </div>
                </div>

                <div class="prof-actions">
                    <a href="{{ route('admin.professores.index') }}" class="btn-prof-cancelar">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-prof-salvar">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewFoto(event) {
    const input = event.target;
    const previewImagem = document.getElementById('previewImagem');
    const previewTexto = document.getElementById('previewTexto');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImagem.src = e.target.result;
            previewImagem.style.display = 'block';
            previewTexto.style.display = 'none';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        previewImagem.src = '';
        previewImagem.style.display = 'none';
        previewTexto.style.display = 'block';
    }
}
</script>

@endsection