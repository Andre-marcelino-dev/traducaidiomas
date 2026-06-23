@extends('admin.layout.admin')

@section('content')

<style>
    .aluno-page {
        padding-bottom: 32px;
    }

    .aluno-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .aluno-title-box h1 {
        font-size: 1.65rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .aluno-title-box p {
        color: #64748b;
        margin: 4px 0 0;
        font-size: .92rem;
    }

    .aluno-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(15, 23, 42, .08);
        overflow: hidden;
        background: #fff;
    }

    .aluno-card-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-bottom: 1px solid #e5edf8;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .aluno-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: #163d8f;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .aluno-card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #1e293b;
    }

    .aluno-card-header span {
        display: block;
        color: #64748b;
        font-size: .82rem;
        margin-top: 2px;
    }

    .aluno-card-body {
        padding: 24px;
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
        font-weight: 700;
        font-size: .86rem;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 1px solid #dbe3ef;
        min-height: 35px;
        font-size: .92rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #163d8f;
        box-shadow: 0 0 0 .18rem rgba(22, 61, 143, .12);
    }

    .input-hint {
        font-size: .76rem;
        color: #94a3b8;
        margin-top: 5px;
    }

    .foto-preview-box {
        min-height: 170px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        text-align: center;
    }

    .foto-preview-box img {
        max-width: 100%;
        max-height: 145px;
        display: none;
        border-radius: 14px;
        object-fit: cover;
    }

    .foto-preview-empty {
        color: #94a3b8;
        font-size: .86rem;
    }

    .aluno-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 24px;
        border-top: 1px solid #edf2f7;
    }

    .btn-voltar-aluno {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 700;
        background: #dc2626;
        border: 0;
        color: #fff;
        text-decoration: none;
    }

    .btn-voltar-aluno:hover {
        background: #475569;
        color: #fff;
    }

    .btn-salvar-aluno {
        border-radius: 12px;
        padding: 10px 22px;
        font-weight: 800;
        background: #163d8f;
        border: 0;
        color: #fff;
        box-shadow: 0 10px 20px rgba(22, 61, 143, .18);
    }

    .btn-salvar-aluno:hover {
        background: #0f2d72;
        color: #fff;
    }

    .alert {
        border-radius: 14px;
        border: 0;
    }

    @media (max-width: 768px) {
        .aluno-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .aluno-header .btn {
            width: 100%;
        }

        .aluno-card-body {
            padding: 18px;
        }

        .aluno-actions {
            flex-direction: column-reverse;
        }

        .aluno-actions .btn,
        .aluno-actions a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid aluno-page">

    <div class="aluno-header">
        <div class="aluno-title-box">
            <h1>Novo Aluno</h1>
            <p>Preencha os dados abaixo para cadastrar um novo aluno no sistema.</p>
        </div>

        <a href="{{ route('admin.alunos.index') }}" class="btn-voltar-aluno">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Verifique os campos abaixo:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card aluno-card">
        <div class="aluno-card-header">
            <div class="aluno-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>

            <div>
                <h5>Dados do Aluno</h5>
                <span>Informações pessoais, acesso e matrícula</span>
            </div>
        </div>

        <div class="aluno-card-body">
            <form action="{{ route('admin.alunos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-section-title">Informações principais</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome</label>
                        <input 
                            type="text" 
                            name="nome_aluno" 
                            class="form-control @error('nome_aluno') is-invalid @enderror" 
                            value="{{ old('nome_aluno') }}"
                            placeholder="Digite o nome completo"
                            required
                        >
                        @error('nome_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input 
                            type="email" 
                            name="email_aluno" 
                            class="form-control @error('email_aluno') is-invalid @enderror" 
                            value="{{ old('email_aluno') }}"
                            placeholder="exemplo@email.com"
                            required
                        >
                        @error('email_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Telefone</label>
                        <input 
                            type="text" 
                            name="telefone_aluno" 
                            class="form-control @error('telefone_aluno') is-invalid @enderror"
                            value="{{ old('telefone_aluno') }}"
                            placeholder="(11) 99999-9999"
                        >
                        @error('telefone_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Data de Nascimento</label>
                        <input 
                            type="date" 
                            name="data_nasc_aluno" 
                            class="form-control @error('data_nasc_aluno') is-invalid @enderror"
                            value="{{ old('data_nasc_aluno') }}"
                        >
                        @error('data_nasc_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Curso</label>
                        <input 
                            type="text" 
                            name="curso_aluno" 
                            class="form-control @error('curso_aluno') is-invalid @enderror" 
                            value="{{ old('curso_aluno') }}"
                            placeholder="Ex: Inglês"
                        >
                        @error('curso_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Acesso do aluno</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Senha</label>
                        <input 
                            type="password" 
                            name="senha_aluno" 
                            class="form-control @error('senha_aluno') is-invalid @enderror" 
                            required 
                            minlength="6"
                            placeholder="Mínimo 6 caracteres"
                        >
                        @error('senha_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="input-hint">A senha será usada para o acesso do aluno.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirmar Senha</label>
                        <input 
                            type="password" 
                            name="senha_aluno_confirmation" 
                            class="form-control" 
                            required
                            minlength="6"
                            placeholder="Digite a senha novamente"
                        >
                    </div>
                </div>

                <div class="form-section-title mt-4">Curso e status</div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nível</label>
                        <select name="nivel_aluno" class="form-select @error('nivel_aluno') is-invalid @enderror">
                            <option value="Iniciante" {{ old('nivel_aluno') == 'Iniciante' ? 'selected' : '' }}>Iniciante</option>
                            <option value="Intermediário" {{ old('nivel_aluno') == 'Intermediário' ? 'selected' : '' }}>Intermediário</option>
                            <option value="Avançado" {{ old('nivel_aluno') == 'Avançado' ? 'selected' : '' }}>Avançado</option>
                        </select>
                        @error('nivel_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status_aluno" class="form-select @error('status_aluno') is-invalid @enderror">
                            <option value="EM CURSO" {{ old('status_aluno') == 'EM CURSO' ? 'selected' : '' }}>EM CURSO</option>
                            <option value="CONCLUÍDO" {{ old('status_aluno') == 'CONCLUÍDO' ? 'selected' : '' }}>CONCLUÍDO</option>
                            <option value="INATIVO" {{ old('status_aluno') == 'INATIVO' ? 'selected' : '' }}>INATIVO</option>
                        </select>
                        @error('status_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section-title mt-4">Foto do aluno</div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-6">
                        <label class="form-label">Foto</label>
                        <input 
                            type="file" 
                            name="foto_aluno" 
                            id="foto_aluno"
                            class="form-control @error('foto_aluno') is-invalid @enderror" 
                            accept="image/*"
                            onchange="previewFotoAluno(event)"
                        >
                        @error('foto_aluno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="input-hint">Formatos recomendados: JPG, PNG ou WebP.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Pré-visualização</label>
                        <div class="foto-preview-box">
                            <img id="previewImagemAluno" src="" alt="Pré-visualização da foto">
                            <span id="previewTextoAluno" class="foto-preview-empty">
                                Nenhuma foto selecionada
                            </span>
                        </div>
                    </div>
                </div>

                <div class="aluno-actions">
                    <a href="{{ route('admin.alunos.index') }}" class="btn-voltar-aluno">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-salvar-aluno">
                        Cadastrar Aluno
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewFotoAluno(event) {
    const input = event.target;
    const previewImagem = document.getElementById('previewImagemAluno');
    const previewTexto = document.getElementById('previewTextoAluno');

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