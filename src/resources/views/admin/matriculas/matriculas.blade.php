@extends('admin.layout.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $matriculaEdit ? 'Editar Matrícula' : 'Matrículas de Alunos' }}</h1>
        @if ($matriculaEdit)
        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-secondary">Nova matrícula</a>
        @endif
    </div>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form
                action="{{ $matriculaEdit ? route('admin.matriculas.update', $matriculaEdit->id_matricula) : route('admin.matriculas.store') }}"
                method="POST">
                @csrf
                @if ($matriculaEdit)
                @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Aluno</label>
                        <select name="id_aluno" class="form-select" required>
                            <option value="">Selecione um aluno</option>
                            @foreach ($alunos as $aluno)
                            <option value="{{ $aluno->id_aluno }}"
                                {{ (string) old('id_aluno', $matriculaEdit->id_aluno ?? '') === (string) $aluno->id_aluno ? 'selected' : '' }}>
                                {{ $aluno->nome_aluno }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Curso</label>
                        <select name="id_curso" class="form-select" required>
                            <option value="">Selecione um curso</option>
                            @foreach ($cursos as $curso)
                            <option value="{{ $curso->id_curso }}"
                                {{ (string) old('id_curso', $matriculaEdit->id_curso ?? '') === (string) $curso->id_curso ? 'selected' : '' }}>
                                {{ $curso->nome_curso }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Nível</label>
                        <select name="id_nivel" class="form-select" required>
                            <option value="">Selecione um nível</option>
                            @foreach ($niveis as $nivel)
                            <option value="{{ $nivel->id_nivel }}"
                                {{ (string) old('id_nivel', $matriculaEdit->id_nivel ?? '') === (string) $nivel->id_nivel ? 'selected' : '' }}>
                                {{ ucfirst($nivel->nome_nivel) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Data da matrícula</label>
                        <input type="date" name="data_matricula" class="form-control"
                            value="{{ old('data_matricula', isset($matriculaEdit) ? \Illuminate\Support\Carbon::parse($matriculaEdit->data_matricula)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                            required>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        {{ $matriculaEdit ? 'Salvar alterações' : 'Cadastrar matrícula' }}
                    </button>
                    @if ($matriculaEdit)
                    <a href="{{ route('admin.matriculas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <input type="text" id="pesquisaMatricula" class="form-control mb-3"
                placeholder="Pesquisar por aluno, curso ou nível...">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Aluno</th>
                        <th>Curso</th>
                        <th>Nível</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matriculas as $matricula)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $matricula->aluno->nome_aluno ?? '—' }}</td>
                        <td>{{ $matricula->curso->nome_curso ?? '—' }}</td>
                        <td>{{ isset($matricula->nivel->nome_nivel) ? ucfirst($matricula->nivel->nome_nivel) : '—' }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($matricula->data_matricula)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.matriculas.edit', $matricula->id_matricula) }}"
                                class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.matriculas.destroy', $matricula->id_matricula) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Deseja excluir esta matrícula?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhuma matrícula cadastrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const pesquisaMatricula = document.getElementById('pesquisaMatricula');
    const linhasMatricula = document.querySelectorAll('tbody tr');

    pesquisaMatricula.addEventListener('keyup', function () {
        const texto = this.value.toLowerCase();

        linhasMatricula.forEach(function (linha) {
            const conteudo = linha.textContent.toLowerCase();

            linha.style.display = conteudo.includes(texto) ? '' : 'none';
        });
    });
</script>
@endsection