@extends('admin.layout.admin')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">{{ $atividade->titulo_atividade }}</h3></div>
            <div class="col-sm-6">
                <a href="{{ route('admin.atividades.index') }}" class="btn btn-outline-secondary float-end">Voltar</a>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        {{-- INFO --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">📋 Detalhes</div>
            <div class="card-body">
                <p><strong>Curso:</strong> {{ $atividade->curso?->nome_curso }}</p>
                <p><strong>Entrega:</strong> {{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}</p>
                <p><strong>Descrição:</strong> {{ $atividade->descricao_atividade }}</p>
            </div>
        </div>

        {{-- QUESTÕES --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">❓ Questões</div>
            <div class="card-body">
                @foreach($atividade->questoes as $i => $questao)
                <div class="mb-3 p-3 border rounded">
                    <p><strong>{{ $i+1 }}. {{ $questao->enunciado }}</strong></p>
                    @if($questao->tipo_questao === 'multipla_escolha')
                        <p>A) {{ $questao->opcao_a }} &nbsp; B) {{ $questao->opcao_b }} &nbsp; C) {{ $questao->opcao_c }} &nbsp; D) {{ $questao->opcao_d }}</p>
                        <p class="text-success"><strong>Resposta correta: {{ $questao->resposta_correta }}</strong></p>
                    @else
                        <p class="text-muted">Questão dissertativa</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- RESPOSTAS --}}
        <div class="card shadow-sm">
            <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">📬 Respostas dos Alunos</div>
            <div class="card-body p-0">
                @forelse($atividade->respostas as $resposta)
                <div class="p-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold">{{ $resposta->aluno?->nome_aluno }}</h6>
                        <div class="d-flex gap-2 align-items-center">
                            @if($resposta->status_resposta === 'CORRIGIDA')
                                <span class="badge bg-success">✅ Corrigida — Nota: {{ $resposta->nota }}</span>
                            @elseif($resposta->status_resposta === 'ENVIADA')
                                <span class="badge bg-warning">⏳ Aguardando correção</span>
                            @else
                                <span class="badge bg-secondary">Pendente</span>
                            @endif
                        </div>
                    </div>

                    @foreach($resposta->respostasQuestoes as $rq)
                    <div class="mb-2 p-2 bg-light rounded">
                        <small class="text-muted">{{ $rq->questao?->enunciado }}</small><br>
                        <strong>Resposta: </strong> {{ $rq->resposta_aluno ?? '—' }}
                        @if($rq->correta !== null)
                            {!! $rq->correta ? '<span class="badge bg-success ms-2">✅ Correta</span>' : '<span class="badge bg-danger ms-2">❌ Errada</span>' !!}
                        @endif
                    </div>
                    @endforeach

                    @if($resposta->status_resposta === 'ENVIADA')
                    <form action="{{ route('admin.atividades.corrigir', $resposta->id_resposta) }}" method="POST" class="mt-3">
                        @csrf @method('PUT')
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label">Nota (0-10)</label>
                                <input type="number" name="nota" class="form-control" min="0" max="10" step="0.5" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Feedback</label>
                                <textarea name="feedback_professor" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">Corrigir</button>
                            </div>
                        </div>
                    </form>
                    @elseif($resposta->feedback_professor)
                    <div class="alert alert-info mt-2 mb-0">
                        <strong>Feedback:</strong> {{ $resposta->feedback_professor }}
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-center text-muted py-4">Nenhuma resposta recebida ainda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
