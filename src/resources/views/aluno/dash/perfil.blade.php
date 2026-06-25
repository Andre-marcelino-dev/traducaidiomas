@extends('aluno.layout.aluno')

@section('content')

<style>
    .perfil-page {
        --dark: #1a1a2e;
        --blue: #4f46e5;
        --green: #10b981;
        --red: #ef4444;
        --amber: #f59e0b;
        --slate-50: #f8fafc;
        --slate-100: #e2e8f0;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-700: #334155;
        --slate-800: #1e293b;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(25px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-up {
        opacity: 0;
        animation: fadeUp .7s ease-out forwards;
    }

    .delay-1 { animation-delay: .1s; }
    .delay-2 { animation-delay: .2s; }
    .delay-3 { animation-delay: .3s; }

    .perfil-card {
        background: #fff;
        border: 1px solid var(--slate-100);
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .perfil-cover {
        height: 105px;
        background: linear-gradient(135deg, #1a1a2e, #0f3460, #4f46e5);
        position: relative;
    }

    .perfil-avatar-wrap {
        margin-top: -60px;
        position: relative;
        z-index: 2;
    }

    .perfil-avatar {
        width: 125px;
        height: 125px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .18);
        background: #e2e8f0;
    }

    .perfil-avatar-placeholder {
        width: 125px;
        height: 125px;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .18);
        background: linear-gradient(135deg, #64748b, #334155);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .perfil-status {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .75rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #bbf7d0;
    }

    .perfil-info-list {
        display: grid;
        gap: .65rem;
        margin-top: 1rem;
    }

    .perfil-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .8rem;
        padding: .75rem .85rem;
        border-radius: 12px;
        background: var(--slate-50);
        border: 1px solid var(--slate-100);
    }

    .perfil-info-label {
        color: var(--slate-500);
        font-size: .78rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: .45rem;
    }

    .perfil-info-value {
        color: var(--slate-800);
        font-size: .82rem;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    .perfil-form-card {
        background: #fff;
        border: 1px solid var(--slate-100);
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .perfil-form-header {
        padding: 1rem 1.2rem;
        background: linear-gradient(135deg, #f8faff, #eef3ff);
        border-bottom: 1px solid var(--slate-100);
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .perfil-form-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef3ff;
        color: var(--blue);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .perfil-form-title {
        margin: 0;
        font-size: 1rem;
        color: var(--slate-800);
        font-weight: 800;
    }

    .perfil-form-sub {
        margin: 0;
        font-size: .78rem;
        color: var(--slate-500);
    }

    .perfil-form-body {
        padding: 1.25rem;
    }

    .perfil-input {
        border-radius: 12px;
        border: 1px solid var(--slate-100);
        padding: .75rem .9rem;
        font-size: .9rem;
    }

    .perfil-input:focus {
        border-color: var(--blue);
        box-shadow: 0 0 0 .18rem rgba(79, 70, 229, .12);
    }

    .perfil-btn-primary {
        border: none;
        border-radius: 12px;
        padding: .7rem 1rem;
        background: linear-gradient(135deg, #4f46e5, #3730a3);
        color: #fff;
        font-weight: 700;
        transition: all .2s;
    }

    .perfil-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(79, 70, 229, .25);
        color: #fff;
    }

    .perfil-btn-danger {
        border: none;
        border-radius: 12px;
        padding: .7rem 1rem;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff;
        font-weight: 700;
        transition: all .2s;
    }

    .perfil-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(239, 68, 68, .25);
        color: #fff;
    }

    .perfil-btn-warning {
        border: none;
        border-radius: 12px;
        padding: .7rem 1rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-weight: 700;
        transition: all .2s;
    }

    .perfil-btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(245, 158, 11, .25);
        color: #fff;
    }

    .foto-upload-box {
        border: 1.5px dashed var(--slate-100);
        border-radius: 14px;
        padding: .85rem;
        background: var(--slate-50);
    }

    .senha-tabs .nav-link {
        border: none;
        border-radius: 12px;
        color: var(--slate-500);
        font-weight: 700;
        font-size: .85rem;
        padding: .7rem 1rem;
    }

    .senha-tabs .nav-link.active {
        background: #eef3ff;
        color: var(--blue);
    }

    .password-toggle {
        border-radius: 0 12px 12px 0 !important;
        border-color: var(--slate-100);
        color: var(--slate-500);
    }

    .security-note {
        border-radius: 14px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: .85rem;
        font-size: .82rem;
        line-height: 1.45;
    }

    .alert {
        border-radius: 14px;
        border: none;
    }

    @media (max-width: 767.98px) {
        .perfil-info-item {
            align-items: flex-start;
            flex-direction: column;
        }

        .perfil-info-value {
            text-align: left;
        }
    }
</style>

<div class="perfil-page">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mt-3 mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#1e293b;">Meu Perfil</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('aluno.dash') }}" style="text-decoration:none;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Meu Perfil</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show fade-up">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show fade-up">
                    <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show fade-up">
                    <div class="fw-bold mb-1">
                        <i class="fas fa-triangle-exclamation me-2"></i>Corrija os campos abaixo:
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">

                {{-- CARD PERFIL --}}
                <div class="col-lg-4 fade-up delay-1">
                    <div class="perfil-card text-center h-100">
                        <div class="perfil-cover"></div>

                        <div class="perfil-avatar-wrap">
                            @if($aluno->foto_aluno)
                                <img
                                    id="previewFoto"
                                    src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                    class="perfil-avatar"
                                    alt="{{ $aluno->nome_aluno }}"
                                >
                            @else
                                <div id="placeholderFoto" class="perfil-avatar-placeholder mx-auto">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                                <img
                                    id="previewFoto"
                                    src=""
                                    class="perfil-avatar d-none"
                                    alt="Prévia da foto"
                                >
                            @endif
                        </div>

                        <div class="px-4 pb-4 pt-3">
                            <h5 class="fw-bold mb-1" style="color:#1e293b;">
                                {{ $aluno->nome_aluno }}
                            </h5>

                            <p class="text-muted small mb-2">
                                {{ $aluno->email_aluno }}
                            </p>

                            <span class="perfil-status">
                                <i class="fas fa-circle-check"></i>
                                {{ $aluno->status_aluno ?? 'Ativo' }}
                            </span>

                            <div class="perfil-info-list text-start">
                                <div class="perfil-info-item">
                                    <span class="perfil-info-label">
                                        <i class="fas fa-book text-primary"></i> Curso
                                    </span>
                                    <span class="perfil-info-value">
                                        {{ $aluno->curso_aluno ?: '—' }}
                                    </span>
                                </div>

                                <div class="perfil-info-item">
                                    <span class="perfil-info-label">
                                        <i class="fas fa-layer-group text-warning"></i> Nível
                                    </span>
                                    <span class="perfil-info-value">
                                        {{ $aluno->nivel_aluno ?: '—' }}
                                    </span>
                                </div>

                                <div class="perfil-info-item">
                                    <span class="perfil-info-label">
                                        <i class="fas fa-phone text-success"></i> Telefone
                                    </span>
                                    <span class="perfil-info-value">
                                        {{ $aluno->telefone_aluno ?: '—' }}
                                    </span>
                                </div>
                            </div>

                            {{-- FORM FOTO --}}
                            <form action="{{ route('aluno.perfil.foto') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                @method('PUT')

                                <div class="foto-upload-box text-start">
                                    <label class="form-label fw-bold small mb-2">
                                        <i class="fas fa-camera me-1 text-primary"></i>
                                        Alterar foto de perfil
                                    </label>

                                    <input
                                        type="file"
                                        id="foto_aluno"
                                        name="foto_aluno"
                                        class="form-control form-control-sm @error('foto_aluno') is-invalid @enderror"
                                        accept="image/*"
                                    >

                                    @error('foto_aluno')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="text-muted d-block mt-2">
                                        Use uma imagem JPG, PNG ou WEBP.
                                    </small>
                                </div>

                                <button type="submit" class="perfil-btn-primary w-100 mt-3">
                                    <i class="fas fa-upload me-1"></i> Salvar Foto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- COLUNA FORMULÁRIOS --}}
                <div class="col-lg-8">

                    {{-- ALTERAR EMAIL --}}
                    <div class="perfil-form-card mb-4 fade-up delay-2">
                        <div class="perfil-form-header">
                            <div class="perfil-form-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h5 class="perfil-form-title">Alterar E-mail</h5>
                                <p class="perfil-form-sub">Atualize o e-mail usado para acessar sua conta.</p>
                            </div>
                        </div>

                        <div class="perfil-form-body">
                            <form action="{{ route('aluno.perfil.email') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">E-mail atual</label>
                                        <input
                                            type="text"
                                            class="form-control perfil-input"
                                            value="{{ $aluno->email_aluno }}"
                                            disabled
                                        >
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Novo e-mail</label>
                                        <input
                                            type="email"
                                            name="email_aluno"
                                            class="form-control perfil-input @error('email_aluno') is-invalid @enderror"
                                            placeholder="Digite o novo e-mail"
                                            value="{{ old('email_aluno') }}"
                                            required
                                        >

                                        @error('email_aluno')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="perfil-btn-primary mt-3">
                                    <i class="fas fa-save me-1"></i> Atualizar E-mail
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- REDEFINIR SENHA --}}
                    <div class="perfil-form-card fade-up delay-3">
                        <div class="perfil-form-header">
                            <div class="perfil-form-icon" style="background:#fff1f2;color:#e11d48;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h5 class="perfil-form-title">Redefinir Senha</h5>
                                <p class="perfil-form-sub">Altere sua senha usando a senha atual ou confirmação de e-mail.</p>
                            </div>
                        </div>

                        <div class="perfil-form-body">
                            <ul class="nav nav-pills senha-tabs mb-3" id="senhaTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link active"
                                        id="comSenha-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#comSenha"
                                        type="button"
                                        role="tab"
                                    >
                                        <i class="fas fa-shield-halved me-1"></i>
                                        Sei minha senha
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link"
                                        id="semSenha-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#semSenha"
                                        type="button"
                                        role="tab"
                                    >
                                        <i class="fas fa-key me-1"></i>
                                        Esqueci minha senha
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">

                                {{-- COM SENHA ATUAL --}}
                                <div class="tab-pane fade show active" id="comSenha" role="tabpanel">
                                    <form action="{{ route('aluno.perfil.senha') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="modo" value="com_senha">

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Senha atual</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        name="senha_atual"
                                                        class="form-control perfil-input @error('senha_atual') is-invalid @enderror"
                                                        required
                                                    >
                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    @error('senha_atual')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Nova senha</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        name="nova_senha"
                                                        class="form-control perfil-input @error('nova_senha') is-invalid @enderror"
                                                        required
                                                        minlength="6"
                                                    >
                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    @error('nova_senha')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Confirmar nova senha</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        name="nova_senha_confirmation"
                                                        class="form-control perfil-input"
                                                        required
                                                        minlength="6"
                                                    >
                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="perfil-btn-danger mt-3">
                                            <i class="fas fa-lock me-1"></i> Redefinir Senha
                                        </button>
                                    </form>
                                </div>

                                {{-- SEM SENHA ATUAL --}}
                                <div class="tab-pane fade" id="semSenha" role="tabpanel">
                                    <div class="security-note mb-3">
                                        <i class="fas fa-circle-info me-1"></i>
                                        Confirme o e-mail cadastrado antes de definir uma nova senha.
                                    </div>

                                    <form action="{{ route('aluno.perfil.senha') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="modo" value="sem_senha">

                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Confirme seu e-mail</label>
                                                <input
                                                    type="email"
                                                    name="email_confirmacao"
                                                    class="form-control perfil-input @error('email_confirmacao') is-invalid @enderror"
                                                    required
                                                    placeholder="Digite o e-mail cadastrado"
                                                    value="{{ old('email_confirmacao') }}"
                                                >

                                                @error('email_confirmacao')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Nova senha</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        name="nova_senha"
                                                        class="form-control perfil-input @error('nova_senha') is-invalid @enderror"
                                                        required
                                                        minlength="6"
                                                    >
                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Confirmar nova senha</label>
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        name="nova_senha_confirmation"
                                                        class="form-control perfil-input"
                                                        required
                                                        minlength="6"
                                                    >
                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="perfil-btn-warning mt-3">
                                            <i class="fas fa-key me-1"></i> Redefinir Senha
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFoto = document.getElementById('foto_aluno');
    const previewFoto = document.getElementById('previewFoto');
    const placeholderFoto = document.getElementById('placeholderFoto');

    if (inputFoto && previewFoto) {
        inputFoto.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                previewFoto.src = e.target.result;
                previewFoto.classList.remove('d-none');

                if (placeholderFoto) {
                    placeholderFoto.classList.add('d-none');
                }
            };

            reader.readAsDataURL(file);
        });
    }

    document.querySelectorAll('.password-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});
</script>

@endsection