@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px; height:48px; background:#d97706; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-calendar-alt text-white fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color:#92400e;">Solicitações de Reagendamento</h5>
                    <small class="text-muted">Gerencie os pedidos ou crie um novo reagendamento direto pelo painel</small>
                </div>
            </div>
            <button type="button" class="btn text-white fw-semibold px-4 py-2 d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#modalNovoReagendamentoAdmin"
                style="background:#d97706; border-radius:10px; border:none; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2);">
                <i class="fas fa-plus"></i> Novo Reagendamento
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm p-3" style="border-radius:12px; background: #fffbeb;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small fw-semibold">PENDENTES</span>
                            <h3 class="fw-bold mb-0" style="color:#b45309;">
                                {{ $reagendamentos->where('status', 'pendente')->count() }}</h3>
                        </div>
                        <i class="fas fa-clock fs-3" style="color:#d97706;"></i>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
                <i class="fas fa-exclamation-triangle me-2"></i> Preencha todos os campos obrigatórios do formulário.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: linear-gradient(135deg, #f9fafb, #f3f4f6); font-size: 0.85rem;" class="text-secondary fw-semibold">
                        <tr>
                            <th class="px-4 py-3">Aluno</th>
                            <th class="py-3">Aula Original</th>
                            <th class="py-3">Motivo Informado</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem;">
                        @forelse($reagendamentos as $reagendamento)
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:32px; height:32px;">
                                            <i class="fas fa-user text-secondary" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $reagendamento->aluno?->nome_aluno ?? 'Aluno não encontrado' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium text-dark">
                                        {{ $reagendamento->aula?->titulo_aulas ?? 'Aula não encontrada' }}
                                    </span>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        @if($reagendamento->aula && $reagendamento->aula->data_aulas)
                                            {{ \Carbon\Carbon::parse($reagendamento->aula->data_aulas)->format('d/m/Y') }}
                                            {{ $reagendamento->aula->hora_aulas ? \Carbon\Carbon::parse($reagendamento->aula->hora_aulas)->format('H:i') : '' }}
                                        @else
                                            Sem data
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <p class="mb-0 text-secondary text-wrap" style="max-width: 300px; font-size: 0.85rem; line-height: 1.4;">
                                        {{ $reagendamento->motivo }}
                                    </p>
                                </td>
                                <td class="text-center">
                                    @if ($reagendamento->status === 'pendente')
                                        <span class="badge bg-warning text-dark" style="border-radius:30px; font-size:0.75rem;">Pendente</span>
                                    @elseif($reagendamento->status === 'confirmado')
                                        <span class="badge bg-success" style="border-radius:30px; font-size:0.75rem;">Confirmado</span>
                                        @if($reagendamento->data_nova)
                                            <small class="text-muted d-block" style="font-size:0.72rem;">
                                                {{ \Carbon\Carbon::parse($reagendamento->data_nova)->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="badge bg-danger" style="border-radius:30px; font-size:0.75rem;">Recusado</span>
                                    @endif
                                </td>
                                <td class="px-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">

                                        @if ($reagendamento->status === 'pendente')
                                            {{-- Botão Confirmar com Data --}}
                                            <button type="button" class="btn btn-sm text-white"
                                                style="background: #059669; border-radius: 8px;"
                                                title="Confirmar com nova data"
                                                onclick="abrirModalConfirmar({{ $reagendamento->id }}, '{{ $reagendamento->aula?->titulo_aulas }}', '{{ $reagendamento->aluno?->nome_aluno }}')">
                                                <i class="fas fa-calendar-check"></i>
                                            </button>

                                            {{-- Botão Recusar --}}
                                            <form action="{{ route('admin.reagendamentos.recusar', $reagendamento->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    style="border-radius: 8px;" title="Recusar Reagendamento">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small me-2">Concluído</span>
                                        @endif

                                        {{-- Botão Deletar --}}
                                        <form action="{{ route('admin.reagendamentos.destroy', $reagendamento->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Tem certeza que deseja deletar este reagendamento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm"
                                                style="background:#fee2e2;color:#dc2626;border-radius:8px;"
                                                title="Deletar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fs-2 mb-3 d-block text-black-50"></i>
                                    Nenhuma solicitação de reagendamento encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL CONFIRMAR COM DATA --}}
    <div class="modal fade" id="modalConfirmarData" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15);">

                <div class="modal-header border-0 pb-0"
                    style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); border-radius:16px 16px 0 0; padding:1.5rem 1.5rem 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#059669;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-calendar-check text-white fs-5"></i>
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

                        <div class="p-3 rounded mb-3" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                            <small class="text-muted d-block" style="font-size:0.8rem;">Aluno</small>
                            <span class="fw-semibold" id="modalConfirmarAluno" style="color:#065f46;"></span>
                            <small class="text-muted d-block mt-1" style="font-size:0.8rem;">Aula</small>
                            <span class="fw-semibold" id="modalConfirmarAula" style="color:#065f46;"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                                <i class="fas fa-calendar me-1 text-success"></i> Nova Data
                            </label>
                            <input type="date" name="nova_data_aulas" id="novaDataAulas"
                                class="form-control" style="border-radius:10px;border-color:#e5e7eb;"
                                min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                                <i class="fas fa-clock me-1 text-success"></i> Nova Hora
                            </label>
                            <input type="time" name="nova_hora_aulas" id="novaHoraAulas"
                                class="form-control" style="border-radius:10px;border-color:#e5e7eb;" required>
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius:10px;border:1px solid #e5e7eb;">
                            Cancelar
                        </button>
                        <button type="submit" class="btn px-4 text-white fw-semibold"
                            style="background:#059669;border-radius:10px;border:none;">
                            <i class="fas fa-paper-plane me-2"></i>Confirmar e Notificar Aluno
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- MODAL NOVO REAGENDAMENTO (ADMIN) --}}
    <div class="modal fade" id="modalNovoReagendamentoAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15);">

                <div class="modal-header border-0 pb-0"
                    style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-radius:16px 16px 0 0; padding:1.5rem 1.5rem 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-calendar-plus text-white fs-5"></i>
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
                                <i class="fas fa-user me-1 text-warning"></i> Selecione o Aluno
                            </label>
                            <select name="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror"
                                style="border-radius:10px;border-color:#e5e7eb;" required>
                                <option value="">Escolha o aluno...</option>
                                @isset($alunos)
                                    @foreach ($alunos as $aluno)
                                        <option value="{{ $aluno->id_aluno }}" {{ old('aluno_id') == $aluno->id_aluno ? 'selected' : '' }}>
                                            {{ $aluno->nome_aluno }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('aluno_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                                <i class="fas fa-book-open me-1 text-warning"></i> Selecione a Aula
                            </label>
                            <select name="aula_id" class="form-select @error('aula_id') is-invalid @enderror"
                                style="border-radius:10px;border-color:#e5e7eb;" required>
                                <option value="">Escolha a aula comprometida...</option>
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
                                <i class="fas fa-comment-alt me-1 text-warning"></i> Observações / Motivo
                            </label>
                            <textarea name="motivo" id="motivoAdmin" rows="3"
                                class="form-control @error('motivo') is-invalid @enderror"
                                style="border-radius:10px;border-color:#e5e7eb;resize:none;"
                                placeholder="Escreva a justificativa da alteração..."
                                maxlength="500" required>{{ old('motivo') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" id="motivoAdminCount">0/500</small>
                            </div>
                            @error('motivo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius:10px;border:1px solid #e5e7eb;">
                            Cancelar
                        </button>
                        <button type="submit" class="btn px-4 text-white fw-semibold"
                            style="background:#d97706;border-radius:10px;border:none;">
                            <i class="fas fa-save me-2"></i>Salvar Alteração
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function abrirModalConfirmar(id, aula, aluno) {
            document.getElementById('modalConfirmarAluno').textContent = aluno;
            document.getElementById('modalConfirmarAula').textContent  = aula;
            document.getElementById('formConfirmarData').action = '/admin/reagendamentos/' + id + '/aceitar';
            const modal = new bootstrap.Modal(document.getElementById('modalConfirmarData'));
            modal.show();
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