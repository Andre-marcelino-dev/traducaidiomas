@extends('admin.layout.admin')

@section('content')

<style>
    .fade-in,
    .fade-up {
        opacity: 0;
        will-change: opacity, transform;
    }

    .fade-in {
        transform: translate3d(0, 0, 0) scale(.985);
        transition: opacity .55s ease, transform .55s ease;
    }

    .fade-up {
        transform: translate3d(0, 42px, 0);
        transition: opacity .8s cubic-bezier(.22, 1, .36, 1), transform .8s cubic-bezier(.22, 1, .36, 1);
    }

    .fade-in.is-visible,
    .fade-up.is-visible {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
    }

    .delay-1 {
        transition-delay: .12s;
    }

    .delay-2 {
        transition-delay: .24s;
    }

    .delay-3 {
        transition-delay: .36s;
    }

    .atividade-show-page {
        padding-bottom: 34px;
    }

    .atividade-show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }

    .atividade-show-header h3 {
        font-size: 1.55rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .atividade-show-header p {
        color: #64748b;
        margin: 4px 0 0;
        font-size: .92rem;
    }

    .atividade-card {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .08);
        background: #fff;
    }

    .atividade-card-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-bottom: 1px solid #e5edf8;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .atividade-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 15px;
        background: #163d8f;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 10px 22px rgba(22, 61, 143, .18);
    }

    .atividade-card-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #1e293b;
    }

    .atividade-card-header span {
        display: block;
        margin-top: 2px;
        font-size: .8rem;
        color: #64748b;
    }

    .atividade-card-body {
        padding: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
    }

    .info-label {
        font-size: .74rem;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 4px;
    }

    .info-value {
        font-size: .92rem;
        color: #1e293b;
        font-weight: 800;
    }

    .descricao-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        color: #475569;
        line-height: 1.6;
    }

    .questao-box {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        background: #fff;
        margin-bottom: 14px;
    }

    .questao-head {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
    }

    .questao-numero {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        background: #10b981;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        flex-shrink: 0;
    }

    .questao-enunciado {
        font-weight: 800;
        color: #1e293b;
        line-height: 1.5;
    }

    .alternativas-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .alternativa-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 10px 12px;
        color: #334155;
        font-size: .88rem;
    }

    .alternativa-letra {
        font-weight: 900;
        color: #163d8f;
        margin-right: 6px;
    }

    .resposta-correta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 7px 12px;
        background: #ecfdf5;
        color: #047857;
        border-radius: 999px;
        font-weight: 800;
        font-size: .8rem;
    }

    .tipo-dissertativa {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        font-weight: 800;
        font-size: .8rem;
    }

    .resposta-aluno-card {
        padding: 22px 24px;
        border-bottom: 1px solid #edf2f7;
    }

    .resposta-aluno-card:last-child {
        border-bottom: 0;
    }

    .resposta-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }

    .aluno-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .aluno-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #eef3ff;
        color: #163d8f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        font-weight: 900;
        flex-shrink: 0;
    }

    .aluno-nome {
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .badge-status {
        border-radius: 999px;
        padding: 8px 12px;
        font-weight: 800;
        font-size: .78rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-corrigida {
        background: #ecfdf5;
        color: #047857;
    }

    .badge-enviada {
        background: #fffbeb;
        color: #b45309;
    }

    .badge-pendente {
        background: #f1f5f9;
        color: #64748b;
    }

    .resposta-questao {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 10px;
    }

    .resposta-questao small {
        display: block;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .resposta-texto {
        color: #1e293b;
        font-size: .9rem;
    }

    .badge-correta,
    .badge-errada {
        border-radius: 999px;
        padding: 5px 9px;
        font-size: .72rem;
        font-weight: 900;
        margin-left: 6px;
    }

    .badge-correta {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-errada {
        background: #fee2e2;
        color: #dc2626;
    }

    .correcao-box {
        margin-top: 16px;
        padding: 16px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
    }

    .form-label {
        font-size: .86rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 13px;
        border: 1px solid #dbe3ef;
        min-height: 44px;
        font-size: .92rem;
    }

    .form-control:focus {
        border-color: #163d8f;
        box-shadow: 0 0 0 .18rem rgba(22, 61, 143, .12);
    }

    .btn-voltar-atividade {
        border-radius: 12px;
        padding: 10px 18px;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 10px 22px rgba(239, 68, 68, .12);
        transition: .22s ease;
    }

    .btn-voltar-atividade:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(239, 68, 68, .22);
    }

    .btn-corrigir {
        border-radius: 12px;
        padding: 10px 18px;
        background: #10b981;
        color: #fff;
        border: 0;
        font-weight: 800;
        width: 100%;
    }

    .btn-corrigir:hover {
        background: #059669;
        color: #fff;
    }

    .feedback-box {
        margin-top: 12px;
        padding: 14px 16px;
        border-radius: 16px;
        background: #eef6ff;
        color: #1e3a8a;
        border: 1px solid #dbeafe;
        font-size: .9rem;
    }

    .empty-box {
        padding: 38px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-box strong {
        display: block;
        color: #64748b;
        margin-bottom: 4px;
    }

    .alert {
        border: 0;
        border-radius: 15px;
    }

    @media (max-width: 768px) {
        .atividade-show-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-voltar-atividade {
            width: 100%;
            text-align: center;
        }

        .info-grid,
        .alternativas-grid {
            grid-template-columns: 1fr;
        }

        .resposta-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .atividade-card-body,
        .resposta-aluno-card {
            padding: 18px;
        }
    }
</style>

<div class="atividade-show-page fade-in">

    <div class="app-content-header fade-up">
        <div class="container-fluid">
            <div class="atividade-show-header">
                <div>
                    <h3>{{ $atividade->titulo_atividade }}</h3>
                    <p>Detalhes da atividade, questões e respostas enviadas pelos alunos.</p>
                </div>

                <a href="{{ route('admin.atividades.index') }}" class="btn-voltar-atividade">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 fade-up delay-1">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- INFO --}}
            <div class="card atividade-card mb-4 fade-up delay-1">
                <div class="atividade-card-header">
                    <div class="atividade-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                            viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                            stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <path d="M14 2v6h6"></path>
                            <path d="M8 13h8"></path>
                            <path d="M8 17h5"></path>
                        </svg>
                    </div>

                    <div>
                        <h5>Detalhes</h5>
                        <span>Informações gerais da atividade</span>
                    </div>
                </div>

                <div class="atividade-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Curso</div>
                            <div class="info-value">{{ $atividade->curso?->nome_curso ?? '—' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Entrega</div>
                            <div class="info-value">
                                {{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Questões</div>
                            <div class="info-value">{{ $atividade->questoes->count() }}</div>
                        </div>
                    </div>

                    <div class="descricao-box">
                        <strong>Descrição:</strong>
                        {{ $atividade->descricao_atividade ?: 'Nenhuma descrição cadastrada.' }}
                    </div>
                </div>
            </div>

            {{-- QUESTÕES --}}
            <div class="card atividade-card mb-4 fade-up delay-2">
                <div class="atividade-card-header">
                    <div class="atividade-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                            viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                            stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9.09 9a3 3 0 1 1 5.82 1c0 2-3 2-3 4"></path>
                            <path d="M12 17h.01"></path>
                            <circle cx="12" cy="12" r="10"></circle>
                        </svg>
                    </div>

                    <div>
                        <h5>Questões</h5>
                        <span>Enunciados e respostas corretas</span>
                    </div>
                </div>

                <div class="atividade-card-body">
                    @forelse($atividade->questoes as $i => $questao)
                        <div class="questao-box">
                            <div class="questao-head">
                                <div class="questao-numero">{{ $i + 1 }}</div>

                                <div>
                                    <div class="questao-enunciado">
                                        {{ $questao->enunciado }}
                                    </div>

                                    @if($questao->tipo_questao === 'multipla_escolha')
                                        <div class="alternativas-grid">
                                            <div class="alternativa-item">
                                                <span class="alternativa-letra">A)</span>{{ $questao->opcao_a }}
                                            </div>

                                            <div class="alternativa-item">
                                                <span class="alternativa-letra">B)</span>{{ $questao->opcao_b }}
                                            </div>

                                            <div class="alternativa-item">
                                                <span class="alternativa-letra">C)</span>{{ $questao->opcao_c }}
                                            </div>

                                            <div class="alternativa-item">
                                                <span class="alternativa-letra">D)</span>{{ $questao->opcao_d }}
                                            </div>
                                        </div>

                                        <div class="resposta-correta">
                                            Resposta correta: {{ $questao->resposta_correta }}
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <span class="tipo-dissertativa">
                                                Questão dissertativa
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-box">
                            <strong>Nenhuma questão cadastrada.</strong>
                            Esta atividade ainda não possui questões.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RESPOSTAS --}}
            <div class="card atividade-card fade-up delay-3">
                <div class="atividade-card-header">
                    <div class="atividade-card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                            viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                            stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-6l-2 3h-4l-2-3H2"></path>
                            <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"></path>
                        </svg>
                    </div>

                    <div>
                        <h5>Respostas dos Alunos</h5>
                        <span>Envios, correções, notas e feedbacks</span>
                    </div>
                </div>

                <div>
                    @forelse($atividade->respostas as $resposta)
                        @php
                            $nomeAluno = $resposta->aluno?->nome_aluno ?? 'Aluno';
                            $iniciais = strtoupper(mb_substr($nomeAluno, 0, 2));
                        @endphp

                        <div class="resposta-aluno-card">
                            <div class="resposta-header">
                                <div class="aluno-info">
                                    <div class="aluno-avatar">{{ $iniciais }}</div>

                                    <div>
                                        <h6 class="aluno-nome">{{ $nomeAluno }}</h6>
                                    </div>
                                </div>

                                <div>
                                    @if($resposta->status_resposta === 'CORRIGIDA')
                                        <span class="badge-status badge-corrigida">
                                            Corrigida — Nota: {{ $resposta->nota }}
                                        </span>
                                    @elseif($resposta->status_resposta === 'ENVIADA')
                                        <span class="badge-status badge-enviada">
                                            Aguardando correção
                                        </span>
                                    @else
                                        <span class="badge-status badge-pendente">
                                            Pendente
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @foreach($resposta->respostasQuestoes as $rq)
                                <div class="resposta-questao">
                                    <small>{{ $rq->questao?->enunciado }}</small>

                                    <div class="resposta-texto">
                                        <strong>Resposta:</strong> {{ $rq->resposta_aluno ?? '—' }}

                                        @if($rq->correta !== null)
                                            @if($rq->correta)
                                                <span class="badge-correta">Correta</span>
                                            @else
                                                <span class="badge-errada">Errada</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($resposta->status_resposta === 'ENVIADA')
                                <form action="{{ route('admin.atividades.corrigir', $resposta->id_resposta) }}" method="POST" class="correcao-box">
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label">Nota (0-10)</label>

                                            <input
                                                type="number"
                                                name="nota"
                                                class="form-control"
                                                min="0"
                                                max="10"
                                                step="0.5"
                                                required
                                            >
                                        </div>

                                        <div class="col-md-8">
                                            <label class="form-label">Feedback</label>

                                            <textarea
                                                name="feedback_professor"
                                                class="form-control"
                                                rows="2"
                                                placeholder="Digite um feedback para o aluno..."
                                                required
                                            ></textarea>
                                        </div>

                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn-corrigir">
                                                Corrigir
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @elseif($resposta->feedback_professor)
                                <div class="feedback-box">
                                    <strong>Feedback:</strong> {{ $resposta->feedback_professor }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-box">
                            <strong>Nenhuma resposta recebida ainda.</strong>
                            As respostas dos alunos aparecerão aqui quando forem enviadas.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    requestAnimationFrame(function() {
        document.querySelectorAll('.fade-in, .fade-up').forEach(function(element) {
            element.classList.add('is-visible');
        });
    });
});
</script>

@endsection