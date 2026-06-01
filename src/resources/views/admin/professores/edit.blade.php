@extends('admin.layout.admin')
@section('content')
<div class="container-fluid">
    <h1>Editar Professor</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.professores.update', $professor->id_professor) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Nome</label>
                    <input type="text" name="nome_professor" class="form-control" value="{{ $professor->nome_professor }}">
                </div>
                <div class="mb-3">
                    <label>Especialidade</label>
                    <input type="text" name="especialidade_professor" class="form-control" value="{{ $professor->especialidade_professor }}">
                </div>
                <div class="mb-3">
                    <label>Experiencia</label>
                    <input type="text" name="experiencia_professor" class="form-control" value="{{ $professor->experiencia_professor }}">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email_professor" class="form-control" value="{{ $professor->email_professor }}">
                </div>
                <div class="mb-3">
                    <label>Curso</label>
                    <input type="text" name="curso_professor" class="form-control" value="{{ $professor->curso_professor }}">
                </div>
                <div class="mb-3">
                    <label>Nivel</label>
                    <input type="text" name="nivel_professor" class="form-control" value="{{ $professor->nivel_professor }}">
                </div>
                <div class="mb-3">
                    <label>Telefone</label>
                    <input type="text" name="telefone_professor" class="form-control" value="{{ $professor->telefone_professor }}">
                </div>
                <div class="mb-3">
                    <label>Nova Senha (opcional)</label>
                    <input type="password" name="senha_professor" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Foto</label>
                    <input type="file" name="foto_professor" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection
