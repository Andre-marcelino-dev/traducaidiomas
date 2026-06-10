@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Matrículas</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Matrículas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="mc mc-blue shadow">
                        <div class="mc-val">{{ $totalMatriculas ?? 0 }}</div>
                        <p>Total</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="mc mc-green shadow">
                        <div class="mc-val">{{ $alunosAtivos ?? 0 }}</div>
                        <p>Alunos Ativos</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="mc mc-rose shadow">
                        <div class="mc-val">{{ $alunosInativos ?? 0 }}</div>
                        <p>Alunos Inativos</p>
                    </div>
                </div>
            </div>

            {{-- FORMULÁRIO (novo ou editar) --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ $matriculaEdit ? 'Editar Matrícula' : 'Nova Matrícula' }}</h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ $matriculaEdit ? route('admin.matriculas.update', $matriculaEdit->id_matricula) : route('admin.matriculas.store') }}"
                        method="POST">
                        @csrf
                        @if ($matriculaEdit)
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Aluno</label>
                                <select name="id_aluno" class="form-control">
                                    <option value="">Selecione...</option>
                                    @foreach ($alunos as $aluno)
                                        <option value="{{ $aluno->id_aluno }}"
                                            {{ $matriculaEdit?->id_aluno == $aluno->id_aluno ? 'selected' : '' }}>
                                            {{ $aluno->nome_aluno }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Curso</label>
                                <select name="id_curso" class="form-control">
                                    <option value="">Selecione...</option>
                                    @foreach ($cursos as $curso)
                                        <option value="{{ $curso->id_curso }}"
                                            {{ $matriculaEdit?->id_curso == $curso->id_curso ? 'selected' : '' }}>
                                            {{ $curso->nome_curso }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Nível</label>
                                <select name="id_nivel" class="form-control">
                                    <option value="">Selecione...</option>
                                    @foreach ($niveis as $nivel)
                                        <option value="{{ $nivel->id_nivel }}"
                                            {{ $matriculaEdit?->id_nivel == $nivel->id_nivel ? 'selected' : '' }}>
                                            {{ $nivel->nome_nivel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Data</label>
                                <input type="date" name="data_matricula" class="form-control"
                                    value="{{ $matriculaEdit?->data_matricula ?? '' }}">
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ $matriculaEdit ? 'Salvar' : 'Cadastrar' }}
                            </button>
                            @if ($matriculaEdit)
                                <a href="{{ route('admin.matriculas.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABELA --}}
            <div class="card">
                <div class="card-header">
                    <h5>Lista de Matrículas</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Curso</th>
                                <th>Nível</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($matriculas as $matricula)
                                <tr>
                                    <td>{{ $matricula->aluno?->nome_aluno ?? '—' }}</td>
                                    <td>{{ $matricula->curso?->nome_curso ?? '—' }}</td>
                                    <td>{{ $matricula->nivel?->nome_nivel ?? '—' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($matricula->data_matricula)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.matriculas.edit', $matricula->id_matricula) }}"
                                            class="btn btn-warning btn-sm">
                                            Editar
                                        </a>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalExcluir" data-id="{{ $matricula->id_matricula }}"
                                            data-nome="{{ $matricula->aluno?->nome_aluno }}">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nenhuma matrícula encontrada</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="modalExcluir">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5>Excluir</h5>
                </div>
                <div class="modal-body text-center">
                    <p>Excluir matrícula do aluno:</p>
                    <strong id="nomeAlunoModal"></strong>
                </div>
                <div class="modal-footer">
                    <form id="formExcluir" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Sim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('modalExcluir').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nome = button.getAttribute('data-nome');

            document.getElementById('nomeAlunoModal').textContent = nome;
            document.getElementById('formExcluir').action = `/admin/matriculas/${id}`;
        });
    </script>

@endsection