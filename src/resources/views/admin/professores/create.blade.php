@extends('admin.layout.admin')
@section('content')
    <div class="container-fluid">
        <h1>Novo Professor</h1>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.professores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" name="nome_professor" class="form-control" value="{{ old('nome_professor') }}">
                    </div>
                    <div class="mb-3">
                        <label>Especialidade</label>
                        <input type="text" name="especialidade_professor" class="form-control"
                            value="{{ old('especialidade_professor') }}">
                    </div>
                    <div class="mb-3">
                        <label>Experiencia</label>
                        <input type="text" name="experiencia_professor" class="form-control"
                            value="{{ old('experiencia_professor') }}">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email_professor" class="form-control"
                            value="{{ old('email_professor') }}">
                    </div>
                    <div class="mb-3">
                        <label>Curso</label>
                        <input type="text" name="curso_professor" class="form-control"
                            value="{{ old('curso_professor') }}">
                    </div>
                    <div class="mb-3">
                        <label>Nivel</label>
                        <input type="text" name="nivel_professor" class="form-control"
                            value="{{ old('nivel_professor') }}">
                    </div>
                    <div class="mb-3">
                        <label>Telefone</label>
                        <input type="text" name="telefone_professor" class="form-control"
                            value="{{ old('telefone_professor') }}">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha_professor" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-6"> <label class="form-label">Confirmar Senha</label>
                            <input type="password" name="senha_professor_confirmation" class="form-control" required
                                minlength="6">
                        </div>
                    </div>


                    <div class="mb-3">
                        <label>Biografia</label>
                        <textarea name="bio_professor" class="form-control" rows="5">{{ old('bio_professor') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Foto</label>
                        <input type="file" name="foto_professor" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                    <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
