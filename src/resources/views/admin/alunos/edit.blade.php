@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Editar Aluno</h1>
            <a href="{{ route('admin.alunos.index') }}" class="btn btn-secondary">Voltar</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.alunos.update', $aluno->id_aluno) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome_aluno" class="form-control" value="{{ $aluno->nome_aluno }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email_aluno" class="form-control" value="{{ $aluno->email_aluno }}"
                                required>
                        </div>

                        <!-- Senha: deixar em branco para não alterar -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nova Senha <span class="text-muted fs-6">(deixe em branco para não
                                    alterar)</span></label>
                            <input type="password" name="senha_aluno" class="form-control" minlength="6">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar Nova Senha</label>
                            <input type="password" name="senha_aluno_confirmation" class="form-control" minlength="6">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone_aluno" class="form-control"
                                value="{{ $aluno->telefone_aluno }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Curso</label>
                            <input type="text" name="curso_aluno" class="form-control" value="{{ $aluno->curso_aluno }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" name="data_nasc_aluno" class="form-control"
                                value="{{ $aluno->data_nasc_aluno }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nível</label>
                            <select name="nivel_aluno" class="form-select">
                                <option value="Iniciante" {{ $aluno->nivel_aluno == 'Iniciante' ? 'selected' : '' }}>
                                    Iniciante</option>
                                <option value="Intermediário"
                                    {{ $aluno->nivel_aluno == 'Intermediário' ? 'selected' : '' }}>Intermediário</option>
                                <option value="Avançado" {{ $aluno->nivel_aluno == 'Avançado' ? 'selected' : '' }}>
                                    Avançado</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aluno" class="form-select">
                                <option value="EM CURSO" {{ $aluno->status_aluno == 'EM CURSO' ? 'selected' : '' }}>EM
                                    CURSO</option>
                                <option value="CONCLUIDO" {{ $aluno->status_aluno == 'CONCLUIDO' ? 'selected' : '' }}>
                                    CONCLUÍDO</option>
                                <option value="INATIVO" {{ $aluno->status_aluno == 'INATIVO' ? 'selected' : '' }}>INATIVO
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto</label>
                            @if ($aluno->foto_aluno)
                                <div class="mb-2">
                                    <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}" width="60"
                                        height="60" style="object-fit: cover; border-radius: 50%;">
                                </div>
                            @endif
                            <input type="file" name="foto_aluno" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection
