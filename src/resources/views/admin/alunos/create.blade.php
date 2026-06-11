@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Novo Aluno</h1>
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
                <form action="{{ route('admin.alunos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome_aluno" class="form-control" value="{{ old('nome_aluno') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email_aluno" class="form-control" value="{{ old('email_aluno') }}"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha_aluno" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirmar Senha</label>
                            <input type="password" name="senha_aluno_confirmation" class="form-control" required
                                minlength="6">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone_aluno" class="form-control"
                                value="{{ old('telefone_aluno') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" name="data_nasc_aluno" class="form-control"
                                value="{{ old('data_nasc_aluno') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Curso</label>
                            <input type="text" name="curso_aluno" class="form-control" value="{{ old('curso_aluno') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nível</label>
                            <select name="nivel_aluno" class="form-select">
                                <option value="Iniciante">Iniciante</option>
                                <option value="Intermediário">Intermediário</option>
                                <option value="Avançado">Avançado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status_aluno" class="form-select">
                                <option value="EM CURSO">EM CURSO</option>
                                <option value="CONCLUÍDO">CONCLUÍDO</option>
                                <option value="INATIVO">INATIVO</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto_aluno" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">Cadastrar Aluno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
