@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Presença</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Presença</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Aulas Cadastradas --}}
        <div class="d-card fade-up mb-4">
            <div class="d-card-header">
                <h6><i class="fas fa-book-open text-primary"></i> Aulas Cadastradas</h6>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Curso</th>
                            <th>Professor</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aulas as $aula)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="tbl-icon-wrap"><i class="fas fa-chalkboard"></i></div>
                                    <span style="font-weight:600;">{{ $aula->titulo_aulas }}</span>
                                </div>
                            </td>
                            <td><span class="tbl-badge">{{ $aula->cursos_aulas }}</span></td>
                            <td>{{ $aula->professor->nome_professor ?? '—' }}</td>
                            <td>
                                <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                {{ \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.presenca.alunos', $aula->id_aulas) }}" class="tbl-btn-success">
                                    <i class="fas fa-clipboard-check"></i> Registrar Presença
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="tbl-empty">
                                    <i class="fas fa-book-open tbl-empty-icon"></i>
                                    <span class="tbl-empty-text">Nenhuma aula cadastrada ainda.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Presença de Hoje --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-calendar-check text-success"></i> Presença de Hoje — {{ date('d/m/Y') }}</h6>
            </div>
            @if($registrosHoje->isEmpty())
                <div class="tbl-empty">
                    <i class="fas fa-clipboard-list tbl-empty-icon"></i>
                    <span class="tbl-empty-text">Nenhum registro de presença hoje ainda.</span>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Aula</th>
                                <th>Curso</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrosHoje as $registro)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="prof-avatar-placeholder">{{ strtoupper(substr($registro->aluno->nome_aluno ?? '?', 0, 1)) }}</div>
                                        <span style="font-weight:600;">{{ $registro->aluno->nome_aluno ?? '—' }}</span>
                                    </div>
                                </td>
                                <td>{{ $registro->aula->titulo_aulas ?? '—' }}</td>
                                <td>{{ $registro->aula->cursos_aulas ?? '—' }}</td>
                                <td>
                                    @if($registro->status_presenca == 'presente')
                                        <span class="tbl-status tbl-status-presente"><span class="tbl-status-dot"></span> Presente</span>
                                    @elseif($registro->status_presenca == 'falta')
                                        <span class="tbl-status tbl-status-falta"><span class="tbl-status-dot"></span> Falta</span>
                                    @else
                                        <span class="tbl-status tbl-status-justificado"><span class="tbl-status-dot"></span> Justificado</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
