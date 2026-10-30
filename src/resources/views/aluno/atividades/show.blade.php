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

        @if(session('error') || $errors->any())
            <div class="alert alert-danger alert-styled alert-dismissible fade show mb-3">
                <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') ?? $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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

        {{-- Áudio da atividade --}}
        @if($atividade->arquivo_audio)
            <div class="d-card fade-up mb-4">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="tbl-icon-wrap" style="min-width:38px;"><i class="fas fa-headphones"></i></div>
                    <div class="flex-grow-1">
                        <div style="font-weight:600;font-size:.88rem;color:#1e293b;margin-bottom:.4rem;">Ouça o áudio da atividade</div>
                        <audio controls preload="none" class="w-100" src="{{ route('aluno.atividades.audio', $atividade->id_atividade) }}"></audio>
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
                            @elseif($questao->tipo_questao === 'audio')
                                <div style="margin-top:.5rem;">
                                    @if($rq?->resposta_aluno)
                                        <div style="font-size:.75rem;color:#64748b;margin-bottom:.3rem;"><i class="fas fa-microphone me-1"></i> Sua gravação</div>
                                        <audio controls preload="none" class="w-100" src="{{ route('aluno.atividades.questoes.audio', [$atividade->id_atividade, $questao->id_questao]) }}"></audio>
                                    @else
                                        <span style="font-size:.85rem;color:#94a3b8;">Nenhuma gravação enviada.</span>
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
            <form action="{{ route('aluno.atividades.responder', $atividade->id_atividade) }}" method="POST" enctype="multipart/form-data" id="formAtividade">
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
                            @elseif($questao->tipo_questao === 'audio')
                                <span class="tbl-badge"><i class="fas fa-microphone me-1"></i> Fala (gravação)</span>
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
                                                <input type="radio" name="questao_{{ $questao->id_questao }}" value="{{ $letra }}" required class="atv-radio" @checked(old('questao_' . $questao->id_questao) === $letra)>
                                                <span class="atv-option-letra">{{ $letra }}</span>
                                                <span class="atv-option-text">{{ $opcao }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($questao->tipo_questao === 'audio')
                                @php $jaGravou = (bool) $resposta?->respostasQuestoes->firstWhere('id_questao', $questao->id_questao)?->resposta_aluno; @endphp
                                <div class="atv-gravador" data-ja-gravou="{{ $jaGravou ? '1' : '0' }}" data-questao="{{ $i + 1 }}">
                                    @if($jaGravou)
                                        <div class="atv-gravador-aviso">
                                            <i class="fas fa-circle-check me-1"></i> Você já enviou uma gravação (pelo app). Pode ouvir abaixo ou gravar de novo para substituir.
                                            <audio controls preload="none" class="w-100 mt-2" src="{{ route('aluno.atividades.questoes.audio', [$atividade->id_atividade, $questao->id_questao]) }}"></audio>
                                        </div>
                                    @endif

                                    <div class="atv-gravador-controles">
                                        <button type="button" class="atv-btn-gravar" data-acao="gravar">
                                            <i class="fas fa-microphone"></i> <span>Gravar</span>
                                        </button>
                                        <span class="atv-gravador-tempo" hidden>00:00</span>
                                        <span class="atv-gravador-status"></span>
                                    </div>

                                    <audio class="atv-gravador-previa w-100 mt-2" controls hidden></audio>

                                    <input type="file" name="audio_{{ $questao->id_questao }}" accept="audio/*" class="atv-gravador-arquivo" hidden>
                                    <div class="atv-gravador-alternativa" hidden>
                                        Não foi possível usar o microfone neste navegador. Envie um arquivo de áudio:
                                        <input type="file" accept="audio/*" class="form-control form-control-sm mt-1 atv-gravador-arquivo-manual">
                                    </div>
                                </div>
                            @else
                                <textarea name="questao_{{ $questao->id_questao }}" class="form-control" rows="4"
                                    placeholder="Digite sua resposta..." required
                                    style="border-radius:10px;border-color:#e2e8f0;">{{ old('questao_' . $questao->id_questao) }}</textarea>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="d-flex gap-2 mb-5 fade-up">
                    <button type="submit" class="tbl-btn-success" style="padding:.6rem 1.5rem;font-size:.85rem;">
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

    /* ── Questão de fala (gravação) ── */
    .atv-gravador-controles {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .atv-btn-gravar {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.2rem;
        border: none;
        border-radius: 10px;
        font-size: .85rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #6366f1, #818cf8);
        cursor: pointer;
    }
    .atv-btn-gravar.gravando {
        background: linear-gradient(135deg, #e11d48, #f43f5e);
    }
    .atv-gravador-tempo {
        font-variant-numeric: tabular-nums;
        font-weight: 700;
        color: #e11d48;
    }
    .atv-gravador-status {
        font-size: .8rem;
        color: #64748b;
    }
    .atv-gravador-aviso,
    .atv-gravador-alternativa {
        font-size: .8rem;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .6rem .8rem;
        margin-bottom: .75rem;
    }
    .atv-gravador-alternativa {
        margin: .75rem 0 0;
    }
</style>

<script>
(function () {
    const form = document.getElementById('formAtividade');
    if (!form) return;

    // Gravar no navegador precisa de microfone + MediaRecorder; DataTransfer põe
    // a gravação no <input type="file"> pra ela ir junto com o formulário.
    const podeGravar = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia
        && window.MediaRecorder && window.DataTransfer);
    const LIMITE_SEGUNDOS = 300; // 5 minutos: bem abaixo dos 20 MB aceitos pelo servidor
    const extensaoDe = tipo => tipo.includes('ogg') ? 'ogg' : (tipo.includes('mp4') || tipo.includes('aac') ? 'm4a' : 'webm');
    const formatar = s => String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
    let gravadorAtivo = null;

    document.querySelectorAll('.atv-gravador').forEach(function (box) {
        const btn = box.querySelector('[data-acao="gravar"]');
        const icone = btn.querySelector('i');
        const rotulo = btn.querySelector('span');
        const tempo = box.querySelector('.atv-gravador-tempo');
        const status = box.querySelector('.atv-gravador-status');
        const previa = box.querySelector('.atv-gravador-previa');
        const arquivo = box.querySelector('.atv-gravador-arquivo');

        if (!podeGravar) {
            // Sem gravação no navegador: o aluno escolhe um arquivo de áudio.
            const manual = box.querySelector('.atv-gravador-arquivo-manual');
            manual.name = arquivo.name;
            arquivo.remove();
            box.querySelector('.atv-gravador-controles').hidden = true;
            box.querySelector('.atv-gravador-alternativa').hidden = false;
            manual.addEventListener('change', function () {
                if (manual.files.length) {
                    previa.src = URL.createObjectURL(manual.files[0]);
                    previa.hidden = false;
                }
            });
            return;
        }

        let recorder = null;
        let stream = null;
        let pedacos = [];
        let timer = null;
        let segundos = 0;

        btn.addEventListener('click', async function () {
            if (recorder && recorder.state === 'recording') {
                recorder.stop();
                return;
            }
            if (gravadorAtivo) {
                status.textContent = 'Pare a outra gravação primeiro.';
                return;
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            } catch (e) {
                status.textContent = 'Permita o uso do microfone no navegador para gravar.';
                return;
            }

            pedacos = [];
            recorder = new MediaRecorder(stream);
            recorder.ondataavailable = function (e) {
                if (e.data.size) pedacos.push(e.data);
            };
            recorder.onstop = function () {
                clearInterval(timer);
                stream.getTracks().forEach(t => t.stop());
                gravadorAtivo = null;

                const tipo = (recorder.mimeType || 'audio/webm').split(';')[0];
                const blob = new Blob(pedacos, { type: tipo });
                const dt = new DataTransfer();
                dt.items.add(new File([blob], 'resposta.' + extensaoDe(tipo), { type: tipo }));
                arquivo.files = dt.files;

                previa.src = URL.createObjectURL(blob);
                previa.hidden = false;
                btn.classList.remove('gravando');
                icone.className = 'fas fa-rotate-right';
                rotulo.textContent = 'Gravar de novo';
                status.textContent = 'Gravação pronta. Ouça antes de enviar.';
            };

            recorder.start();
            gravadorAtivo = box;
            segundos = 0;
            tempo.textContent = formatar(0);
            tempo.hidden = false;
            timer = setInterval(function () {
                segundos++;
                tempo.textContent = formatar(segundos);
                if (segundos >= LIMITE_SEGUNDOS) recorder.stop();
            }, 1000);

            previa.hidden = true;
            btn.classList.add('gravando');
            icone.className = 'fas fa-stop';
            rotulo.textContent = 'Parar';
            status.textContent = 'Gravando... (máximo 5 minutos)';
        });
    });

    form.addEventListener('submit', function (e) {
        if (gravadorAtivo) {
            e.preventDefault();
            alert('Pare a gravação antes de enviar a atividade.');
            return;
        }

        for (const box of form.querySelectorAll('.atv-gravador')) {
            const input = box.querySelector('input[type="file"][name]');
            if (box.dataset.jaGravou !== '1' && !(input && input.files.length)) {
                e.preventDefault();
                alert('Grave sua resposta da questão ' + box.dataset.questao + ' antes de enviar.');
                box.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }

        if (!confirm('Confirmar envio da atividade?')) {
            e.preventDefault();
        }
    });
})();
</script>
@endpush

@endsection
