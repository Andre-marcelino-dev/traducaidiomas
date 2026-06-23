@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">{{ $atividade->titulo_atividade }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('aluno.atividades.index') }}">Atividades</a></li>
                    <li class="breadcrumb-item active">Responder</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- Descrição --}}
        @if($atividade->descricao_atividade)
            <div class="d-card fade-up mb-4">
                <div class="card-body p-3 d-flex align-items-start gap-3">
                    <div class="tbl-icon-wrap" style="min-width:38px;"><i class="fas fa-circle-info"></i></div>
                    <div>
                        <div style="font-weight:600;font-size:.88rem;color:#1e293b;margin-bottom:.2rem;">Instruções da Atividade</div>
                        <div style="font-size:.82rem;color:#64748b;line-height:1.6;">{{ $atividade->descricao_atividade }}</div>
                    </div>
                </div>
            </div>
        @endif

        @if($resposta && in_array($resposta->status_resposta, ['ENVIADA', 'CORRIGIDA']))
            {{-- ══ JÁ RESPONDEU ══ --}}

            @if($resposta->status_resposta === 'CORRIGIDA')
                <div class="fade-up mb-4">
                    <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:16px;padding:1.5rem;border:1.5px solid #bbf7d0;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:52px;height:52px;border-radius:14px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-check-circle" style="font-size:1.4rem;color:#16a34a;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div style="font-weight:700;font-size:1.05rem;color:#065f46;">Atividade Corrigida</div>
                                <div style="font-size:.78rem;color:#16a34a;">Seu professor avaliou suas respostas</div>
                            </div>
                            <div style="text-align:center;">
                                <div style="font-size:2rem;font-weight:900;color:#059669;line-height:1;">{{ $resposta->nota }}</div>
                                <div style="font-size:.65rem;font-weight:600;color:#16a34a;text-transform:uppercase;letter-spacing:.08em;">/10</div>
                            </div>
                        </div>
                        @if($resposta->feedback_professor)
                            <div style="background:rgba(255,255,255,.6);border-radius:10px;padding:.75rem 1rem;border-left:3px solid #10b981;">
                                <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#059669;margin-bottom:.3rem;">Feedback do Professor</div>
                                <div style="font-size:.85rem;color:#065f46;line-height:1.5;">{{ $resposta->feedback_professor }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="fade-up mb-4">
                    <div class="dash-ok-banner" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-color:#fde68a;">
                        <div class="dash-ok-icon" style="background:#fde68a;color:#b45309;"><i class="fas fa-paper-plane"></i></div>
                        <div>
                            <div class="dash-ok-title" style="color:#92400e;">Atividade enviada!</div>
                            <div class="dash-ok-sub" style="color:#b45309;">Aguardando correção do professor.</div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Respostas --}}
            <div class="d-card fade-up mb-4">
                <div class="d-card-header">
                    <h6><i class="fas fa-clipboard-check text-success"></i> Suas Respostas</h6>
                </div>
                <div class="card-body p-3">
                    @foreach($atividade->questoes as $i => $questao)
                        @php $rq = $resposta->respostasQuestoes->firstWhere('id_questao', $questao->id_questao); @endphp
                        <div style="padding:1rem;margin-bottom:.75rem;border-radius:12px;border:1.5px solid #f1f5f9;background:#fafbfc;">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <span style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#818cf8);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;flex-shrink:0;">{{ $i+1 }}</span>
                                <div style="font-weight:600;font-size:.88rem;color:#1e293b;">{{ $questao->enunciado }}</div>
                            </div>
                            @if($questao->tipo_questao === 'multipla_escolha')
                                <div style="font-size:.82rem;color:#64748b;padding-left:36px;">
                                    A) {{ $questao->opcao_a }} &nbsp; B) {{ $questao->opcao_b }} &nbsp; C) {{ $questao->opcao_c }} &nbsp; D) {{ $questao->opcao_d }}
                                </div>
                                <div style="padding-left:36px;margin-top:.5rem;font-size:.85rem;">
                                    Sua resposta: <strong style="color:#1e293b;">{{ $rq?->resposta_aluno ?? '—' }}</strong>
                                    @if($rq?->correta !== null)
                                        @if($rq->correta)
                                            <span class="tbl-status tbl-status-ativo ms-2"><span class="tbl-status-dot"></span> Correta</span>
                                        @else
                                            <span class="tbl-status tbl-status-cancelado ms-2"><span class="tbl-status-dot"></span> Errada</span>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div style="padding-left:36px;font-size:.85rem;color:#475569;background:#fff;border-radius:8px;padding:.5rem .75rem;margin-top:.5rem;border:1px solid #e2e8f0;">
                                    {{ $rq?->resposta_aluno ?? '—' }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('aluno.atividades.index') }}" class="del-btn-cancelar mb-5">
                <i class="fas fa-arrow-left me-1"></i> Voltar para Atividades
            </a>

        @else
            {{-- ══ FORMULÁRIO ══ --}}
            <form action="{{ route('aluno.atividades.responder', $atividade->id_atividade) }}" method="POST">
                @csrf
                @foreach($atividade->questoes as $i => $questao)
                    <div class="d-card fade-up mb-3">
                        <div class="d-card-header">
                            <h6>
                                <span style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#818cf8);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;">{{ $i+1 }}</span>
                                Questão {{ $i+1 }}
                            </h6>
                            @if($questao->tipo_questao === 'multipla_escolha')
                                <span class="tbl-badge blue"><i class="fas fa-list-ol me-1"></i> Múltipla Escolha</span>
                            @else
                                <span class="tbl-badge"><i class="fas fa-pen me-1"></i> Dissertativa</span>
                            @endif
                        </div>
                        <div class="card-body p-3">
                            <p style="font-weight:600;font-size:.9rem;color:#1e293b;margin-bottom:1rem;">{{ $questao->enunciado }}</p>

                            @if($questao->tipo_questao === 'multipla_escolha')
                                <div class="d-flex flex-column gap-2">
                                    @foreach(['A' => $questao->opcao_a, 'B' => $questao->opcao_b, 'C' => $questao->opcao_c, 'D' => $questao->opcao_d] as $letra => $opcao)
                                        @if($opcao)
                                            <label class="atv-option">
                                                <input type="radio" name="questao_{{ $questao->id_questao }}" value="{{ $letra }}" required class="atv-radio">
                                                <span class="atv-option-letra">{{ $letra }}</span>
                                                <span class="atv-option-text">{{ $opcao }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <textarea name="questao_{{ $questao->id_questao }}" class="form-control" rows="4"
                                    placeholder="Digite sua resposta..." required
                                    style="border-radius:10px;border-color:#e2e8f0;"></textarea>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="d-flex gap-2 mb-5 fade-up">
                    <button type="submit" class="tbl-btn-success" style="padding:.6rem 1.5rem;font-size:.85rem;"
                        onclick="return confirm('Confirmar envio da atividade?')">
                        <i class="fas fa-paper-plane"></i> Enviar Atividade
                    </button>
                    <a href="{{ route('aluno.atividades.index') }}" class="del-btn-cancelar">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        @endif

    </div>
</div>

@push('scripts')
<style>
    .atv-option {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .75rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all .2s;
        background: #fff;
    }
    .atv-option:hover {
        border-color: #6366f1;
        background: #f5f3ff;
    }
    .atv-option:has(:checked) {
        border-color: #6366f1;
        background: linear-gradient(135deg, #eef3ff, #e0e7ff);
        box-shadow: 0 2px 8px rgba(99,102,241,.12);
    }
    .atv-radio {
        display: none;
    }
    .atv-option-letra {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: .82rem;
        color: #64748b;
        flex-shrink: 0;
        transition: all .2s;
    }
    .atv-option:has(:checked) .atv-option-letra {
        background: linear-gradient(135deg, #6366f1, #818cf8);
        color: #fff;
    }
    .atv-option-text {
        font-size: .88rem;
        color: #1e293b;
        font-weight: 500;
    }
</style>
@endpush

@endsection
