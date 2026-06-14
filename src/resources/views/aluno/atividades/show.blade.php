@extends('aluno.layout.aluno')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">{{ $atividade->titulo_atividade }}</h3></div>
            <div class="col-sm-6">
                <a href="{{ route('aluno.atividades.index') }}" class="btn btn-outline-secondary float-end">Voltar</a>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">

        @if($atividade->descricao_atividade)
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-1"></i> {{ $atividade->descricao_atividade }}
        </div>
        @endif

        @if($resposta && in_array($resposta->status_resposta, ['ENVIADA', 'CORRIGIDA']))
            {{-- JÁ RESPONDEU --}}
            @if($resposta->status_resposta === 'CORRIGIDA')
            <div class="alert alert-success mb-4">
                <h5>✅ Atividade Corrigida</h5>
                <p><strong>Nota: {{ $resposta->nota }}/10</strong></p>
                <p><strong>Feedback do Professor:</strong> {{ $resposta->feedback_professor }}</p>
            </div>
            @else
            <div class="alert alert-warning mb-4">⏳ Atividade enviada! Aguardando correção do professor.</div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">Suas Respostas</div>
                <div class="card-body">
                    @foreach($atividade->questoes as $i => $questao)
                    @php $rq = $resposta->respostasQuestoes->firstWhere('id_questao', $questao->id_questao); @endphp
                    <div class="mb-4 p-3 border rounded">
                        <p><strong>{{ $i+1 }}. {{ $questao->enunciado }}</strong></p>
                        @if($questao->tipo_questao === 'multipla_escolha')
                            <p>A) {{ $questao->opcao_a }} &nbsp; B) {{ $questao->opcao_b }} &nbsp; C) {{ $questao->opcao_c }} &nbsp; D) {{ $questao->opcao_d }}</p>
                            <p>Sua resposta: <strong>{{ $rq?->resposta_aluno ?? '—' }}</strong>
                                @if($rq?->correta !== null)
                                    {!! $rq->correta ? '<span class="badge bg-success">✅ Correta</span>' : '<span class="badge bg-danger">❌ Errada</span>' !!}
                                @endif
                            </p>
                        @else
                            <p class="text-muted">{{ $rq?->resposta_aluno ?? '—' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            {{-- FORMULÁRIO --}}
            <form action="{{ route('aluno.atividades.responder', $atividade->id_atividade) }}" method="POST">
                @csrf
                @foreach($atividade->questoes as $i => $questao)
                <div class="card shadow-sm mb-3">
                    <div class="card-header" style="background:#1a1a2e; color:#fff;">
                        Questão {{ $i+1 }}
                        @if($questao->tipo_questao === 'multipla_escolha')
                            <span class="badge bg-info ms-2">Múltipla Escolha</span>
                        @else
                            <span class="badge bg-secondary ms-2">Dissertativa</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <p class="fw-bold">{{ $questao->enunciado }}</p>
                        @if($questao->tipo_questao === 'multipla_escolha')
                            <div class="d-flex flex-column gap-2">
                                @foreach(['A' => $questao->opcao_a, 'B' => $questao->opcao_b, 'C' => $questao->opcao_c, 'D' => $questao->opcao_d] as $letra => $opcao)
                                @if($opcao)
                                <label class="d-flex align-items-center gap-2 p-2 border rounded" style="cursor:pointer;">
                                    <input type="radio" name="questao_{{ $questao->id_questao }}" value="{{ $letra }}" required>
                                    <strong>{{ $letra }})</strong> {{ $opcao }}
                                </label>
                                @endif
                                @endforeach
                            </div>
                        @else
                            <textarea name="questao_{{ $questao->id_questao }}" class="form-control" rows="4" placeholder="Digite sua resposta..." required></textarea>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="d-flex gap-2 mb-5">
                    <button type="submit" class="btn btn-success px-5" onclick="return confirm('Confirmar envio da atividade?')">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Atividade
                    </button>
                    <a href="{{ route('aluno.atividades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
