@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <h1>Editar Professor</h1>



        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.professores.update', $professor->id_professor) }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" name="nome_professor" class="form-control"
                            value="{{ old('nome_professor', $professor->nome_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Especialidade</label>
                        <input type="text" name="especialidade_professor" class="form-control"
                            value="{{ old('especialidade_professor', $professor->especialidade_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Experiência</label>
                        <input type="text" name="experiencia_professor" class="form-control"
                            value="{{ old('experiencia_professor', $professor->experiencia_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email_professor" class="form-control"
                            value="{{ old('email_professor', $professor->email_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Curso</label>
                        <input type="text" name="curso_professor" class="form-control"
                            value="{{ old('curso_professor', $professor->curso_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nível</label>
                        <input type="text" name="nivel_professor" class="form-control"
                            value="{{ old('nivel_professor', $professor->nivel_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone_professor" class="form-control"
                            value="{{ old('telefone_professor', $professor->telefone_professor) }}">
                    </div>

                    <div class="mb-3">
                        <label>Biografia</label>
                        <textarea name="bio_professor" class="form-control" rows="5">{{ old('bio_professor', $professor->bio_professor ) }}</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha_professor" class="form-control" minlength="6"
                                placeholder="Deixe em branco para não alterar">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Senha</label>
                            <input type="password" name="senha_professor_confirmation" class="form-control" minlength="6">
                        </div>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Foto Atual</label>
                        <br>

                        @if ($professor->foto_professor)
                            <img src="{{ asset('traducaidiomas/professor/' . $professor->foto_professor) }}"
                                alt="{{ $professor->nome_professor }}" width="50" height="50"
                                style="object-fit: cover; border-radius: 50%;">
                            <p class="text-muted">Nenhuma foto cadastrada.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nova Foto</label>
                        <input type="file" name="foto_professor" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Salvar Alterações
                    </button>

                    <a href="{{ route('admin.professores.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>

                </form>

            </div>
        </div>
    </div>
    @include('partials.script') {{-- ← adicione aqui --}}
@endsection
