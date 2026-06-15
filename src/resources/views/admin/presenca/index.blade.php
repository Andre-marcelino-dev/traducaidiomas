@extends('admin.layout.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Presença</h3>
            <small class="text-muted">Gerencie a presença dos alunos por aula</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabela de Aulas --}}
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white">
            <span class="fw-semibold"><i class="bi bi-journal-text me-2"></i>Aulas Cadastradas</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Título</th>
                        <th>Curso</th>
                        <th>Professor</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aulas as $aula)
                    <tr class="align-middle">
                        <td class="ps-4 fw-semibold">{{ $aula->titulo_aulas }}</td>
                        <td><span class="badge bg-primary">{{ $aula->cursos_aulas }}</span></td>
                        <td>{{ $aula->professor->nome_professor ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.presenca.alunos', $aula->id_aulas) }}"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> Registrar Presença
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabela Presença de Hoje --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="bi bi-calendar-check me-2"></i>Presença de Hoje — {{ date('d/m/Y') }}</span>
        </div>
        <div class="card-body p-0">
            @if($registrosHoje->isEmpty())
                <div class="p-4 text-muted">
                    <i class="bi bi-info-circle me-1"></i> Nenhum registro de presença hoje ainda.
                </div>
            @else
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Aluno</th>
                        <th>Aula</th>
                        <th>Curso</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrosHoje as $registro)
                    <tr class="align-middle">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                     style="width:34px;height:34px;font-size:13px;flex-shrink:0">
                                    {{ strtoupper(substr($registro->aluno->nome_aluno ?? '?', 0, 1)) }}
                                </div>
                                {{ $registro->aluno->nome_aluno ?? '-' }}
                            </div>
                        </td>
                        <td>{{ $registro->aula->titulo_aulas ?? '-' }}</td>
                        <td>{{ $registro->aula->cursos_aulas ?? '-' }}</td>
                        <td>
                            @if($registro->status_presenca == 'presente')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Presente</span>
                            @elseif($registro->status_presenca == 'falta')
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Falta</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle me-1"></i>Justificado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

</div>
@endsection