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
            <div class="alert alert-success alert-dismissible fade show mb-3 alert-styled">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Metric Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-book-open-reader"></i></div>
                    <div class="mc-val">{{ $totalAulas }}</div>
                    <p class="mc-lbl">Total de Aulas</p>
                    <div class="mc-trend"><i class="fas fa-database me-1"></i> cadastradas</div>
                </div>
            </div>
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="mc-val">{{ $aulasAtivas }}</div>
                    <p class="mc-lbl">Aulas Ativas</p>
                    <div class="mc-trend"><i class="fas fa-signal me-1"></i> em andamento</div>
                </div>
            </div>
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="mc-val">{{ $aulasHoje }}</div>
                    <p class="mc-lbl">Aulas Hoje</p>
                    <div class="mc-trend"><i class="fas fa-clock me-1"></i> agendadas</div>
                </div>
            </div>
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-sky shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="mc-val">{{ $totalCursos }}</div>
                    <p class="mc-lbl">Cursos</p>
                    <div class="mc-trend"><i class="fas fa-layer-group me-1"></i> disponíveis</div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-book-open text-primary"></i> Lista de Aulas</h6>
                <a href="{{ route('admin.aulas.create') }}" class="tbl-btn-novo">
                    <i class="fas fa-plus"></i> Nova Aula
                </a>
            </div>

            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Professor</th>
                            <th>Curso</th>
                            <th>Data & Hora</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aulas as $aula)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="tbl-icon-wrap">
                                            <i class="fas fa-chalkboard"></i>
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:.875rem;">{{ $aula->titulo_aulas }}</div>
                                            @if($aula->descricao_aulas)
                                                <div style="font-size:.7rem;color:#94a3b8;max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $aula->descricao_aulas }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($aula->professor)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="prof-avatar-placeholder" style="width:30px;height:30px;font-size:.65rem;border-radius:8px;">{{ strtoupper(substr($aula->professor->nome_professor, 0, 2)) }}</div>
                                            <span style="font-size:.84rem;">{{ $aula->professor->nome_professor }}</span>
                                        </div>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($aula->cursos_aulas)
                                        <span class="tbl-badge">
                                            <i class="fas fa-language me-1"></i>{{ $aula->cursos_aulas }}
                                        </span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($aula->data_aulas)
                                        <div style="font-weight:600;font-size:.84rem;">
                                            <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                            {{ \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    @if($aula->hora_aulas)
                                        <div style="font-size:.72rem;color:#94a3b8;">
                                            <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($aula->hora_aulas)->format('H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($aula->link_teams)
                                        <a href="{{ $aula->link_teams }}" target="_blank" class="tbl-link-btn">
                                            <i class="fas fa-video me-1"></i> Teams
                                        </a>
                                    @else
                                        <span style="color:#cbd5e1;font-size:.8rem;">Sem link</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match(strtoupper($aula->status_aulas)) {
                                            'ATIVO' => 'tbl-status-ativo',
                                            'INATIVO' => 'tbl-status-inativo',
                                            'CANCELADO' => 'tbl-status-cancelado',
                                            default => 'tbl-status-inativo',
                                        };
                                    @endphp
                                    <span class="tbl-status {{ $statusClass }}">
                                        <span class="tbl-status-dot"></span>
                                        {{ $aula->status_aulas }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('admin.aulas.edit', $aula->id_aulas) }}"
                                           class="tbl-btn-editar" title="Editar">
                                            <i class="fas fa-pen-to-square"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.aulas.destroy', $aula->id_aulas) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="tbl-btn-excluir"
                                                    data-titulo="{{ $aula->titulo_aulas }}"
                                                    onclick="abrirModalExcluir(this)">
                                                <i class="fas fa-trash-alt"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="tbl-empty">
                                        <i class="fas fa-book-open tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhuma aula cadastrada ainda</span>
                                        <a href="{{ route('admin.aulas.create') }}" class="tbl-empty-btn">
                                            <i class="fas fa-plus"></i> Cadastrar primeira aula
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Aula', 'delDescricao' => 'Você está prestes a excluir a aula:'])

@endsection
