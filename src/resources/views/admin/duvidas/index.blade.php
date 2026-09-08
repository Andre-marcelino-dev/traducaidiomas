@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">Dúvidas dos Alunos</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dúvidas dos Alunos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-envelope"></i></div>
                        <div class="mc-val">{{ $totalDuvidas }}</div>
                        <p class="mc-lbl">Total de Dúvidas</p>
                        <div class="mc-trend"><i class="fas fa-comment me-1"></i>recebidas</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-clock"></i></div>
                        <div class="mc-val">{{ $totalPendentes }}</div>
                        <p class="mc-lbl">Não Respondidas</p>
                        <div class="mc-trend"><i class="fas fa-hourglass-half me-1"></i>aguardando resposta</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green shadow">
                        <div class="mc-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="mc-val">{{ $totalRespondidas }}</div>
                        <p class="mc-lbl">Respondidas</p>
                        <div class="mc-trend"><i class="fas fa-reply me-1"></i>já atendidas</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- FILTRO --}}
    <div class="row fade-up mb-3">
        <div class="col-12">
            <div class="d-card">
                <div class="card-body p-3">
                    <form action="{{ route('admin.duvidas.index') }}" method="GET" class="row g-2">
                        <div class="col-sm-4">
                            <select name="status_duvida" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Todos os status</option>
                                <option value="pendente" {{ request('status_duvida') == 'pendente' ? 'selected' : '' }}>Não respondidas</option>
                                <option value="respondida" {{ request('status_duvida') == 'respondida' ? 'selected' : '' }}>Respondidas</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="row fade-up">
        <div class="col-12">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-envelope text-primary"></i> Mensagens dos Alunos</h6>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Assunto</th>
                                <th>Status</th>
                                <th>Enviado em</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($duvidas as $duvida)
                                <tr>
                                    <td>{{ $duvida->aluno->nome_aluno ?? '—' }}</td>
                                    <td>
                                        <div style="font-weight:600;font-size:.875rem;">{{ $duvida->assunto_duvida }}</div>
                                        <div style="font-size:.72rem;color:#94a3b8;">{{ Str::limit($duvida->mensagem_duvida, 60) }}</div>
                                    </td>
                                    <td>
                                        @if($duvida->status_duvida === 'respondida')
                                            <span class="tbl-badge">Respondida</span>
                                        @else
                                            <span class="tbl-badge amber">Não respondida</span>
                                        @endif
                                    </td>
                                    <td>{{ $duvida->criado_em?->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('admin.duvidas.show', $duvida->id_duvida) }}" class="tbl-btn-ver">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <form action="{{ route('admin.duvidas.desativar', $duvida->id_duvida) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Desativar a dúvida de {{ $duvida->aluno->nome_aluno ?? 'aluno' }}? Ela deixará de aparecer nas listas.');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="tbl-btn-excluir">
                                                    <i class="fas fa-ban"></i> Desativar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tbl-empty">
                                            <i class="fas fa-envelope tbl-empty-icon"></i>
                                            <span class="tbl-empty-text">Nenhuma dúvida recebida ainda.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($duvidas->hasPages())
                    <div class="card-footer">
                        {{ $duvidas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
