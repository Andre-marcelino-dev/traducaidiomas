@extends('aluno.layout.aluno')

@section('content')

<style>
    .mc { background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,.05);border:1px solid #e2e8f0; }
    .recent-card { background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.05);border:1px solid #e2e8f0; }
    .recent-card .card-header { background:transparent;border-bottom:1px solid #f1f5f9;padding:16px; }
    .recent-card .card-header h5 { margin:0;font-size:16px;font-weight:700;color:#1e293b; }

    .countdown-box { display:flex;gap:10px;flex-wrap:wrap; }
    .countdown-item { background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 16px;text-align:center;min-width:70px; }
    .countdown-num { font-size:22px;font-weight:700;color:#1e293b; }
    .countdown-lbl { font-size:11px;color:#64748b;text-transform:uppercase;font-weight:600; }

    .aula-item { display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f1f5f9; }
    .aula-item:last-child { border-bottom:none; }
    .aula-icon { width:42px;height:42px;border-radius:10px;background:#eef3ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px; }

    .material-item { display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid #f1f5f9; }
    .material-item:last-child { border-bottom:none; }
    .material-icon { width:42px;height:42px;border-radius:10px;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px; }
</style>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center mt-3 mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color:#1e293b;">Minhas Aulas</h3>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent justify-content-end" style="padding:0;">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}" style="text-decoration:none;">Home</a></li>
                    <li class="breadcrumb-item active">Minhas Aulas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="card recent-card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-video me-2 text-danger"></i>Sua Próxima Aula Ao Vivo</h5>
            </div>
            <div class="card-body p-3">
                @if ($proximaAula)
                    @php
                        $dataHora = \Carbon\Carbon::parse($proximaAula->data_aulas . ' ' . $proximaAula->hora_aulas);
                    @endphp

                    <div class="p-3 mb-3 rounded" style="background-color:#f8fafc;border-left:4px solid #4f46e5;">
                        <h6 class="fw-bold mb-1" style="color:#1e293b;font-size:15px;">
                            {{ $proximaAula->titulo_aulas }}
                        </h6>
                        <p class="text-muted small mb-1">
                            <strong>Professor:</strong> {{ $proximaAula->professor?->nome_professor ?? '—' }}
                        </p>
                        <p class="text-muted small mb-0">
                            <strong>Agendado para:</strong> {{ $dataHora->translatedFormat('d/m/Y \à\s H:i') }}
                        </p>
                    </div>

                    <p class="text-muted small mb-2"><i class="fas fa-hourglass-half me-1"></i> Tempo até o início da aula:</p>
                    <div class="countdown-box mb-3" data-datahora="{{ $dataHora->toIso8601String() }}">
                        <div class="countdown-item">
                            <div class="countdown-num" data-dias>--</div>
                            <div class="countdown-lbl">dias</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-num" data-horas>--</div>
                            <div class="countdown-lbl">horas</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-num" data-minutos>--</div>
                            <div class="countdown-lbl">min</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-num" data-segundos>--</div>
                            <div class="countdown-lbl">seg</div>
                        </div>
                    </div>

                    @if ($proximaAula->link_teams)
                        <p class="text-muted small mb-2">Clique para abrir a sala virtual:</p>
                        <a href="{{ $proximaAula->link_teams }}" target="_blank"
                           class="btn btn-outline-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="fab fa-windows"></i> Entrar na aula
                        </a>
                    @else
                        <div class="alert alert-warning mb-0 py-2 small">
                            <i class="fas fa-circle-info me-1"></i>
                            O professor ainda não disponibilizou o link da aula.
                        </div>
                    @endif

                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-xmark fa-2x mb-2"></i>
                        <p class="mb-0">Nenhuma aula futura agendada.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card recent-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="fas fa-book me-2 text-primary"></i>Todas as Minhas Aulas</h5>
                        <span class="badge bg-primary">{{ $aulas->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($aulas as $aula)
                            <div class="aula-item">
                                <div class="aula-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" style="font-size:.9rem;color:#1e293b;">
                                        {{ $aula->titulo_aulas }}
                                    </div>
                                    <div class="text-muted" style="font-size:.78rem;">
                                        {{ $aula->professor?->nome_professor ?? '—' }} ·
                                        {{ $aula->data_aulas ? \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') : '—' }}
                                        @if ($aula->hora_aulas)
                                            às {{ \Carbon\Carbon::parse($aula->hora_aulas)->format('H:i') }}
                                        @endif
                                    </div>
                                </div>
                                @if ($aula->link_teams)
                                    <a href="{{ $aula->link_teams }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-camera-video"></i>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Nenhuma aula encontrada.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-4">
                <div class="card recent-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="fas fa-file-arrow-down me-2 text-success"></i>Material de Apoio</h5>
                        <span class="badge bg-success">{{ $materiais->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        @forelse ($materiais as $material)
                            <div class="material-item">
                                <div class="material-icon"><i class="fas fa-file-pdf"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" style="font-size:.9rem;color:#1e293b;">
                                        {{ $material->titulo_materiais }}
                                    </div>
                                    <div class="text-muted" style="font-size:.78rem;">
                                        {{ Str::limit($material->descricao_materiais, 60) }}
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('aluno.materiais.visualizar', $material->id_materiais) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('aluno.materiais.download', $material->id_materiais) }}"
                                       class="btn btn-sm btn-outline-success" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Nenhum material disponível.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const box = document.querySelector('.countdown-box');
    if (!box) return;

    const alvo = new Date(box.dataset.datahora).getTime();

    const dias = box.querySelector('[data-dias]');
    const horas = box.querySelector('[data-horas]');
    const minutos = box.querySelector('[data-minutos]');
    const segundos = box.querySelector('[data-segundos]');

    function atualizar() {
        const agora = new Date().getTime();
        let diff = alvo - agora;

        if (diff <= 0) {
            dias.textContent = '0';
            horas.textContent = '0';
            minutos.textContent = '0';
            segundos.textContent = '0';
            clearInterval(timer);
            return;
        }

        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        diff -= d * (1000 * 60 * 60 * 24);

        const h = Math.floor(diff / (1000 * 60 * 60));
        diff -= h * (1000 * 60 * 60);

        const m = Math.floor(diff / (1000 * 60));
        diff -= m * (1000 * 60);

        const s = Math.floor(diff / 1000);

        dias.textContent = d;
        horas.textContent = h;
        minutos.textContent = m;
        segundos.textContent = s;
    }

    atualizar();
    const timer = setInterval(atualizar, 1000);
});
</script>

@endsection
