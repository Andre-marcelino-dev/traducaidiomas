@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">Justificativas de Falta</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Justificativas de Falta</li>
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
            @if (session('error'))
                <div class="alert alert-danger alert-styled alert-dismissible fade show mb-3">
                    <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- CARDS (também funcionam como filtro) --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <a href="{{ route('admin.justificativas.index', ['status' => 'pendente']) }}" class="text-decoration-none">
                        <div class="mc mc-amber shadow">
                            <div class="mc-icon"><i class="fas fa-hourglass-half"></i></div>
                            <div class="mc-val">{{ $totais['pendente'] ?? 0 }}</div>
                            <p class="mc-lbl">Aguardando resposta</p>
                            <div class="mc-trend"><i class="fas fa-bell me-1"></i>pendentes</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <a href="{{ route('admin.justificativas.index', ['status' => 'aceita']) }}" class="text-decoration-none">
                        <div class="mc mc-green shadow">
                            <div class="mc-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="mc-val">{{ $totais['aceita'] ?? 0 }}</div>
                            <p class="mc-lbl">Aceitas</p>
                            <div class="mc-trend"><i class="fas fa-file-circle-check me-1"></i>falta justificada</div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <a href="{{ route('admin.justificativas.index', ['status' => 'recusada']) }}" class="text-decoration-none">
                        <div class="mc mc-rose shadow">
                            <div class="mc-icon"><i class="fas fa-times-circle"></i></div>
                            <div class="mc-val">{{ $totais['recusada'] ?? 0 }}</div>
                            <p class="mc-lbl">Recusadas</p>
                            <div class="mc-trend"><i class="fas fa-ban me-1"></i>falta mantida</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- TABELA --}}
            <div class="row fade-up">
                <div class="col-12">
                    <div class="d-card">
                        <div class="d-card-header">
                            <h6>
                                <i class="fas fa-file-signature text-primary"></i>
                                @switch($filtro)
                                    @case('aceita') Justificativas aceitas @break
                                    @case('recusada') Justificativas recusadas @break
                                    @default Justificativas aguardando resposta
                                @endswitch
                            </h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table recent-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Aluno</th>
                                        <th>Aula</th>
                                        <th>Motivo</th>
                                        <th>Enviada em</th>
                                        <th class="text-center">{{ $filtro === 'pendente' ? 'Ações' : 'Resposta' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($justificativas as $j)
                                        <tr>
                                            <td>{{ $j->presenca?->aluno?->nome_aluno ?? '—' }}</td>
                                            <td>
                                                <div style="font-weight:600;font-size:.875rem;">{{ $j->presenca?->aula?->titulo_aulas ?? '—' }}</div>
                                                <div style="font-size:.72rem;color:#94a3b8;">
                                                    {{ $j->presenca ? \Carbon\Carbon::parse($j->presenca->data_registro_presenca)->format('d/m/Y') : '' }}
                                                </div>
                                            </td>
                                            <td style="max-width:360px;white-space:pre-line;font-size:.85rem;">{{ $j->motivo_justificativa }}</td>
                                            <td>{{ $j->created_at?->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                @if($j->status_justificativa === 'pendente')
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <form action="{{ route('admin.justificativas.aceitar', $j->id_justificativa) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="tbl-btn-success">
                                                                <i class="fas fa-check"></i> Aceitar
                                                            </button>
                                                        </form>
                                                        <button type="button" class="tbl-btn-excluir btn-recusar"
                                                            data-bs-toggle="modal" data-bs-target="#modalRecusar"
                                                            data-action="{{ route('admin.justificativas.recusar', $j->id_justificativa) }}"
                                                            data-aluno="{{ $j->presenca?->aluno?->nome_aluno ?? 'aluno' }}">
                                                            <i class="fas fa-times"></i> Recusar
                                                        </button>
                                                    </div>
                                                @elseif($j->status_justificativa === 'aceita')
                                                    <span class="tbl-badge">Aceita</span>
                                                    <div style="font-size:.72rem;color:#94a3b8;">{{ $j->respondido_em?->format('d/m/Y H:i') }}</div>
                                                @else
                                                    <span class="tbl-badge rose">Recusada</span>
                                                    @if($j->resposta_professor)
                                                        <div style="font-size:.75rem;color:#64748b;">{{ $j->resposta_professor }}</div>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="tbl-empty">
                                                    <i class="fas fa-file-signature tbl-empty-icon"></i>
                                                    <span class="tbl-empty-text">Nenhuma justificativa por aqui.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($justificativas->hasPages())
                            <div class="card-footer">
                                {{ $justificativas->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL RECUSAR --}}
    <div class="modal fade" id="modalRecusar" tabindex="-1" aria-labelledby="modalRecusarTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="" class="modal-content" id="formRecusar">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRecusarTitulo">Recusar justificativa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">A falta de <strong id="recusarAluno"></strong> será mantida.</p>
                    <label for="resposta_professor" class="form-label">Motivo da recusa (opcional, o aluno verá)</label>
                    <textarea name="resposta_professor" id="resposta_professor" class="form-control" rows="3" maxlength="500"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Recusar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.btn-recusar').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('formRecusar').action = btn.dataset.action;
                document.getElementById('recusarAluno').textContent = btn.dataset.aluno;
            });
        });
    </script>

@endsection
