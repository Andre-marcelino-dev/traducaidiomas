@extends('admin.layout.admin')

@section('content')
<div class="container-fluid">

    <h1>Nova Aula</h1>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.aulas.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Título da Aula</label>
                    <input type="text"
                           name="titulo_aulas"
                           class="form-control"
                           value="{{ old('titulo_aulas') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao_aulas"
                              class="form-control"
                              rows="4"
                              required>{{ old('descricao_aulas') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Data da Aula</label>
                    <input type="date"
                           name="data_aulas"
                           class="form-control"
                           value="{{ old('data_aulas') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hora da Aula</label>
                    <input type="time"
                           name="hora_aulas"
                           class="form-control"
                           value="{{ old('hora_aulas') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Professor</label>

                    <select name="id_professor" class="form-control" required>
                        <option value="">Selecione um professor</option>

                        @foreach($professores as $professor)
                            <option value="{{ $professor->id_professor }}">
                                {{ $professor->nome_professor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Curso</label>

                    <select name="id_curso" class="form-control" required>
                        <option value="">Selecione um curso</option>

                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id_curso }}">
                                {{ $curso->nome_curso }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nome do Curso</label>
                    <input type="text"
                           name="cursos_aulas"
                           class="form-control"
                           value="{{ old('cursos_aulas') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Link Teams</label>
                    <input type="url"
                           name="link_teams"
                           class="form-control"
                           value="{{ old('link_teams') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>

                    <select name="status_aulas" class="form-control" required>
                        <option value="ATIVO">ATIVO</option>
                        <option value="INATIVO">INATIVO</option>
                        <option value="CANCELADO">CANCELADO</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    Salvar Aula
                </button>

                <a href="{{ route('admin.aulas.index') }}"
                   class="btn btn-secondary">
                    Voltar
                </a>

            </form>

        </div>
    </div>

</div>
@endsection