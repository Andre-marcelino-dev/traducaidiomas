@extends('admin.layout.admin')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Topo com Título e Botão de Novo Reagendamento --}}
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
        
        {{-- Botão que abre o Formulário (Modal) --}}
        <button type="button" class="btn text-white fw-semibold px-4 py-2 d-flex align-items-center gap-2" 
                data-bs-toggle="modal" data-bs-target="#modalNovoReagendamentoAdmin"
                style="background:#d97706; border-radius:10px; border:none; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2);">
            <i class="fas fa-plus"></i> Novo Reagendamento
        </button>
    </div>

    {{-- Cards Informativos de Status --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius:12px; background: #fffbeb;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted d-block small fw-semibold">PENDENTES</span>
                        <h3 class="fw-bold mb-0" style="color:#b45309;">{{ $reagendamentos->where('status', 'pendente')->count() }}</h3>
                    </div>
                    <i class="fas fa-clock fs-3" style="color:#d97706;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Alertas de Feedback --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px;">
            <i class="fas fa-exclamation-triangle me-2"></i> Preencha todos os campos obrigatórios do formulário.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabela de Reagendamentos --}}
    <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: linear-gradient(135deg, #f9fafb, #f3f4f6); font-size: 0.85rem;" class="text-secondary fw-semibold">
                    <tr>
                        <th class="px-4 py-3">Aluno</th>
                        <th class="py-3">Aula Original</th>
                        <th class="py-3">Nova Data Sugerida</th>
                        <th class="py-3" style="width: 30%;">Motivo Informado</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-end">Ações</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.9rem;">
                    @forelse($reagendamentos as $reagendamento)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                        <i class="fas fa-user text-secondary" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $reagendamento->aluno?->nome_aluno ?? 'Aluno não encontrado' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{-- AQUI FOI CORRIGIDO: Trecho inserido com o safe navigation operator (?->) para varrer as colunas --}}
                                <span class="fw-medium text-dark">
                                    {{ $reagendamento->aula?->nome_aula ?? $reagendamento->aula?->titulo ?? $reagendamento->aula?->titulo_aula ?? 'Aula não encontrada' }}
                                </span>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">
                                    <i class="fas fa-calendar-day me-1"></i>
                                    {{ $reagendamento->aula && $reagendamento->aula->data_hora ? \Carbon\Carbon::parse($reagendamento->aula->data_hora)->format('d/m/Y H:i') : 'Sem data' }}
                                </small>
                            </td>
                            <td>
                                @if($reagendamento->data_sugerida)
                                    <span class="badge bg-warning-subtle text-warning-emphasis p-2" style="border-radius: 6px; font-size: 0.8rem;">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($reagendamento->data_sugerida)->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted italic small">Não sugerida</span>
                                @endif
                            </td>
                            <td>
                                <p class="mb-0 text-secondary text-wrap" style="max-width: 350px; font-size: 0.85rem; line-height: 1.4;">
                                    {{ $reagendamento->motivo }}
                                </p>
                            </td>
                            <td class="text-center">
                                @if($reagendamento->status === 'pendente')
                                    <span class="badge bg-warning text-dark px-2.5 py-1.5" style="border-radius:30px; font-size:0.75rem;">Pendente</span>
                                @elseif($reagendamento->status === 'aceito')
                                    <span class="badge bg-success px-2.5 py-1.5" style="border-radius:30px; font-size:0.75rem;">Aceito</span>
                                @else
                                    <span class="badge bg-danger px-2.5 py-1.5" style="border-radius:30px; font-size:0.75rem;">Recusado</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                @if($reagendamento->status === 'pendente')
                                    <div class="d-flex justify-content-end gap-2">
                                        
                                        {{-- Botão Aceitar --}}
                                        <form action="{{ route('admin.reagendamentos.aceitar', $reagendamento->id) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm text-white" style="background: #059669; border-radius: 8px;" title="Aceitar Reagendamento">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        {{-- Botão Recusar --}}
                                        <form action="{{ route('admin.reagendamentos.recusar', $reagendamento->id) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px;" title="Recusar Reagendamento">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">Concluído</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
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

{{-- ── FORMULÁRIO DE REAGENDAMENTO (MODAL ADMIN) ── --}}
<div class="modal fade" id="modalNovoReagendamentoAdmin" tabindex="-1" aria-labelledby="modalNovoReagendamentoAdminLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15);">

            {{-- Header --}}
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-radius:16px 16px 0 0; padding:1.5rem 1.5rem 1rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-calendar-plus text-white fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold" id="modalNovoReagendamentoAdminLabel" style="color:#92400e;">
                            Lançar Reagendamento
                        </h5>
                        <small style="color:#b45309;">Cadastrar alteração manual de aula</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            {{-- Body --}}
            <form action="{{ route('aluno.reagendamento.solicitar') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">

                    {{-- Seleção de Aluno --}}
                    <div class="mb-3">
                        <label for="aluno_id" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-user me-1 text-warning"></i> Selecione o Aluno
                        </label>
                        <select name="aluno_id" id="aluno_id" class="form-select @error('aluno_id') is-invalid @enderror" style="border-radius:10px;border-color:#e5e7eb;" required>
                            <option value="">Escolha o aluno...</option>
                            @isset($alunos)
                                @foreach($alunos as $aluno)
                                    <option value="{{ $aluno->id_aluno }}" {{ old('aluno_id') == $aluno->id_aluno ? 'selected' : '' }}>
                                        {{ $aluno->nome_aluno }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        @error('aluno_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Seleção de Aula --}}
                    <div class="mb-3">
                        <label for="aula_id" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-book-open me-1 text-warning"></i> Selecione a Aula
                        </label>
                        <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" style="border-radius:10px;border-color:#e5e7eb;" required>
                            <option value="">Escolha a aula comprometida...</option>
                            @isset($aulas)
                                @foreach($aulas as $aula)
                                    <option value="{{ $aula->id }}" {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                        {{ $aula->titulo ?? $aula->nome_aula }} — {{ \Carbon\Carbon::parse($aula->data_hora)->format('d/m/Y H:i') }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        @error('aula_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nova Data/Hora Sugerida --}}
                    <div class="mb-3">
                        <label for="data_sugerida" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                            <i class="fas fa-clock me-1 text-warning"></i> Nova Data / Hora Marcada
                        </label>
                        <input type="datetime-local" name="data_sugerida" id="data_sugerida"
                            class="form-control @error('data_sugerida') is-invalid @enderror"
                            style="border-radius:10px;border-color:#e5e7eb;"
                            value="{{ old('data_sugerida') }}" required>
                        @error('data_sugerida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Motivo / Observação --}}
                    <div class="mb-2">
                        <label for="motivo" class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
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
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius:10px;border:1px solid #e5e7eb;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn px-4 text-white fw-semibold" style="background:#d97706;border-radius:10px;border:none;">
                        <i class="fas fa-save me-2"></i>Salvar Alteração
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Script: Contador de caracteres do campo Motivo --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const motivoAdmin = document.getElementById('motivoAdmin');
        const countAdmin = document.getElementById('motivoAdminCount');
        if (motivoAdmin && countAdmin) {
            const updateCount = () => countAdmin.textContent = motivoAdmin.value.length + '/500';
            motivoAdmin.addEventListener('input', updateCount);
            updateCount();
        }
    });
</script>
@endsection