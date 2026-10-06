
@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">

    <div class="container-fluid">

        <div class="row align-items-center">

            <div class="col-sm-6">

                <h3 class="mb-0 fw-bold">

                    {{ $atividade->titulo_atividade }}

                </h3>

            </div>


            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-end mb-0">

                    <li class="breadcrumb-item">

                        <a href="{{ route('aluno.dash') }}">

                            Home

                        </a>

                    </li>


                    <li class="breadcrumb-item">

                        <a href="{{ route('aluno.atividades.index') }}">

                            Atividades

                        </a>

                    </li>


                    <li class="breadcrumb-item active">

                        Responder

                    </li>

                </ol>

            </div>

        </div>

    </div>

</div>


<div class="app-content">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- MENSAGENS --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show mb-4"
                role="alert"
            >

                <i class="fas fa-circle-check me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif

<<<<<<< HEAD
=======
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
>>>>>>> 6f578f56ecd30dc7830ded11ab7a911becaaacd1

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show mb-4"
                role="alert"
            >

                <i class="fas fa-circle-exclamation me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROS DE VALIDAÇÃO --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger mb-4">

                <div class="fw-bold mb-2">

                    <i class="fas fa-circle-exclamation me-1"></i>

                    Verifique os seguintes erros:

                </div>


                <ul class="mb-0">

                    @foreach($errors->all() as $erro)

                        <li>

                            {{ $erro }}

                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- DESCRIÇÃO --}}
        {{-- ========================================================= --}}

        @if($atividade->descricao_atividade)

            <div class="d-card fade-up mb-4">

                <div class="card-body p-3 d-flex align-items-start gap-3">

                    <div
                        class="tbl-icon-wrap"
                        style="min-width:38px;"
                    >

                        <i class="fas fa-circle-info"></i>

                    </div>


                    <div>

                        <div
                            style="
                                font-weight:600;
                                font-size:.88rem;
                                color:#1e293b;
                                margin-bottom:.2rem;
                            "
                        >

                            @if($atividade->tipo_atividade === 'pronuncia')

                                Instruções do Teste de Pronúncia

                            @else

                                Instruções da Atividade

                            @endif

                        </div>


                        <div
                            style="
                                font-size:.82rem;
                                color:#64748b;
                                line-height:1.6;
                            "
                        >

                            {{ $atividade->descricao_atividade }}

                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ÁUDIO DA ATIVIDADE --}}
        {{-- CONVERSA / LEITURA --}}
        {{-- ========================================================= --}}

        @if(
            $atividade->audio &&
            in_array(
                $atividade->tipo_atividade,
                ['conversa', 'leitura']
            )
        )

            <div class="d-card fade-up mb-4">

                <div class="d-card-header">

                    <h6>

                        @if($atividade->tipo_atividade === 'conversa')

                            <i class="fas fa-headphones text-primary"></i>

                            Áudio da Conversa

                        @else

                            <i class="fas fa-volume-high text-primary"></i>

                            Áudio da Leitura

                        @endif

                    </h6>

                </div>


                <div class="card-body p-3">

                    <div class="audio-atividade">

                        <div class="audio-atividade-icon">

                            @if($atividade->tipo_atividade === 'conversa')

                                <i class="fas fa-headphones"></i>

                            @else

                                <i class="fas fa-book-open"></i>

                            @endif

                        </div>


                        <div class="audio-atividade-info">

                            <div class="audio-atividade-titulo">

                                @if($atividade->tipo_atividade === 'conversa')

                                    Ouça a conversa antes de responder

                                @else

                                    Ouça o áudio da leitura

                                @endif

                            </div>


                            <div class="audio-atividade-subtitulo">

                                Você pode ouvir quantas vezes precisar.

                            </div>

                        </div>

                    </div>


                    <audio
                        controls
                        preload="metadata"
                        class="audio-player"
                    >

                        <source
                            src="{{ asset('storage/' . $atividade->audio) }}"
                            type="audio/mpeg"
                        >

                        Seu navegador não suporta reprodução de áudio.

                    </audio>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ATIVIDADE JÁ RESPONDIDA --}}
        {{-- ========================================================= --}}

        @if(
            $resposta &&
            in_array(
                $resposta->status_resposta,
                ['ENVIADA', 'CORRIGIDA']
            )
        )


            {{-- ===================================================== --}}
            {{-- CORRIGIDA --}}
            {{-- ===================================================== --}}

            @if($resposta->status_resposta === 'CORRIGIDA')

                <div class="fade-up mb-4">

                    <div
                        style="
                            background:linear-gradient(
                                135deg,
                                #ecfdf5,
                                #d1fae5
                            );
                            border-radius:16px;
                            padding:1.5rem;
                            border:1.5px solid #bbf7d0;
                        "
                    >

                        <div
                            class="d-flex align-items-center gap-3 mb-3"
                        >

                            <div
                                style="
                                    width:52px;
                                    height:52px;
                                    border-radius:14px;
                                    background:#dcfce7;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                "
                            >

                                <i
                                    class="fas fa-check-circle"
                                    style="
                                        font-size:1.4rem;
                                        color:#16a34a;
                                    "
                                ></i>

                            </div>


                            <div class="flex-grow-1">

                                <div
                                    style="
                                        font-weight:700;
                                        font-size:1.05rem;
                                        color:#065f46;
                                    "
                                >

                                    Atividade Corrigida

                                </div>


                                <div
                                    style="
                                        font-size:.78rem;
                                        color:#16a34a;
                                    "
                                >

                                    Seu professor avaliou suas respostas

                                </div>

                            </div>


                            <div style="text-align:center;">

                                <div
                                    style="
                                        font-size:2rem;
                                        font-weight:900;
                                        color:#059669;
                                        line-height:1;
                                    "
                                >

                                    {{ $resposta->nota }}

                                </div>


                                <div
                                    style="
                                        font-size:.65rem;
                                        font-weight:600;
                                        color:#16a34a;
                                        text-transform:uppercase;
                                        letter-spacing:.08em;
                                    "
                                >

                                    /10

                                </div>

                            </div>

                        </div>


                        @if($resposta->feedback_professor)

                            <div
                                style="
                                    background:rgba(255,255,255,.6);
                                    border-radius:10px;
                                    padding:.75rem 1rem;
                                    border-left:3px solid #10b981;
                                "
                            >

                                <div
                                    style="
                                        font-size:.68rem;
                                        font-weight:700;
                                        text-transform:uppercase;
                                        letter-spacing:.08em;
                                        color:#059669;
                                        margin-bottom:.3rem;
                                    "
                                >

                                    Feedback do Professor

                                </div>


                                <div
                                    style="
                                        font-size:.85rem;
                                        color:#065f46;
                                        line-height:1.5;
                                    "
                                >

                                    {{ $resposta->feedback_professor }}

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            @else


                {{-- ================================================= --}}
                {{-- ENVIADA --}}
                {{-- ================================================= --}}

                <div class="fade-up mb-4">

                    <div
                        class="dash-ok-banner"
                        style="
                            background:linear-gradient(
                                135deg,
                                #fffbeb,
                                #fef3c7
                            );
                            border-color:#fde68a;
                        "
                    >

                        <div
                            class="dash-ok-icon"
                            style="
                                background:#fde68a;
                                color:#b45309;
                            "
                        >

                            <i class="fas fa-paper-plane"></i>

                        </div>


                        <div>

                            <div
                                class="dash-ok-title"
                                style="color:#92400e;"
                            >

                                Atividade enviada!

                            </div>


                            <div
                                class="dash-ok-sub"
                                style="color:#b45309;"
                            >

                                Aguardando correção do professor.

                            </div>

                        </div>

                    </div>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- RESPOSTAS ENVIADAS --}}
            {{-- ===================================================== --}}

            <div class="d-card fade-up mb-4">

                <div class="d-card-header">

                    <h6>

                        <i class="fas fa-clipboard-check text-success"></i>

                        Suas Respostas

                    </h6>

                </div>


                <div class="card-body p-3">


                    @foreach($atividade->questoes as $i => $questao)

                        @php

                            $rq = $resposta
                                ->respostasQuestoes
                                ->firstWhere(
                                    'id_questao',
                                    $questao->id_questao
                                );

                        @endphp


                        <div
                            style="
                                padding:1rem;
                                margin-bottom:.75rem;
                                border-radius:12px;
                                border:1.5px solid #f1f5f9;
                                background:#fafbfc;
                            "
                        >

                            <div
                                class="d-flex align-items-start gap-2 mb-2"
                            >

                                <span
                                    style="
                                        width:28px;
                                        height:28px;
                                        border-radius:8px;
                                        background:linear-gradient(
                                            135deg,
                                            #6366f1,
                                            #818cf8
                                        );
                                        display:inline-flex;
                                        align-items:center;
                                        justify-content:center;
                                        color:#fff;
                                        font-size:.72rem;
                                        font-weight:700;
                                        flex-shrink:0;
                                    "
                                >

                                    {{ $i + 1 }}

                                </span>


                                <div
                                    style="
                                        font-weight:600;
                                        font-size:.88rem;
                                        color:#1e293b;
                                    "
                                >

                                    @if($atividade->tipo_atividade === 'pronuncia')

                                        Pronuncie:

                                    @endif

                                    {{ $questao->enunciado }}

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- PRONÚNCIA --}}
                            {{-- ================================================= --}}

                            @if($atividade->tipo_atividade === 'pronuncia')

                                <div style="padding-left:36px;">

                                    @if($rq?->audio_resposta)

                                        <div class="audio-resposta-enviada">

                                            <div class="audio-resposta-icon">

                                                <i class="fas fa-microphone"></i>

                                            </div>


                                            <div>

                                                <div class="audio-resposta-titulo">

                                                    Sua pronúncia

                                                </div>


                                                <audio
                                                    controls
                                                    class="
                                                        audio-player
                                                        audio-player-small
                                                    "
                                                >

                                                    <source
                                                        src="{{ asset('storage/' . $rq->audio_resposta) }}"
                                                    >

                                                    Seu navegador não suporta
                                                    reprodução de áudio.

                                                </audio>

                                            </div>

                                        </div>

                                    @else

                                        <div
                                            style="
                                                color:#64748b;
                                                background:#fff;
                                                border-radius:8px;
                                                padding:.75rem;
                                                border:1px solid #e2e8f0;
                                            "
                                        >

                                            Nenhuma gravação enviada.

                                        </div>

                                    @endif

                                </div>


                            {{-- ================================================= --}}
                            {{-- MÚLTIPLA ESCOLHA --}}
                            {{-- ================================================= --}}

                            @elseif($questao->tipo_questao === 'multipla_escolha')

                                <div
                                    style="
                                        font-size:.82rem;
                                        color:#64748b;
                                        padding-left:36px;
                                    "
                                >

                                    A) {{ $questao->opcao_a }}

                                    &nbsp;&nbsp;

                                    B) {{ $questao->opcao_b }}

                                    &nbsp;&nbsp;

                                    C) {{ $questao->opcao_c }}

                                    &nbsp;&nbsp;

                                    D) {{ $questao->opcao_d }}

                                </div>


                                <div
                                    style="
                                        padding-left:36px;
                                        margin-top:.5rem;
                                        font-size:.85rem;
                                    "
                                >

                                    Sua resposta:

                                    <strong style="color:#1e293b;">

                                        {{ $rq?->resposta_aluno ?? '—' }}

                                    </strong>


                                    @if($rq?->correta !== null)

                                        @if($rq->correta)

                                            <span
                                                class="
                                                    tbl-status
                                                    tbl-status-ativo
                                                    ms-2
                                                "
                                            >

                                                <span class="tbl-status-dot"></span>

                                                Correta

                                            </span>

                                        @else

                                            <span
                                                class="
                                                    tbl-status
                                                    tbl-status-cancelado
                                                    ms-2
                                                "
                                            >

                                                <span class="tbl-status-dot"></span>

                                                Errada

                                            </span>

                                        @endif

                                    @endif

                                </div>


                            {{-- ================================================= --}}
                            {{-- ÁUDIO --}}
                            {{-- ================================================= --}}

                            @elseif($questao->tipo_questao === 'audio')

                                <div style="padding-left:36px;">

                                    @if($rq?->audio_resposta)

                                        <div class="audio-resposta-enviada">

                                            <div class="audio-resposta-icon">

                                                <i class="fas fa-microphone"></i>

                                            </div>


                                            <div>

                                                <div class="audio-resposta-titulo">

                                                    Sua gravação

                                                </div>


                                                <audio
                                                    controls
                                                    class="
                                                        audio-player
                                                        audio-player-small
                                                    "
                                                >

                                                    <source
                                                        src="{{ asset('storage/' . $rq->audio_resposta) }}"
                                                    >

                                                    Seu navegador não suporta
                                                    reprodução de áudio.

                                                </audio>

                                            </div>

                                        </div>

                                    @else

                                        <div
                                            style="
                                                color:#64748b;
                                                background:#fff;
                                                border-radius:8px;
                                                padding:.75rem;
                                                border:1px solid #e2e8f0;
                                            "
                                        >

                                            Nenhuma gravação enviada.

                                        </div>

                                    @endif

                                </div>


                            {{-- ================================================= --}}
                            {{-- TEXTO --}}
                            {{-- ================================================= --}}

                            @else

                                <div
                                    style="
                                        padding-left:36px;
                                        font-size:.85rem;
                                        color:#475569;
                                        background:#fff;
                                        border-radius:8px;
                                        padding:.5rem .75rem;
                                        margin-top:.5rem;
                                        border:1px solid #e2e8f0;
                                    "
                                >

                                    {{ $rq?->resposta_aluno ?? '—' }}

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>


            <a
                href="{{ route('aluno.atividades.index') }}"
                class="del-btn-cancelar mb-5"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Voltar para Atividades

            </a>


        @else


            {{-- ========================================================= --}}
            {{-- FORMULÁRIO --}}
            {{-- ========================================================= --}}

            <form
                action="{{
                    route(
                        'aluno.atividades.responder',
                        $atividade->id_atividade
                    )
                }}"
                method="POST"
                enctype="multipart/form-data"
                id="formResposta"
            >

                @csrf


                @foreach($atividade->questoes as $i => $questao)

                    <div class="d-card fade-up mb-3">

                        <div class="d-card-header">

                            <h6>

                                <span
                                    style="
                                        width:28px;
                                        height:28px;
                                        border-radius:8px;
                                        background:linear-gradient(
                                            135deg,
                                            #6366f1,
                                            #818cf8
                                        );
                                        display:inline-flex;
                                        align-items:center;
                                        justify-content:center;
                                        color:#fff;
                                        font-size:.72rem;
                                        font-weight:700;
                                    "
                                >

                                    {{ $i + 1 }}

                                </span>


                                Questão {{ $i + 1 }}

                            </h6>


                            {{-- ================================================= --}}
                            {{-- TIPO --}}
                            {{-- ================================================= --}}

                            @if($atividade->tipo_atividade === 'pronuncia')

                                <span class="tbl-badge">

                                    <i class="fas fa-microphone me-1"></i>

                                    Pronúncia

                                </span>

                            @elseif($questao->tipo_questao === 'multipla_escolha')

                                <span class="tbl-badge blue">

                                    <i class="fas fa-list-ol me-1"></i>

                                    Múltipla Escolha

                                </span>

                            @elseif($questao->tipo_questao === 'audio')

                                <span class="tbl-badge">

                                    <i class="fas fa-microphone me-1"></i>

                                    Resposta em Áudio

                                </span>

                            @else

                                <span class="tbl-badge">

                                    <i class="fas fa-pen me-1"></i>

                                    Dissertativa

                                </span>

                            @endif

                        </div>


                        <div class="card-body p-3">


                            {{-- ================================================= --}}
                            {{-- TESTE DE PRONÚNCIA --}}
                            {{-- ================================================= --}}

                            @if($atividade->tipo_atividade === 'pronuncia')

                                <div class="teste-pronuncia">


                                    {{-- CABEÇALHO --}}

                                    <div class="teste-pronuncia-topo">

                                        <div class="teste-pronuncia-icone">

                                            <i class="fas fa-microphone"></i>

                                        </div>


                                        <div>

                                            <div class="teste-pronuncia-titulo">

                                                Teste de Pronúncia

                                            </div>


                                            <div class="teste-pronuncia-subtitulo">

                                                Leia a frase abaixo e grave sua
                                                pronúncia.

                                            </div>

                                        </div>

                                    </div>


                                    {{-- FRASE --}}

                                    <div class="teste-pronuncia-frase">

                                        <div class="teste-pronuncia-label">

                                            <i class="fas fa-volume-high me-1"></i>

                                            Pronuncie:

                                        </div>


                                        <div class="teste-pronuncia-texto">

                                            {{ $questao->enunciado }}

                                        </div>

                                    </div>


                                    {{-- CONTROLES --}}

                                    <div class="teste-pronuncia-controles">


                                        <button
                                            type="button"
                                            class="btn-gravar-pronuncia"
                                            data-questao="{{ $questao->id_questao }}"
                                        >

                                            <i class="fas fa-microphone"></i>

                                            Gravar minha pronúncia

                                        </button>


                                        <button
                                            type="button"
                                            class="btn-parar-pronuncia d-none"
                                            data-questao="{{ $questao->id_questao }}"
                                        >

                                            <i class="fas fa-stop"></i>

                                            Parar gravação

                                        </button>

                                    </div>


                                    {{-- STATUS --}}

                                    <div
                                        class="status-pronuncia"
                                        id="status-pronuncia-{{ $questao->id_questao }}"
                                    >

                                        <i class="fas fa-circle-info"></i>

                                        Clique em "Gravar minha pronúncia"
                                        para começar.

                                    </div>


                                    {{-- PREVIEW --}}

                                    <audio
                                        controls
                                        class="preview-pronuncia d-none"
                                        id="preview-pronuncia-{{ $questao->id_questao }}"
                                    ></audio>


                                    {{-- INPUT REAL --}}

                                    <input
                                        type="file"
                                        name="audio_resposta_{{ $questao->id_questao }}"
                                        id="input-pronuncia-{{ $questao->id_questao }}"
                                        class="input-pronuncia audio-input"
                                        accept="audio/*"
                                        required
                                    >

                                </div>


                            {{-- ================================================= --}}
                            {{-- MÚLTIPLA ESCOLHA --}}
                            {{-- ================================================= --}}

                            @elseif($questao->tipo_questao === 'multipla_escolha')


                                <p
                                    style="
                                        font-weight:600;
                                        font-size:.9rem;
                                        color:#1e293b;
                                        margin-bottom:1rem;
                                    "
                                >

                                    {{ $questao->enunciado }}

                                </p>


                                <div class="d-flex flex-column gap-2">

                                    @foreach([
                                        'A' => $questao->opcao_a,
                                        'B' => $questao->opcao_b,
                                        'C' => $questao->opcao_c,
                                        'D' => $questao->opcao_d
                                    ] as $letra => $opcao)

                                        @if($opcao)

                                            <label class="atv-option">

                                                <input
                                                    type="radio"
                                                    name="questao_{{ $questao->id_questao }}"
                                                    value="{{ $letra }}"
                                                    required
                                                    class="atv-radio"
                                                >


                                                <span class="atv-option-letra">

                                                    {{ $letra }}

                                                </span>


                                                <span class="atv-option-text">

                                                    {{ $opcao }}

                                                </span>

                                            </label>

                                        @endif

                                    @endforeach

                                </div>


                            {{-- ================================================= --}}
                            {{-- ÁUDIO NORMAL --}}
                            {{-- ================================================= --}}

                            @elseif($questao->tipo_questao === 'audio')


                                <p
                                    style="
                                        font-weight:600;
                                        font-size:.9rem;
                                        color:#1e293b;
                                        margin-bottom:1rem;
                                    "
                                >

                                    {{ $questao->enunciado }}

                                </p>


                                <div class="audio-gravacao-container">


                                    <div class="audio-gravacao-info">

                                        <div class="audio-gravacao-icon">

                                            <i class="fas fa-microphone"></i>

                                        </div>


                                        <div>

                                            <div class="audio-gravacao-titulo">

                                                Grave sua resposta

                                            </div>


                                            <div class="audio-gravacao-subtitulo">

                                                Clique em gravar e fale sua
                                                resposta.

                                            </div>

                                        </div>

                                    </div>


                                    <div class="audio-gravacao-botoes">

                                        <button
                                            type="button"
                                            class="btn-gravar-audio"
                                            data-questao="{{ $questao->id_questao }}"
                                        >

                                            <i class="fas fa-microphone"></i>

                                            Gravar

                                        </button>


                                        <button
                                            type="button"
                                            class="btn-parar-audio d-none"
                                            data-questao="{{ $questao->id_questao }}"
                                        >

                                            <i class="fas fa-stop"></i>

                                            Parar

                                        </button>

                                    </div>


                                    <div
                                        class="audio-status"
                                        id="audio-status-{{ $questao->id_questao }}"
                                    >

                                        Aguardando gravação...

                                    </div>


                                    <audio
                                        controls
                                        class="audio-preview d-none"
                                        id="audio-preview-{{ $questao->id_questao }}"
                                    ></audio>


                                    <input
                                        type="file"
                                        name="audio_resposta_{{ $questao->id_questao }}"
                                        id="audio-input-{{ $questao->id_questao }}"
                                        class="audio-input"
                                        accept="audio/*"
                                        required
                                    >

                                </div>


                            {{-- ================================================= --}}
                            {{-- TEXTO --}}
                            {{-- ================================================= --}}

                            @else


                                <p
                                    style="
                                        font-weight:600;
                                        font-size:.9rem;
                                        color:#1e293b;
                                        margin-bottom:1rem;
                                    "
                                >

                                    {{ $questao->enunciado }}

                                </p>


                                <textarea
                                    name="questao_{{ $questao->id_questao }}"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Digite sua resposta..."
                                    required
                                    style="
                                        border-radius:10px;
                                        border-color:#e2e8f0;
                                    "
                                ></textarea>


                            @endif

                        </div>

                    </div>

                @endforeach


                {{-- ========================================================= --}}
                {{-- BOTÕES --}}
                {{-- ========================================================= --}}

                <div
                    class="
                        d-flex
                        gap-2
                        mb-5
                        fade-up
                    "
                >


                    <button
                        type="submit"
                        class="tbl-btn-success"
                        style="
                            padding:.6rem 1.5rem;
                            font-size:.85rem;
                        "
                        id="btnEnviarAtividade"
                        onclick="return confirmarEnvio()"
                    >

                        <i class="fas fa-paper-plane"></i>

                        Enviar Atividade

                    </button>


                    <a
                        href="{{ route('aluno.atividades.index') }}"
                        class="del-btn-cancelar"
                    >

                        <i class="fas fa-arrow-left"></i>

                        Cancelar

                    </a>

                </div>

            </form>

        @endif

    </div>

</div>


@push('scripts')

<style>


/* ============================================================= */
/* MÚLTIPLA ESCOLHA */
/* ============================================================= */

.atv-option {

    display:flex;

    align-items:center;

    gap:.75rem;

    padding:.75rem 1rem;

    border:1.5px solid #e2e8f0;

    border-radius:12px;

    cursor:pointer;

    transition:all .2s;

    background:#fff;

}


.atv-option:hover {

    border-color:#6366f1;

    background:#f5f3ff;

}


.atv-option:has(input:checked) {

    border-color:#6366f1;

    background:linear-gradient(
        135deg,
        #eef3ff,
        #e0e7ff
    );

    box-shadow:
        0 2px 8px rgba(99,102,241,.12);

}


.atv-radio {

    display:none;

}


.atv-option-letra {

    width:32px;

    height:32px;

    border-radius:8px;

    background:#f1f5f9;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    font-size:.82rem;

    color:#64748b;

    flex-shrink:0;

    transition:all .2s;

}


.atv-option:has(input:checked)
.atv-option-letra {

    background:linear-gradient(
        135deg,
        #6366f1,
        #818cf8
    );

    color:#fff;

}


.atv-option-text {

    font-size:.88rem;

    color:#1e293b;

    font-weight:500;

}


/* ============================================================= */
/* ÁUDIO DA ATIVIDADE */
/* ============================================================= */

.audio-atividade {

    display:flex;

    align-items:center;

    gap:1rem;

    padding:1rem;

    border-radius:12px;

    background:#f8fafc;

    border:1px solid #e2e8f0;

    margin-bottom:1rem;

}


.audio-atividade-icon {

    width:48px;

    height:48px;

    border-radius:12px;

    background:#eef2ff;

    color:#6366f1;

    display:flex;

    align-items:center;

    justify-content:center;

    flex-shrink:0;

    font-size:1.2rem;

}


.audio-atividade-info {

    flex:1;

}


.audio-atividade-titulo {

    font-weight:700;

    color:#1e293b;

    font-size:.9rem;

}


.audio-atividade-subtitulo {

    color:#64748b;

    font-size:.78rem;

    margin-top:.2rem;

}


.audio-player {

    width:100%;

    height:42px;

}


/* ============================================================= */
/* TESTE DE PRONÚNCIA */
/* ============================================================= */

.teste-pronuncia {

    border:1.5px solid #e2e8f0;

    border-radius:16px;

    padding:1.25rem;

    background:#fafbfc;

}


.teste-pronuncia-topo {

    display:flex;

    align-items:center;

    gap:.85rem;

    margin-bottom:1.25rem;

}


.teste-pronuncia-icone {

    width:50px;

    height:50px;

    border-radius:14px;

    background:#eef2ff;

    color:#6366f1;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.25rem;

    flex-shrink:0;

}


.teste-pronuncia-titulo {

    font-size:.95rem;

    font-weight:700;

    color:#1e293b;

}


.teste-pronuncia-subtitulo {

    margin-top:.2rem;

    font-size:.78rem;

    color:#64748b;

}


.teste-pronuncia-frase {

    background:#fff;

    border:1px solid #e2e8f0;

    border-radius:12px;

    padding:1rem;

    margin-bottom:1rem;

}


.teste-pronuncia-label {

    font-size:.68rem;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:.06em;

    color:#6366f1;

    margin-bottom:.45rem;

}


.teste-pronuncia-texto {

    font-size:1rem;

    font-weight:600;

    line-height:1.6;

    color:#1e293b;

}


.teste-pronuncia-controles {

    display:flex;

    gap:.6rem;

    flex-wrap:wrap;

}


.btn-gravar-pronuncia,

.btn-parar-pronuncia {

    border:none;

    border-radius:10px;

    padding:.7rem 1.1rem;

    font-size:.82rem;

    font-weight:700;

    cursor:pointer;

    transition:all .2s;

}


.btn-gravar-pronuncia {

    background:#dc2626;

    color:#fff;

}


.btn-gravar-pronuncia:hover {

    background:#b91c1c;

    transform:translateY(-1px);

}


.btn-parar-pronuncia {

    background:#1e293b;

    color:#fff;

}


.btn-parar-pronuncia:hover {

    background:#0f172a;

    transform:translateY(-1px);

}


.status-pronuncia {

    margin-top:.8rem;

    color:#64748b;

    font-size:.76rem;

    line-height:1.5;

}


.preview-pronuncia {

    width:100%;

    height:42px;

    margin-top:.8rem;

}


.input-pronuncia {

    display:none;

}


/* ============================================================= */
/* GRAVAÇÃO NORMAL */
/* ============================================================= */

.audio-gravacao-container {

    border:1.5px solid #e2e8f0;

    border-radius:14px;

    padding:1rem;

    background:#fafbfc;

}


.audio-gravacao-info {

    display:flex;

    align-items:center;

    gap:.75rem;

    margin-bottom:1rem;

}


.audio-gravacao-icon {

    width:44px;

    height:44px;

    border-radius:12px;

    background:#fef2f2;

    color:#dc2626;

    display:flex;

    align-items:center;

    justify-content:center;

    flex-shrink:0;

}


.audio-gravacao-titulo {

    font-weight:700;

    font-size:.9rem;

    color:#1e293b;

}


.audio-gravacao-subtitulo {

    font-size:.78rem;

    color:#64748b;

    margin-top:.15rem;

}


.audio-gravacao-botoes {

    display:flex;

    gap:.5rem;

    flex-wrap:wrap;

}


.btn-gravar-audio,

.btn-parar-audio {

    border:none;

    border-radius:10px;

    padding:.65rem 1rem;

    font-size:.82rem;

    font-weight:600;

    cursor:pointer;

    transition:all .2s;

}


.btn-gravar-audio {

    background:#dc2626;

    color:#fff;

}


.btn-gravar-audio:hover {

    background:#b91c1c;

}


.btn-parar-audio {

    background:#1e293b;

    color:#fff;

}


.btn-parar-audio:hover {

    background:#0f172a;

}


.audio-status {

    margin-top:.75rem;

    font-size:.75rem;

    color:#64748b;

    line-height:1.5;

}


.audio-preview {

    width:100%;

    margin-top:.75rem;

    height:40px;

}


.audio-input {

    display:none;

}


/* ============================================================= */
/* ÁUDIO ENVIADO */
/* ============================================================= */

.audio-resposta-enviada {

    display:flex;

    align-items:center;

    gap:.75rem;

}


.audio-resposta-icon {

    width:38px;

    height:38px;

    border-radius:10px;

    background:#eef2ff;

    color:#6366f1;

    display:flex;

    align-items:center;

    justify-content:center;

    flex-shrink:0;

}


.audio-resposta-titulo {

    font-size:.8rem;

    font-weight:700;

    color:#334155;

    margin-bottom:.3rem;

}


.audio-player-small {

    width:300px;

    max-width:100%;

    height:36px;

}


/* ============================================================= */
/* GRAVAÇÃO ATIVA */
/* ============================================================= */

.gravando {

    animation:pulsarMicrofone 1.2s infinite;

}


@keyframes pulsarMicrofone {

    0% {

        box-shadow:
            0 0 0 0 rgba(220,38,38,.35);

    }

    70% {

        box-shadow:
            0 0 0 10px rgba(220,38,38,0);

    }

    100% {

        box-shadow:
            0 0 0 0 rgba(220,38,38,0);

    }

}


/* ============================================================= */
/* RESPONSIVO */
/* ============================================================= */

@media (max-width: 576px) {

    .teste-pronuncia {

        padding:.9rem;

    }


    .teste-pronuncia-texto {

        font-size:.9rem;

    }


    .btn-gravar-pronuncia,
    .btn-parar-pronuncia {

        width:100%;

        justify-content:center;

    }


    .audio-resposta-enviada {

        align-items:flex-start;

    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | GRAVAÇÕES
    |--------------------------------------------------------------------------
    */

    const gravacoes = {};


    /*
    |--------------------------------------------------------------------------
    | MIME TYPE
    |--------------------------------------------------------------------------
    */

    function obterMimeType() {

        const tipos = [

            'audio/webm;codecs=opus',

            'audio/webm',

            'audio/ogg;codecs=opus',

            'audio/ogg'

        ];


        if (typeof MediaRecorder === 'undefined') {

            return '';

        }


        for (const tipo of tipos) {

            if (MediaRecorder.isTypeSupported(tipo)) {

                return tipo;

            }

        }


        return '';

    }


    /*
    |--------------------------------------------------------------------------
    | EXTENSÃO
    |--------------------------------------------------------------------------
    */

    function obterExtensao(mimeType) {

        mimeType = mimeType || '';


        if (mimeType.includes('ogg')) {

            return 'ogg';

        }


        if (
            mimeType.includes('mp4') ||
            mimeType.includes('m4a')
        ) {

            return 'm4a';

        }


        if (mimeType.includes('mpeg')) {

            return 'mp3';

        }


        if (mimeType.includes('wav')) {

            return 'wav';

        }


        return 'webm';

    }


    /*
    |--------------------------------------------------------------------------
    | GRAVAÇÃO GENÉRICA
    |--------------------------------------------------------------------------
    */

    async function iniciarGravacao(config) {


        const {

            idQuestao,

            botaoGravar,

            botaoParar,

            status,

            preview,

            input,

            textoGravando,

            textoConcluido

        } = config;


        /*
        |--------------------------------------------------------------------------
        | VERIFICAR MICROFONE
        |--------------------------------------------------------------------------
        */

        if (
            !navigator.mediaDevices ||
            !navigator.mediaDevices.getUserMedia
        ) {

            status.innerHTML = `

                <span style="color:#dc2626;">

                    <i class="fas fa-circle-exclamation"></i>

                    Seu navegador não permite gravação de áudio.

                </span>

            `;

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | VERIFICAR MEDIA RECORDER
        |--------------------------------------------------------------------------
        */

        if (typeof MediaRecorder === 'undefined') {

            status.innerHTML = `

                <span style="color:#dc2626;">

                    <i class="fas fa-circle-exclamation"></i>

                    Seu navegador não suporta gravação de áudio.

                </span>

            `;

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | EVITAR DUAS GRAVAÇÕES AO MESMO TEMPO
        |--------------------------------------------------------------------------
        */

        const outraGravacao =
            Object.values(gravacoes).find(function (item) {

                return (
                    item &&
                    item.recorder &&
                    item.recorder.state === 'recording'
                );

            });


        if (outraGravacao) {

            alert(
                'Existe outra gravação em andamento. ' +
                'Pare a gravação anterior antes de iniciar outra.'
            );

            return;

        }


        try {


            /*
            |--------------------------------------------------------------------------
            | MICROFONE
            |--------------------------------------------------------------------------
            */

            const stream =
                await navigator.mediaDevices.getUserMedia({

                    audio: {

                        echoCancellation: true,

                        noiseSuppression: true,

                        autoGainControl: true

                    }

                });


            /*
            |--------------------------------------------------------------------------
            | FORMATO
            |--------------------------------------------------------------------------
            */

            const mimeType =
                obterMimeType();


            /*
            |--------------------------------------------------------------------------
            | RECORDER
            |--------------------------------------------------------------------------
            */

            const recorder =
                mimeType

                    ? new MediaRecorder(
                        stream,
                        {
                            mimeType: mimeType
                        }
                    )

                    : new MediaRecorder(stream);


            const chunks = [];


            /*
            |--------------------------------------------------------------------------
            | DADOS
            |--------------------------------------------------------------------------
            */

            recorder.addEventListener(
                'dataavailable',
                function (event) {

                    if (
                        event.data &&
                        event.data.size > 0
                    ) {

                        chunks.push(event.data);

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | QUANDO PARAR
            |--------------------------------------------------------------------------
            */

            recorder.addEventListener(
                'stop',
                function () {


                    const tipoAudio =
                        recorder.mimeType ||
                        mimeType ||
                        'audio/webm';


                    /*
                    |--------------------------------------------------------------------------
                    | BLOB
                    |--------------------------------------------------------------------------
                    */

                    const blob =
                        new Blob(
                            chunks,
                            {
                                type: tipoAudio
                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | EXTENSÃO
                    |--------------------------------------------------------------------------
                    */

                    const extensao =
                        obterExtensao(tipoAudio);


                    /*
                    |--------------------------------------------------------------------------
                    | ARQUIVO
                    |--------------------------------------------------------------------------
                    */

                    const arquivo =
                        new File(
                            [blob],
                            `resposta_${idQuestao}.${extensao}`,
                            {
                                type: tipoAudio,

                                lastModified: Date.now()
                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | DATA TRANSFER
                    |--------------------------------------------------------------------------
                    */

                    const dataTransfer =
                        new DataTransfer();


                    dataTransfer.items.add(arquivo);


                    input.files =
                        dataTransfer.files;


                    /*
                    |--------------------------------------------------------------------------
                    | PREVIEW
                    |--------------------------------------------------------------------------
                    */

                    const url =
                        URL.createObjectURL(blob);


                    preview.src = url;

                    preview.classList.remove('d-none');

                    preview.load();


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    status.innerHTML = `

                        <span style="color:#16a34a;font-weight:600;">

                            <i class="fas fa-circle-check"></i>

                            ${textoConcluido}

                        </span>

                        <br>

                        Você pode ouvir sua gravação ou gravar novamente.

                    `;


                    /*
                    |--------------------------------------------------------------------------
                    | SALVAR REFERÊNCIA
                    |--------------------------------------------------------------------------
                    */

                    gravacoes[idQuestao] = {

                        recorder: recorder,

                        blob: blob,

                        arquivo: arquivo,

                        url: url,

                        stream: stream

                    };


                    /*
                    |--------------------------------------------------------------------------
                    | LIBERAR MICROFONE
                    |--------------------------------------------------------------------------
                    */

                    stream
                        .getTracks()
                        .forEach(function (track) {

                            track.stop();

                        });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | INICIAR
            |--------------------------------------------------------------------------
            */

            recorder.start();


            /*
            |--------------------------------------------------------------------------
            | SALVAR
            |--------------------------------------------------------------------------
            */

            gravacoes[idQuestao] = {

                recorder: recorder,

                stream: stream

            };


            /*
            |--------------------------------------------------------------------------
            | BOTÕES
            |--------------------------------------------------------------------------
            */

            botaoGravar.classList.add('d-none');

            botaoParar.classList.remove('d-none');

            botaoParar.classList.add('gravando');


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            status.innerHTML = `

                <span
                    style="
                        color:#dc2626;
                        font-weight:700;
                    "
                >

                    <i class="fas fa-circle"></i>

                    Gravando...

                </span>

                ${textoGravando}

            `;


            /*
            |--------------------------------------------------------------------------
            | PARAR
            |--------------------------------------------------------------------------
            */

            botaoParar.onclick =
                function () {


                    if (
                        recorder.state !==
                        'inactive'
                    ) {

                        recorder.stop();

                    }


                    botaoParar.classList.add('d-none');

                    botaoParar.classList.remove('gravando');

                    botaoGravar.classList.remove('d-none');

                };


        } catch (erro) {


            console.error(
                'Erro ao gravar áudio:',
                erro
            );


            /*
            |--------------------------------------------------------------------------
            | PERMISSÃO NEGADA
            |--------------------------------------------------------------------------
            */

            if (
                erro.name ===
                'NotAllowedError'
            ) {

                status.innerHTML = `

                    <span style="color:#dc2626;">

                        <i class="fas fa-circle-exclamation"></i>

                        Permissão do microfone negada.

                    </span>

                    <br>

                    Permita o acesso ao microfone no navegador
                    para realizar o teste de pronúncia.

                `;

            }


            /*
            |--------------------------------------------------------------------------
            | MICROFONE NÃO ENCONTRADO
            |--------------------------------------------------------------------------
            */

            else if (
                erro.name ===
                'NotFoundError'
            ) {

                status.innerHTML = `

                    <span style="color:#dc2626;">

                        <i class="fas fa-circle-exclamation"></i>

                        Nenhum microfone foi encontrado.

                    </span>

                `;

            }


            /*
            |--------------------------------------------------------------------------
            | OUTRO ERRO
            |--------------------------------------------------------------------------
            */

            else {

                status.innerHTML = `

                    <span style="color:#dc2626;">

                        <i class="fas fa-circle-exclamation"></i>

                        Não foi possível iniciar a gravação.

                    </span>

                    <br>

                    Verifique as permissões do navegador.

                `;

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BOTÃO DE PRONÚNCIA
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.btn-gravar-pronuncia')
        .forEach(function (botao) {


            botao.addEventListener(
                'click',
                async function () {


                    const idQuestao =
                        this.dataset.questao;


                    const botaoParar =
                        document.querySelector(
                            `.btn-parar-pronuncia[data-questao="${idQuestao}"]`
                        );


                    const status =
                        document.getElementById(
                            `status-pronuncia-${idQuestao}`
                        );


                    const preview =
                        document.getElementById(
                            `preview-pronuncia-${idQuestao}`
                        );


                    const input =
                        document.getElementById(
                            `input-pronuncia-${idQuestao}`
                        );


                    await iniciarGravacao({

                        idQuestao: idQuestao,

                        botaoGravar: botao,

                        botaoParar: botaoParar,

                        status: status,

                        preview: preview,

                        input: input,

                        textoGravando:
                            'Fale a frase apresentada acima.',

                        textoConcluido:
                            'Pronúncia gravada com sucesso!'

                    });

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | BOTÃO DE ÁUDIO NORMAL
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.btn-gravar-audio')
        .forEach(function (botao) {


            botao.addEventListener(
                'click',
                async function () {


                    const idQuestao =
                        this.dataset.questao;


                    const botaoParar =
                        document.querySelector(
                            `.btn-parar-audio[data-questao="${idQuestao}"]`
                        );


                    const status =
                        document.getElementById(
                            `audio-status-${idQuestao}`
                        );


                    const preview =
                        document.getElementById(
                            `audio-preview-${idQuestao}`
                        );


                    const input =
                        document.getElementById(
                            `audio-input-${idQuestao}`
                        );


                    await iniciarGravacao({

                        idQuestao: idQuestao,

                        botaoGravar: botao,

                        botaoParar: botaoParar,

                        status: status,

                        preview: preview,

                        input: input,

                        textoGravando:
                            'Fale sua resposta.',

                        textoConcluido:
                            'Gravação concluída!'

                    });

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR ENVIO
    |--------------------------------------------------------------------------
    */

    window.confirmarEnvio = function () {


        const formulario =
            document.getElementById(
                'formResposta'
            );


        if (!formulario) {

            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | VERIFICAR GRAVAÇÕES
        |--------------------------------------------------------------------------
        */

        const audios =
            formulario.querySelectorAll(
                '.audio-input'
            );


        for (
            const input of audios
        ) {


            if (
                !input.files ||
                input.files.length === 0
            ) {


                const questao =
                    input.id
                        .replace(
                            'audio-input-',
                            ''
                        )
                        .replace(
                            'input-pronuncia-',
                            ''
                        );


                const isPronuncia =
                    input.classList.contains(
                        'input-pronuncia'
                    );


                if (isPronuncia) {

                    alert(
                        'Grave sua pronúncia antes de enviar a atividade.\n\n' +
                        'Questão: ' +
                        questao
                    );

                } else {

                    alert(
                        'Grave uma resposta em áudio antes de enviar a atividade.\n\n' +
                        'Questão: ' +
                        questao
                    );

                }


                return false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAÇÃO
        |--------------------------------------------------------------------------
        */

        return confirm(

            'Tem certeza que deseja enviar a atividade?\n\n' +

            'Depois do envio, suas respostas não poderão ser alteradas.'

        );

    };


    /*
    |--------------------------------------------------------------------------
    | NÃO ENVIAR ENQUANTO ESTIVER GRAVANDO
    |--------------------------------------------------------------------------
    */

    const formulario =
        document.getElementById(
            'formResposta'
        );


    if (formulario) {


        formulario.addEventListener(
            'submit',
            function (event) {


                const gravando =
                    Object.values(
                        gravacoes
                    ).some(function (item) {


                        return (
                            item &&
                            item.recorder &&
                            item.recorder.state ===
                            'recording'
                        );


                    });


                if (gravando) {


                    event.preventDefault();


                    alert(

                        'Pare a gravação antes de enviar a atividade.'

                    );


                }

            }
        );

    }

});

</script>

@endpush

@endsection
```