@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Aulas</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Aulas</li>
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

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Lista de Aulas</h5>
                <a href="{{ route('admin.aulas.create') }}" class="btn btn-success btn-sm">
                    Nova Aula
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Professor</th>
                            <th>Curso</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aulas as $aula)
                            <tr>
                                <td>{{ $aula->titulo_aulas }}</td>
                                <td>{{ $aula->professor?->nome_professor ?? '—' }}</td>
                                <td>{{ $aula->cursos_aulas ?? '—' }}</td>
                                <td>{{ $aula->data_aulas ? \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') : '—' }}</td>
                                <td>{{ $aula->hora_aulas ? \Carbon\Carbon::parse($aula->hora_aulas)->format('H:i') : '—' }}</td>
                                <td>
                                    @if ($aula->link_teams)
                                        <a href="{{ $aula->link_teams }}" target="_blank">Abrir</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $aula->status_aulas }}</td>
                                <td>
                                    <a href="{{ route('admin.aulas.edit', $aula->id_aulas) }}" class="btn btn-warning btn-sm">Editar</a>

                                    <form action="{{ route('admin.aulas.destroy', $aula->id_aulas) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Excluir esta aula?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Nenhuma aula cadastrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
