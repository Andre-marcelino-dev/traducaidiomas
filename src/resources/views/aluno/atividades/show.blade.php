 @extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">{{ $atividade->titulo_atividade }}</h3>
            </div>
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

        @if($atividade->descricao_atividade)
            <div class="d-card fade-up mb-4">
                <div class="card-body p-3 d-flex align-items-start gap-3">
                    <div class="tbl-icon-wrap" style="min-width:38px;">
                        <i class="fas fa-circle-info"></i>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:.88rem;color:#1e293b;margin-bottom:.2rem;">
                            Instruções da Atividade
                        </div>
                        <div style="font-size:.82rem;color:#64748b;line-height:1.6;">
                            {{ $atividade->descricao_atividade }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($resposta && in_array($resposta->status_resposta, ['ENVIADA', 'CORRIGIDA']))

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
                                <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#059669;margin-bottom:.3rem;">
                                    Feedback do Professor
                                </div>
                                <div style="font-size:.85rem;color:#065f46;line-height:1.5;">
                                    {{ $resposta->feedback_professor }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="fade-up mb-4">
                    <div class="dash-ok-banner" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-color:#fde68a;">
                        <div class="dash-ok-icon" style="background:#fde68a;color:#b45309;">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <div class="dash-ok-title" style="color:#92400e;">Atividade enviada!</div>
                            <div class="dash-ok-sub" style="color:#b45309;">Aguardando correção do professor.</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-card fade-up mb-4">
                <div class="d-card-header">
                    <h6><i class="fas fa-clipboard-check text-success"></i> Suas Respostas</h6>
                </div>
                <div class="card-body p-3">
                    @foreach($atividade->questoes as $i => $questao)
                        @php
                            $rq = $resposta->respostasQuestoes->firstWhere('id_questao', $questao->id_questao);
                        @endphp

                        <div style="padding:1rem;margin-bottom:.75rem;border-radius:12px;border:1.5px solid #f1f5f9;background:#fafbfc;">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <span style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#818cf8);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;flex-shrink:0;">
                                    {{ $i+1 }}
                                </span>
                                <div style="font-weight:600;font-size:.88rem;color:#1e293b;">
                                    {{ $questao->enunciado }}
                                </div>
                            </div>

                            @if($questao->tipo_questao === 'multipla_escolha')
                                <div style="font-size:.82rem;color:#64748b;padding-left:36px;">
                                    A) {{ $questao->opcao_a }}
                                    &nbsp; B) {{ $questao->opcao_b }}
                                    &nbsp; C) {{ $questao->opcao_c }}
                                    &nbsp; D) {{ $questao->opcao_d }}
                                </div>
                                <div style="padding-left:36px;margin-top:.5rem;font-size:.85rem;">
                                    Sua resposta:
                                    <strong style="color:#1e293b;">{{ $rq?->resposta_aluno ?? '—' }}</strong>

                                    @if($rq?->correta !== null)
                                        @if($rq->correta)
                                            <span class="tbl-status tbl-status-ativo ms-2">
                                                <span class="tbl-status-dot"></span> Correta
                                            </span>
                                        @else
                                            <span class="tbl-status tbl-status-cancelado ms-2">
                                                <span class="tbl-status-dot"></span> Errada
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            @elseif($questao->tipo_questao === 'audio' && $rq?->audio_resposta)
                                <div style="padding-left:36px;margin-top:.5rem;">
                                    <audio controls style="width:100%;" preload="none">
                                        <source src="{{ asset('storage/' . $rq->audio_resposta) }}">
                                        Seu navegador não suporta áudio.
                                    </audio>
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

            <form id="formResposta"
                  action="{{ route('aluno.atividades.responder', $atividade->id_atividade) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                @foreach($atividade->questoes as $i => $questao)
                    <div class="d-card fade-up mb-3">
                        <div class="d-card-header">
                            <h6>
                                <span style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#6366f1,#818cf8);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:700;">
                                    {{ $i+1 }}
                                </span>
                                Questão {{ $i+1 }}
                            </h6>

                            @if($questao->tipo_questao === 'multipla_escolha')
                                <span class="tbl-badge blue">
                                    <i class="fas fa-list-ol me-1"></i> Múltipla Escolha
                                </span>
                            @elseif($questao->tipo_questao === 'audio')
                                <span class="tbl-badge">
                                    <i class="fas fa-microphone me-1"></i> Resposta em Áudio
                                </span>
                            @else
                                <span class="tbl-badge">
                                    <i class="fas fa-pen me-1"></i> Dissertativa
                                </span>
                            @endif
                        </div>

                        <div class="card-body p-3">
                            <p style="font-weight:600;font-size:.9rem;color:#1e293b;margin-bottom:1rem;">
                                {{ $questao->enunciado }}
                            </p>

                            @if($questao->tipo_questao === 'multipla_escolha')
                                <div class="d-flex flex-column gap-2">
                                    @foreach(['A' => $questao->opcao_a, 'B' => $questao->opcao_b, 'C' => $questao->opcao_c, 'D' => $questao->opcao_d] as $letra => $opcao)
                                        @if($opcao)
                                            <label class="atv-option">
                                                <input type="radio"
                                                       name="questao_{{ $questao->id_questao }}"
                                                       value="{{ $letra }}"
                                                       required
                                                       class="atv-radio">
                                                <span class="atv-option-letra">{{ $letra }}</span>
                                                <span class="atv-option-text">{{ $opcao }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                            @elseif($questao->tipo_questao === 'audio')
                                <div class="audio-gravacao-container">
                                    <p class="audio-instrucao">
                                        <i class="fas fa-info-circle"></i>
                                        Grave sua resposta usando o microfone.
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <button type="button"
                                                class="btn btn-primary btn-gravar-audio"
                                                data-questao="{{ $questao->id_questao }}">
                                            <i class="fas fa-microphone"></i> Gravar
                                        </button>

                                        <button type="button"
                                                class="btn btn-danger btn-parar-audio d-none"
                                                data-questao="{{ $questao->id_questao }}">
                                            <i class="fas fa-stop"></i> Parar
                                        </button>
                                    </div>

                                    <p id="audio-status-{{ $questao->id_questao }}" class="audio-status">
                                        Aguardando gravação...
                                    </p>

                                    <audio controls
                                           class="audio-preview d-none"
                                           id="audio-preview-{{ $questao->id_questao }}"
                                           style="width:100%;max-width:450px;">
                                    </audio>

                                    <input type="file"
                                           name="audio_resposta_{{ $questao->id_questao }}"
                                           id="audio-input-{{ $questao->id_questao }}"
                                           class="audio-input"
                                           accept="audio/*"
                                           required>
                                </div>

                            @else
                                <textarea name="questao_{{ $questao->id_questao }}"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Digite sua resposta..."
                                          required
                                          style="border-radius:10px;border-color:#e2e8f0;"></textarea>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="d-flex gap-2 mb-5 fade-up">
                    <button type="submit"
                            class="tbl-btn-success"
                            style="padding:.6rem 1.5rem;font-size:.85rem;"
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

    .atv-option:has(input:checked) {
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

    .atv-option:has(input:checked) .atv-option-letra {
        background: linear-gradient(135deg, #6366f1, #818cf8);
        color: #fff;
    }

    .atv-option-text {
        font-size: .88rem;
        color: #1e293b;
        font-weight: 500;
    }

    .audio-gravacao-container {
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
    }

    .audio-instrucao {
        color: #475569;
        font-size: .88rem;
        margin-bottom: 1rem;
    }

    .audio-status {
        font-size: .82rem;
        color: #64748b;
        margin: .5rem 0;
    }

    .audio-status.gravando {
        color: #dc2626;
        font-weight: 700;
    }

    .audio-status.concluido {
        color: #15803d;
        font-weight: 600;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const gravacoes = {};

    document.querySelectorAll('.btn-gravar-audio').forEach(botao => {
        botao.addEventListener('click', async () => {
            const id = botao.dataset.questao;
            const parar = document.querySelector(
                `.btn-parar-audio[data-questao="${id}"]`
            );
            const status = document.getElementById(`audio-status-${id}`);
            const preview = document.getElementById(`audio-preview-${id}`);
            const input = document.getElementById(`audio-input-${id}`);

            if (!navigator.mediaDevices?.getUserMedia ||
                typeof MediaRecorder === 'undefined') {
                status.textContent =
                    'Seu navegador não permite gravar. Acesse por HTTPS ou localhost e use um navegador compatível.';
                return;
            }

            botao.disabled = true;
            status.classList.remove('concluido');
            status.textContent = 'Solicitando acesso ao microfone...';

            let stream;

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });

                const tipos = [
                    'audio/webm;codecs=opus',
                    'audio/webm',
                    'audio/ogg;codecs=opus',
                    'audio/ogg',
                    'audio/mp4'
                ];

                const mime = tipos.find(tipo =>
                    MediaRecorder.isTypeSupported(tipo)
                );

                const recorder = mime
                    ? new MediaRecorder(stream, { mimeType: mime })
                    : new MediaRecorder(stream);

                const partes = [];
                gravacoes[id] = { recorder, stream, partes };

                recorder.addEventListener('dataavailable', evento => {
                    if (evento.data && evento.data.size > 0) {
                        partes.push(evento.data);
                    }
                });

                recorder.addEventListener('error', evento => {
                    console.error('Erro no MediaRecorder:', evento);
                    status.textContent = 'Ocorreu um erro durante a gravação.';
                });

                recorder.addEventListener('stop', () => {
                    const tipo = recorder.mimeType || 'audio/webm';
                    const blob = new Blob(partes, { type: tipo });

                    if (blob.size === 0) {
                        status.textContent = 'A gravação ficou vazia. Tente novamente.';
                        botao.classList.remove('d-none');
                        botao.disabled = false;
                        parar.classList.add('d-none');
                        stream.getTracks().forEach(track => track.stop());
                        delete gravacoes[id];
                        return;
                    }

                    let extensao = 'webm';
                    if (tipo.includes('ogg')) extensao = 'ogg';
                    else if (tipo.includes('mp4')) extensao = 'm4a';

                    const arquivo = new File(
                        [blob],
                        `resposta_${id}.${extensao}`,
                        { type: tipo }
                    );

                    const dados = new DataTransfer();
                    dados.items.add(arquivo);
                    input.files = dados.files;

                    if (preview.dataset.urlAnterior) {
                        URL.revokeObjectURL(preview.dataset.urlAnterior);
                    }

                    const url = URL.createObjectURL(blob);
                    preview.src = url;
                    preview.dataset.urlAnterior = url;
                    preview.classList.remove('d-none');

                    status.textContent = 'Gravação concluída! Ouça antes de enviar.';
                    status.classList.add('concluido');

                    botao.innerHTML = '<i class="fas fa-redo"></i> Gravar novamente';
                    botao.classList.remove('d-none');
                    botao.disabled = false;
                    parar.classList.add('d-none');

                    stream.getTracks().forEach(track => track.stop());
                    delete gravacoes[id];
                });

                recorder.start();

                botao.classList.add('d-none');
                parar.classList.remove('d-none');
                status.textContent = 'Gravando... fale sua resposta.';
                status.classList.add('gravando');
                botao.disabled = false;

                parar.onclick = () => {
                    const atual = gravacoes[id];

                    if (atual && atual.recorder.state !== 'inactive') {
                        parar.disabled = true;
                        atual.recorder.stop();
                    }
                };

            } catch (erro) {
                console.error('Não foi possível gravar áudio:', erro);

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                botao.disabled = false;
                status.classList.remove('gravando');

                if (erro.name === 'NotAllowedError' ||
                    erro.name === 'PermissionDeniedError') {
                    status.textContent =
                        'Acesso ao microfone negado. Permita o uso do microfone nas configurações do navegador.';
                } else if (erro.name === 'NotFoundError') {
                    status.textContent =
                        'Nenhum microfone foi encontrado neste dispositivo.';
                } else {
                    status.textContent =
                        'Não foi possível iniciar a gravação. Verifique o microfone e tente novamente.';
                }
            }
        });
    });

    const form = document.getElementById('formResposta');

    if (form) {
        form.addEventListener('submit', evento => {
            const inputsAudio = form.querySelectorAll('.audio-input');

            for (const input of inputsAudio) {
                if (!input.files || input.files.length === 0) {
                    evento.preventDefault();

                    const id = input.id.replace('audio-input-', '');
                    const status = document.getElementById(`audio-status-${id}`);

                    if (status) {
                        status.textContent = 'Grave sua resposta antes de enviar a atividade.';
                        status.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }

                    return;
                }
            }
        });
    }
});
</script>
@endpush

@endsection