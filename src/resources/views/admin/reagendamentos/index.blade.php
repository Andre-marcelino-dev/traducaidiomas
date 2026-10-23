@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Reagendamentos</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reagendamentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow">
                    <div class="mc-icon"><i class="bi bi-clock"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'pendente')->count() }}</div>
                    <p class="mc-lbl">Pendentes</p>
                    <div class="mc-trend"><i class="bi bi-hourglass-split me-1"></i>aguardando resposta</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow">
                    <div class="mc-icon"><i class="bi bi-check-circle"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'confirmado')->count() }}</div>
                    <p class="mc-lbl">Confirmados</p>
                    <div class="mc-trend"><i class="bi bi-check-lg me-1"></i>aprovados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow">
                    <div class="mc-icon"><i class="bi bi-x-circle"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'recusado')->count() }}</div>
                    <p class="mc-lbl">Recusados</p>
                    <div class="mc-trend"><i class="bi bi-slash-circle me-1"></i>negados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow">
                    <div class="mc-icon"><i class="bi bi-calendar3"></i></div>
                    <div class="mc-val">{{ $reagendamentos->count() }}</div>
                    <p class="mc-lbl">Total</p>
                    <div class="mc-trend"><i class="bi bi-database me-1"></i>registrados</div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;border:none;">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;border:none;">
                <i class="bi bi-exclamation-triangle me-2"></i>Preencha todos os campos obrigatórios.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- TABELA --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="bi bi-calendar-event text-warning"></i> Solicitações de Reagendamento</h6>
                <button type="button" class="tbl-btn-novo" data-bs-toggle="modal" data-bs-target="#modalNovoReagendamentoAdmin">
                    <i class="bi bi-plus-lg"></i> Novo Reagendamento
                </button>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Aula Original</th>
                            <th>Motivo</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reagendamentos as $reagendamento)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="prof-avatar-placeholder" style="width:32px;height:32px;font-size:.65rem;">
                                            {{ strtoupper(substr($reagendamento->aluno?->nome_aluno ?? '?', 0, 2)) }}
                                        </div>
                                        <span style="font-weight:600;">{{ $reagendamento->aluno?->nome_aluno ?? 'Aluno não encontrado' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight:600;font-size:.85rem;">{{ $reagendamento->aula?->titulo_aulas ?? 'Aula não encontrada' }}</div>
                                    <div style="font-size:.72rem;color:#94a3b8;">
                                        <i class="bi bi-calendar-date me-1"></i>
                                        @if($reagendamento->aula && $reagendamento->aula->data_aulas)
                                            {{ \Carbon\Carbon::parse($reagendamento->aula->data_aulas)->format('d/m/Y') }}
                                            {{ $reagendamento->aula->hora_aulas ? \Carbon\Carbon::parse($reagendamento->aula->hora_aulas)->format('H:i') : '' }}
                                        @else
                                            Sem data
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0" style="font-size:.85rem;color:#64748b;max-width:300px;line-height:1.4;">{{ $reagendamento->motivo }}</p>
                                    @if($reagendamento->data_sugerida)
                                        <div style="font-size:.72rem;color:#059669;margin-top:4px;">
                                            <i class="bi bi-lightbulb me-1"></i>
                                            Aluno sugeriu: {{ \Carbon\Carbon::parse($reagendamento->data_sugerida)->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($reagendamento->status === 'pendente')
                                        <span class="tbl-status tbl-status-pendente"><span class="tbl-status-dot"></span> Pendente</span>
                                    @elseif($reagendamento->status === 'confirmado')
                                        <span class="tbl-status tbl-status-confirmado"><span class="tbl-status-dot"></span> Confirmado</span>
                                        @if($reagendamento->data_nova)
                                            <div style="font-size:.7rem;color:#94a3b8;margin-top:2px;">
                                                {{ \Carbon\Carbon::parse($reagendamento->data_nova)->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="tbl-status tbl-status-cancelado"><span class="tbl-status-dot"></span> Recusado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center gap-2" style="white-space:nowrap;">
                                        @if ($reagendamento->status === 'pendente')
                                            <button type="button" class="tbl-btn-success" style="box-shadow:none;" title="Confirmar e escolher a nova data"
                                                onclick="abrirModalConfirmar({{ $reagendamento->id }}, @js($reagendamento->aula?->titulo_aulas), @js($reagendamento->aluno?->nome_aluno), @js($reagendamento->data_sugerida ? \Carbon\Carbon::parse($reagendamento->data_sugerida)->format('Y-m-d') : null), @js($reagendamento->data_sugerida ? \Carbon\Carbon::parse($reagendamento->data_sugerida)->format('H:i') : null))">
                                                <i class="bi bi-calendar-check"></i> Confirmar
                                            </button>
                                            <form action="{{ route('admin.reagendamentos.recusar', $reagendamento->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="tbl-btn-excluir" style="box-shadow:none;" title="Recusar o pedido do aluno">
                                                    <i class="bi bi-x-circle"></i> Recusar
                                                </button>
                                            </form>
                                        @else
                                            <span style="font-size:.75rem;color:#94a3b8;align-self:center;"><i class="bi bi-check2-all"></i> Concluído</span>
                                        @endif
                                        {{-- Cinza para não confundir com "Recusar" (vermelho): excluir só apaga o registro da lista. --}}
                                        <form action="{{ route('admin.reagendamentos.destroy', $reagendamento->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="tbl-btn-excluir" style="background:#f1f5f9;color:#475569;box-shadow:none;" title="Apagar este registro"
                                                data-nome="{{ $reagendamento->aluno?->nome_aluno }}"
                                                onclick="abrirModalExcluir(this)">
                                                <i class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="tbl-empty">
                                        <i class="bi bi-calendar-check tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhuma solicitação de reagendamento.</span>
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

{{-- MODAL CONFIRMAR COM DATA --}}
<div class="modal fade" id="modalConfirmarData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:16px 16px 0 0;padding:1.5rem 1.5rem 1rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#059669;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-calendar-check text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" style="color:#065f46;">Confirmar Reagendamento</h5>
                        <small id="modalConfirmarSubtitulo" style="color:#047857;">Defina a nova data e hora</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formConfirmarData" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="p-3 rounded mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                        <small class="text-muted d-block" style="font-size:.8rem;">Aluno</small>
                        <span class="fw-semibold" id="modalConfirmarAluno" style="color:#065f46;"></span>
                        <small class="text-muted d-block mt-1" style="font-size:.8rem;">Aula</small>
                        <span class="fw-semibold" id="modalConfirmarAula" style="color:#065f46;"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="bi bi-calendar me-1 text-success"></i> Nova Data
                        </label>
                        <input type="date" name="nova_data_aulas" id="novaDataAulas" class="form-control" style="border-radius:10px;" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="bi bi-clock me-1 text-success"></i> Nova Hora
                        </label>
                        <input type="time" name="nova_hora_aulas" id="novaHoraAulas" class="form-control" style="border-radius:10px;" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="del-btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="tbl-btn-success">
                        <i class="bi bi-send"></i> Confirmar e Notificar Aluno
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL NOVO REAGENDAMENTO (ADMIN) --}}
<div class="modal fade" id="modalNovoReagendamentoAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-radius:16px 16px 0 0;padding:1.5rem 1.5rem 1rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-calendar-plus text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" style="color:#92400e;">Lançar Reagendamento</h5>
                        <small style="color:#b45309;">Cadastrar alteração manual de aula</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reagendamentos.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="bi bi-person me-1 text-warning"></i> Selecione o Aluno
                        </label>
                        <select name="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror" style="border-radius:10px;" required>
                            <option value="">Escolha o aluno...</option>
                            @isset($alunos)
                                @foreach ($alunos as $aluno)
                                    <option value="{{ $aluno->id_aluno }}" {{ old('aluno_id') == $aluno->id_aluno ? 'selected' : '' }}>{{ $aluno->nome_aluno }}</option>
                                @endforeach
                            @endisset
                        </select>
                        @error('aluno_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="bi bi-book me-1 text-warning"></i> Selecione a Aula
                        </label>
                        <select name="aula_id" class="form-select @error('aula_id') is-invalid @enderror" style="border-radius:10px;" required>
                            <option value="">Escolha a aula...</option>
                            @isset($aulas)
                                @foreach ($aulas as $aula)
                                    <option value="{{ $aula->id_aulas }}" {{ old('aula_id') == $aula->id_aulas ? 'selected' : '' }}>
                                        {{ $aula->titulo_aulas }} — {{ $aula->data_aulas ? \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') : 'Sem data' }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        @error('aula_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="bi bi-chat-left-text me-1 text-warning"></i> Observações / Motivo
                        </label>
                        <textarea name="motivo" id="motivoAdmin" rows="3"
                            class="form-control @error('motivo') is-invalid @enderror"
                            style="border-radius:10px;resize:none;"
                            placeholder="Escreva a justificativa..."
                            maxlength="500" required>{{ old('motivo') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted" id="motivoAdminCount">0/500</small>
                        </div>
                        @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="del-btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="tbl-btn-novo" style="border-radius:10px;background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <i class="bi bi-save"></i> Salvar Alteração
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Reagendamento', 'delDescricao' => 'Você está prestes a excluir o reagendamento de:'])

<script>
    function abrirModalConfirmar(id, aula, aluno, dataSugerida, horaSugerida) {
        document.getElementById('modalConfirmarAluno').textContent = aluno;
        document.getElementById('modalConfirmarAula').textContent  = aula;
        document.getElementById('formConfirmarData').action = '/admin/reagendamentos/' + id + '/aceitar';
        // Já vem preenchido com a data que o aluno sugeriu (se ele sugeriu
        // uma); o professor ainda pode trocar antes de confirmar.
        document.getElementById('novaDataAulas').value = dataSugerida || '';
        document.getElementById('novaHoraAulas').value = horaSugerida || '';
        new bootstrap.Modal(document.getElementById('modalConfirmarData')).show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const motivoAdmin = document.getElementById('motivoAdmin');
        const countAdmin  = document.getElementById('motivoAdminCount');
        if (motivoAdmin && countAdmin) {
            const updateCount = () => countAdmin.textContent = motivoAdmin.value.length + '/500';
            motivoAdmin.addEventListener('input', updateCount);
            updateCount();
        }
    });
</script>

@endsection
