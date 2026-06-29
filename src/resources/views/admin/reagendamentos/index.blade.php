@extends('admin.layout.admin')

@section('content')

<style>
    .reagendamento-datetime-wrap {
        max-width: 360px;
    }

    .reagendamento-datetime-wrap .form-control {
        border-radius: 10px;
        font-size: 0.9rem;
        padding: 0.55rem 0.75rem;
    }

    .reagendamento-datetime-wrap .form-label {
        margin-bottom: 0.35rem;
    }
</style>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Reagendamentos</h3>
            </div>
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
                    <div class="mc-icon"><i class="fas fa-clock"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'pendente')->count() }}</div>
                    <p class="mc-lbl">Pendentes</p>
                    <div class="mc-trend"><i class="fas fa-hourglass-half me-1"></i>aguardando resposta</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow">
                    <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'confirmado')->count() }}</div>
                    <p class="mc-lbl">Confirmados</p>
                    <div class="mc-trend"><i class="fas fa-check me-1"></i>aprovados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow">
                    <div class="mc-icon"><i class="fas fa-circle-xmark"></i></div>
                    <div class="mc-val">{{ $reagendamentos->where('status', 'recusado')->count() }}</div>
                    <p class="mc-lbl">Recusados</p>
                    <div class="mc-trend"><i class="fas fa-ban me-1"></i>negados</div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow">
                    <div class="mc-icon"><i class="fas fa-calendar-days"></i></div>
                    <div class="mc-val">{{ $reagendamentos->count() }}</div>
                    <p class="mc-lbl">Total</p>
                    <div class="mc-trend"><i class="fas fa-database me-1"></i>registrados</div>
                </div>
            </div>
        </div>

        @if (session('success'))
        <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;border:none;">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;border:none;">
            <i class="fas fa-exclamation-triangle me-2"></i>Preencha todos os campos obrigatórios.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- TABELA --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-calendar-alt text-warning"></i> Solicitações de Reagendamento</h6>
                <button type="button" class="tbl-btn-novo" data-bs-toggle="modal" data-bs-target="#modalNovoReagendamentoAdmin">
                   <!--
category: Math
tags: [add, create, new, "+"]
version: "1.0"
unicode: "eb0b"
-->
<svg
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="#ffffff"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M12 5l0 14" />
  <path d="M5 12l14 0" />
</svg>
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
                                <div style="font-size:.72rem;">
                                    <i class="fas fa-calendar-day me-1"></i>
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
                                <div class="d-flex justify-content-center gap-2">
                                    @if ($reagendamento->status === 'pendente')
                                    <button type="button" class="tbl-btn-success"
                                        onclick="abrirModalConfirmar({{ $reagendamento->id }}, '{{ $reagendamento->aula?->titulo_aulas }}', '{{ $reagendamento->aluno?->nome_aluno }}')">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="#FFFFFF"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z" fill="#FFFFFF"/>
</svg>
                                    </button>
                                    <form action="{{ route('admin.reagendamentos.recusar', $reagendamento->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="tbl-btn-excluir" style="box-shadow:none;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C14.2845 2.75 16.3756 3.57817 17.9894 4.95066C17.9827 4.95685 17.9762 4.96319 17.9697 4.96967L4.96969 17.9694C4.96317 17.976 4.95679 17.9826 4.95056 17.9893C3.57813 16.3755 2.75 14.2845 2.75 12ZM6.0105 19.0492C7.62432 20.4218 9.71544 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 9.7155 20.4218 7.62442 19.0493 6.01062C19.0431 6.01728 19.0368 6.02386 19.0303 6.03034L6.03034 19.0301C6.02382 19.0366 6.0172 19.043 6.0105 19.0492Z" fill="#FFFFFF"/>
</svg>
                                        </button>
                                    </form>
                                    @else
                                    <span style="font-size:.75rem;color:#94a3b8;display:flex;align-items:center;justify-content:center;">Concluído</span>
                                    @endif
                                    <form action="{{ route('admin.reagendamentos.destroy', $reagendamento->id) }}" method="POST" class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="tbl-btn-excluir" style="color:#dc2626;box-shadow:none;display:flex;align-items:center;justify-content:center;" data-id="{{ $reagendamento->id }}"
                                            data-nome="{{ $reagendamento->aluno?->nome_aluno }}"
                                            onclick="abrirModalExcluir(this)">
                                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.9999 2.75C11.0214 2.75 10.187 3.37503 9.87778 4.24993C9.73974 4.64047 9.31125 4.84517 8.92071 4.70713C8.53017 4.56909 8.32548 4.1406 8.46352 3.75007C8.97795 2.29459 10.366 1.25 11.9999 1.25C13.6339 1.25 15.0219 2.29459 15.5364 3.75007C15.6744 4.1406 15.4697 4.56909 15.0792 4.70713C14.6886 4.84517 14.2601 4.64047 14.1221 4.24993C13.8129 3.37503 12.9784 2.75 11.9999 2.75Z" fill="#ffffff"/>
<path d="M2.74991 6C2.74991 5.58579 3.08569 5.25 3.49991 5.25H20.5C20.9142 5.25 21.25 5.58579 21.25 6C21.25 6.41421 20.9142 6.75 20.5 6.75H3.49991C3.08569 6.75 2.74991 6.41421 2.74991 6Z" fill="#ffffff"/>
<path d="M5.91499 8.45011C5.88744 8.03681 5.53006 7.72411 5.11677 7.75166C4.70347 7.77921 4.39076 8.13659 4.41832 8.54989L4.88176 15.5016C4.96726 16.7844 5.03632 17.8205 5.19829 18.6336C5.36669 19.4789 5.65311 20.185 6.24471 20.7384C6.8363 21.2919 7.55985 21.5307 8.4145 21.6425C9.23654 21.75 10.275 21.75 11.5606 21.75H12.4394C13.725 21.75 14.7634 21.75 15.5855 21.6425C16.4401 21.5307 17.1637 21.2919 17.7553 20.7384C18.3469 20.185 18.6333 19.4789 18.8017 18.6336C18.9637 17.8205 19.0327 16.7844 19.1182 15.5016L19.5817 8.54989C19.6092 8.13659 19.2965 7.77921 18.8832 7.75166C18.4699 7.72411 18.1125 8.03681 18.085 8.45011L17.625 15.3492C17.5352 16.6971 17.4712 17.6349 17.3306 18.3405C17.1942 19.025 17.0039 19.3873 16.7305 19.6431C16.4571 19.8988 16.0829 20.0647 15.3909 20.1552C14.6775 20.2485 13.7375 20.25 12.3867 20.25H11.6133C10.2625 20.25 9.32246 20.2485 8.60906 20.1552C7.91705 20.0647 7.5429 19.8988 7.26948 19.6431C6.99607 19.3873 6.80574 19.025 6.66939 18.3405C6.52882 17.6349 6.46479 16.6971 6.37493 15.3492L5.91499 8.45011Z" fill="#ffffff"/>
<path d="M9.42537 10.2537C9.83753 10.2125 10.2051 10.5132 10.2463 10.9254L10.7463 15.9254C10.7875 16.3375 10.4868 16.7051 10.0746 16.7463C9.66247 16.7875 9.29493 16.4868 9.25372 16.0746L8.75372 11.0746C8.7125 10.6625 9.01321 10.2949 9.42537 10.2537Z" fill="#ffffff"/>
<path d="M15.2463 11.0746C15.2875 10.6625 14.9868 10.2949 14.5746 10.2537C14.1625 10.2125 13.7949 10.5132 13.7537 10.9254L13.2537 15.9254C13.2125 16.3375 13.5132 16.7051 13.9254 16.7463C14.3375 16.7875 14.7051 16.4868 14.7463 16.0746L15.2463 11.0746Z" fill="#ffffff"/>
</svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="tbl-empty">
                                    <i class="fas fa-calendar-check tbl-empty-icon"></i>
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
                    <div class="p-3 rounded mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                        <small class="text-muted d-block" style="font-size:.8rem;">Aluno</small>
                        <span class="fw-semibold" id="modalConfirmarAluno" style="color:#065f46;"></span>
                        <small class="text-muted d-block mt-1" style="font-size:.8rem;">Aula</small>
                        <span class="fw-semibold" id="modalConfirmarAula" style="color:#065f46;"></span>
                    </div>
                    <div class="row g-2 reagendamento-datetime-wrap">
                        <div class="col-sm-7">
                            <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                                <i class="fas fa-calendar me-1 text-success"></i> Nova Data
                            </label>
                            <input type="date" name="nova_data_aulas" id="novaDataAulas" class="form-control" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label fw-semibold text-secondary" style="font-size:.85rem;">
                                <i class="fas fa-clock me-1 text-success"></i> Nova Hora
                            </label>
                            <input type="time" name="nova_hora_aulas" id="novaHoraAulas" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="del-btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="tbl-btn-success">
                        <i class="fas fa-paper-plane"></i> Confirmar e Notificar Aluno
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
                            <i class="fas fa-book-open me-1 text-warning"></i> Selecione a Aula
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
                            <i class="fas fa-comment-alt me-1 text-warning"></i> Observações / Motivo
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
                        <i class="fas fa-save"></i> Salvar Alteração
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Reagendamento', 'delDescricao' => 'Você está prestes a excluir o reagendamento de:'])

<script>
    function abrirModalConfirmar(id, aula, aluno) {
        document.getElementById('modalConfirmarAluno').textContent = aluno;
        document.getElementById('modalConfirmarAula').textContent = aula;
        document.getElementById('formConfirmarData').action = '/admin/reagendamentos/' + id + '/aceitar';
        new bootstrap.Modal(document.getElementById('modalConfirmarData')).show();
    }

    document.addEventListener('DOMContentLoaded', function() {
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
