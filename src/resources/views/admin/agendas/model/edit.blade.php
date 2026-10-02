@extends('admin.layout.admin')

@section('content')

<div class="container-fluid">
    <h3 class="mb-3">Editar Agendamento</h3>

    <form action="{{ route('admin.agendas.update', $agenda->id_agenda) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo_agenda" class="form-control"
                   value="{{ old('titulo_agenda', $agenda->titulo_agenda) }}">
        </div>

        <div class="mb-3">
            <label>Aluno</label>
            <select name="id_aluno" class="form-control">
                @foreach($alunos as $aluno)
                    <option value="{{ $aluno->id_aluno }}"
                        {{ $agenda->id_aluno == $aluno->id_aluno ? 'selected' : '' }}>
                        {{ $aluno->nome_aluno }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Professor</label>
            <select name="id_professor" class="form-control">
                @foreach($professores as $professor)
                    <option value="{{ $professor->id_professor }}"
                        {{ $agenda->id_professor == $professor->id_professor ? 'selected' : '' }}>
                        {{ $professor->nome_professor }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data_evento_agenda" class="form-control"
                   value="{{ $agenda->data_evento_agenda?->format('Y-m-d') }}">
        </div>

        <div class="mb-3">
            <label>Hora início</label>
            <input type="time" name="hora_inicio_agenda" class="form-control"
                   value="{{ $agenda->hora_inicio_agenda }}">
        </div>

        <div class="mb-3">
            <label>Hora fim</label>
            <input type="time" name="hora_fim_agenda" class="form-control"
                   value="{{ $agenda->hora_fim_agenda }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status_agenda" class="form-control">
                <option value="pendente" {{ $agenda->status_agenda == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="confirmado" {{ $agenda->status_agenda == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                <option value="cancelado" {{ $agenda->status_agenda == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                <option value="reagendado" {{ $agenda->status_agenda == 'reagendado' ? 'selected' : '' }}>Reagendado</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Descrição</label>
            <textarea name="descricao_agenda" class="form-control">{{ old('descricao_agenda', $agenda->descricao_agenda) }}</textarea>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">
            Salvar
        </button>

        <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary">
            Cancelar
        </a>
    </form>
</div>

@endsection
