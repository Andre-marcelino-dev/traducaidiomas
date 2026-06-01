@extends('admin.layout.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Professores</h1>
        <a href="{{ route('admin.professores.create') }}" class="btn btn-primary">+ Novo Professor</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Especialidade</th>
                        <th>Email</th>
                        <th>Nivel</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($professores as $professor)
                    <tr>
                        <td>{{ $professor->id_professor }}</td>
                        <td>{{ $professor->nome_professor }}</td>
                        <td>{{ $professor->especialidade_professor }}</td>
                        <td>{{ $professor->email_professor }}</td>
                        <td>{{ $professor->nivel_professor }}</td>
                        <td>
                            <a href="{{ route('admin.professores.edit', $professor->id_professor) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.professores.destroy', $professor->id_professor) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusao?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Nenhum professor cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
