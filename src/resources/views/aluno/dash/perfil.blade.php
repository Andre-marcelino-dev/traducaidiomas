@extends('aluno.layout.aluno')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Meu Perfil</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Meu Perfil</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- CARD FOTO --}}
            <div class="col-md-4">
                <div class="card shadow-sm text-center p-4">
                    <div class="mb-3">
                        @if($aluno->foto_aluno)
                            <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                class="rounded-circle shadow" style="width:120px; height:120px; object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto shadow"
                                style="width:120px; height:120px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-0">{{ $aluno->nome_aluno }}</h5>
                    <p class="text-muted small">{{ $aluno->email_aluno }}</p>
                    <span class="badge bg-success">{{ $aluno->status_aluno }}</span>

                    <hr>
                    <p class="text-muted small mb-1"><strong>Curso:</strong> {{ $aluno->curso_aluno }}</p>
                    <p class="text-muted small mb-1"><strong>Nível:</strong> {{ $aluno->nivel_aluno }}</p>
                    <p class="text-muted small"><strong>Telefone:</strong> {{ $aluno->telefone_aluno }}</p>

                    {{-- FORM FOTO --}}
                    <form action="{{ route('aluno.perfil.foto') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        @method('PUT')
                        <label class="form-label fw-bold small">Alterar Foto</label>
                        <input type="file" name="foto_aluno" class="form-control form-control-sm mb-2" accept="image/*">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-camera me-1"></i> Salvar Foto
                        </button>
                    </form>
                </div>
            </div>

            {{-- CARD DADOS --}}
            <div class="col-md-8">
                {{-- EMAIL --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                        ✉️ Alterar Email
                    </div>
                    <div class="card-body">
                        <form action="{{ route('aluno.perfil.email') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Email Atual</label>
                                <input type="text" class="form-control" value="{{ $aluno->email_aluno }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Novo Email</label>
                                <input type="email" name="email_aluno" class="form-control" placeholder="Digite o novo email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Atualizar Email
                            </button>
                        </form>
                    </div>
                </div>

                {{-- SENHA --}}
                <div class="card shadow-sm">
                    <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                        🔒 Redefinir Senha
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="senhaTab">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#comSenha">Sei minha senha</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#semSenha">Esqueci minha senha</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            {{-- COM SENHA ATUAL --}}
                            <div class="tab-pane fade show active" id="comSenha">
                                <form action="{{ route('aluno.perfil.senha') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="modo" value="com_senha">
                                    <div class="mb-3">
                                        <label class="form-label">Senha Atual</label>
                                        <input type="password" name="senha_atual" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nova Senha</label>
                                        <input type="password" name="nova_senha" class="form-control" required minlength="6">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirmar Nova Senha</label>
                                        <input type="password" name="nova_senha_confirmation" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-lock me-1"></i> Redefinir Senha
                                    </button>
                                </form>
                            </div>
                            {{-- SEM SENHA ATUAL --}}
                            <div class="tab-pane fade" id="semSenha">
                                <div class="alert alert-info small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Confirme seu email cadastrado e defina uma nova senha.
                                </div>
                                <form action="{{ route('aluno.perfil.senha') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="modo" value="sem_senha">
                                    <div class="mb-3">
                                        <label class="form-label">Confirme seu Email</label>
                                        <input type="email" name="email_confirmacao" class="form-control" required placeholder="Digite o email cadastrado">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nova Senha</label>
                                        <input type="password" name="nova_senha" class="form-control" required minlength="6">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirmar Nova Senha</label>
                                        <input type="password" name="nova_senha_confirmation" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-warning">
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
@endsection
