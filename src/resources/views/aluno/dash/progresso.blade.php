@extends('aluno.layout.aluno')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Meu Progresso</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Meu Progresso</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#0d6efd,#0a58ca)">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 small">Total de Aulas</p>
                                <h2 class="fw-bold mb-0">{{ $totalAulas }}</h2>
                            </div>
                            <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#28a745,#1e7e34)">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 small">Presenças</p>
                                <h2 class="fw-bold mb-0">{{ $totalPresente }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#dc3545,#b02a37)">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 small">Faltas</p>
                                <h2 class="fw-bold mb-0">{{ $totalFalta }}</h2>
                            </div>
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card text-white shadow" style="background:linear-gradient(135deg,#fd7e14,#e65c00)">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 small">Materiais Concluídos</p>
                                <h2 class="fw-bold mb-0">{{ $materiaisVistos }}/{{ $totalMateriais }}</h2>
                            </div>
                            <i class="fas fa-book-open fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- PROGRESSO PRESENÇA --}}
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                        📊 Frequência nas Aulas
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="position:relative; width:180px; height:180px;">
                            <canvas id="chartPresenca"></canvas>
                            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center;">
                                <span class="fw-bold fs-3">{{ $percPresenca }}%</span><br>
                                <small class="text-muted">Presença</small>
                            </div>
                        </div>
                        <div class="mt-3 d-flex gap-3">
                            <span><i class="fas fa-circle text-success"></i> Presente ({{ $totalPresente }})</span>
                            <span><i class="fas fa-circle text-danger"></i> Falta ({{ $totalFalta }})</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PROGRESSO MATERIAIS --}}
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                        📚 Progresso nos Materiais
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="position:relative; width:180px; height:180px;">
                            <canvas id="chartMateriais"></canvas>
                            <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center;">
                                <span class="fw-bold fs-3">{{ $percMateriais }}%</span><br>
                                <small class="text-muted">Concluído</small>
                            </div>
                        </div>
                        <div class="mt-3 d-flex gap-3">
                            <span><i class="fas fa-circle text-primary"></i> Concluídos ({{ $materiaisVistos }})</span>
                            <span><i class="fas fa-circle text-secondary"></i> Pendentes ({{ $totalMateriais - $materiaisVistos }})</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ÚLTIMAS PRESENÇAS --}}
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">
                        🕐 Últimas Aulas
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aula</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Justificativa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimasPresencas as $p)
                                <tr>
                                    <td>{{ $p->aula?->titulo_aulas ?? '—' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->data_registro_presenca)->format('d/m/Y') }}</td>
                                    <td>
                                        {{-- A tela de presença grava em minúsculas: presente, falta ou justificado --}}
                                        @php $statusPresenca = strtolower($p->status_presenca); @endphp
                                        @if($statusPresenca === 'presente')
                                            <span class="badge bg-success">✅ Presente</span>
                                        @elseif($statusPresenca === 'justificado')
                                            <span class="badge bg-warning text-dark">📝 Justificado</span>
                                        @else
                                            <span class="badge bg-danger">❌ Falta</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- O botão só aparece para falta sem justificativa em análise --}}
                                        @php $just = $p->ultimaJustificativa; @endphp
                                        @if($p->ehFalta() && $just?->status_justificativa === 'pendente')
                                            <span class="badge bg-info text-dark">⏳ Em análise pelo professor</span>
                                        @elseif($p->ehFalta())
                                            @if($just?->status_justificativa === 'recusada')
                                                <div class="small text-danger mb-1">
                                                    Justificativa recusada{{ $just->resposta_professor ? ': ' . $just->resposta_professor : '.' }}
                                                </div>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-justificar"
                                                data-bs-toggle="modal" data-bs-target="#modalJustificar"
                                                data-action="{{ route('aluno.justificativa.store', $p->id_presenca) }}"
                                                data-aula="{{ $p->aula?->titulo_aulas ?? 'Aula' }} — {{ \Carbon\Carbon::parse($p->data_registro_presenca)->format('d/m/Y') }}">
                                                <i class="fas fa-file-signature me-1"></i>
                                                {{ $just?->status_justificativa === 'recusada' ? 'Justificar novamente' : 'Justificar' }}
                                            </button>
                                        @elseif($just?->status_justificativa === 'aceita')
                                            <span class="small text-success">Aceita pelo professor</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted">Nenhuma aula registrada ainda.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL JUSTIFICAR FALTA --}}
<div class="modal fade" id="modalJustificar" tabindex="-1" aria-labelledby="modalJustificarTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="" class="modal-content" id="formJustificar">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalJustificarTitulo">Justificar falta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2" id="justificarAula"></p>
                <label for="motivo_justificativa" class="form-label">Motivo da falta</label>
                <textarea name="motivo_justificativa" id="motivo_justificativa" class="form-control" rows="4"
                    minlength="10" maxlength="1000" required
                    placeholder="Explique por que você não pôde participar da aula">{{ old('motivo_justificativa') }}</textarea>
                <div class="form-text">O professor vai analisar e pode aceitar ou recusar.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Enviar justificativa</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.btn-justificar').forEach(function (btn) {
    btn.addEventListener('click', function () {
        document.getElementById('formJustificar').action = btn.dataset.action;
        document.getElementById('justificarAula').textContent = btn.dataset.aula;
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chartPresenca'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [{{ $totalPresente }}, {{ $totalFalta }}],
            backgroundColor: ['#28a745', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: { cutout: '75%', plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('chartMateriais'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [{{ $materiaisVistos }}, {{ $totalMateriais - $materiaisVistos }}],
            backgroundColor: ['#0d6efd', '#dee2e6'],
            borderWidth: 0
        }]
    },
    options: { cutout: '75%', plugins: { legend: { display: false } } }
});
</script>
@endsection
