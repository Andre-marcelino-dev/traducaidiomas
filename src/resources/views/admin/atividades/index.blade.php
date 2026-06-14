@extends('admin.layout.admin')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Atividades</h3></div>
            <div class="col-sm-6">
                <a href="{{ route('admin.atividades.create') }}" class="btn btn-success float-end">
                    <i class="fas fa-plus me-1"></i> Nova Atividade
                </a>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Curso</th>
                            <th>Entrega</th>
                            <th>Respostas</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atividades as $i => $atividade)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $atividade->titulo_atividade }}</td>
                            <td>{{ $atividade->curso?->nome_curso }}</td>
                            <td>{{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-primary">{{ $atividade->respostas->count() }} enviadas</span></td>
                            <td><span class="badge bg-success">{{ $atividade->status_atividade }}</span></td>
                            <td>
                                <a href="{{ route('admin.atividades.show', $atividade->id_atividade) }}" class="btn btn-sm btn-info">Ver</a>
                                <form action="{{ route('admin.atividades.destroy', $atividade->id_atividade) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Nenhuma atividade cadastrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
