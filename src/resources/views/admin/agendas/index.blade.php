@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Agendamentos</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Agendamentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-blue shadow">
                    <div class="mc-val">{{ $totalAgendamentos ?? 0 }}</div>
                    <p>Total</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-amber shadow">
                    <div class="mc-val">{{ $agendamentosPendentes ?? 0 }}</div>
                    <p>Pendentes</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-rose shadow">
                    <div class="mc-val">{{ $reagendamentos ?? 0 }}</div>
                    <p>Reagendamentos</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="mc mc-blue shadow">
                    <div class="mc-val">{{ $agendamentosHoje ?? 0 }}</div>
                    <p>Hoje</p>
                </div>
            </div>
        </div>

        {{-- CALENDÁRIO --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Calendário de Agendamentos</h5>

                <a href="{{ route('admin.agendas.create') }}" class="btn btn-success btn-sm">
                    Novo
                </a>
            </div>

            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        {{-- TABELA --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Lista de Agendamentos</h5>

          
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Professor</th>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($agendas as $agenda)
                            <tr>
                                <td>
                                    <span class="agenda-aluno-link"
                                        data-foto="{{ $agenda->aluno?->foto_aluno }}"
                                        data-nome="{{ $agenda->aluno?->nome_aluno ?? '—' }}"
                                        data-curso="{{ $agenda->aluno?->curso_aluno ?? '—' }}"
                                        data-nivel="{{ $agenda->aluno?->nivel_aluno ?? '—' }}"
                                        data-professor="{{ $agenda->professor?->nome_professor ?? '—' }}"
                                        data-titulo="{{ $agenda->titulo_agenda }}"
                                        data-data="{{ \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y') }}"
                                        data-inicio="{{ \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i') }}"
                                        data-fim="{{ \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i') }}"
                                        data-status="{{ $agenda->status_agenda ?? '—' }}"
                                        data-edit="{{ route('admin.agendas.edit', $agenda->id_agenda) }}"
                                        style="cursor:pointer;font-weight:600;color:#1e293b;border-bottom:1px dashed #94a3b8;">
                                        {{ $agenda->aluno?->nome_aluno ?? '—' }}
                                    </span>
                                </td>

                                <td>{{ $agenda->professor?->nome_professor ?? '—' }}</td>

                                <td>{{ $agenda->titulo_agenda }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i') }}
                                </td>

                                <td>
                                    @php
                                        $status = strtolower($agenda->status_agenda ?? '');

                                        $config = match (true) {
                                            str_contains($status, 'confirmado') => ['#22c55e', 'Confirmado'],
                                            str_contains($status, 'pendente') => ['#f59e0b', 'Pendente'],
                                            str_contains($status, 'cancelado') => ['#ef4444', 'Cancelado'],
                                            str_contains($status, 'reagend') => ['#6366f1', 'Reagendamento'],
                                            default => ['#94a3b8', $agenda->status_agenda ?? '—'],
                                        };
                                    @endphp

                                    <span style="background:{{ $config[0] }}20;color:{{ $config[0] }};padding:4px 10px;border-radius:20px;">
                                        {{ $config[1] }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('admin.agendas.edit', $agenda->id_agenda) }}"
                                       class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalExcluir"
                                        data-id="{{ $agenda->id_agenda }}"
                                        data-titulo="{{ $agenda->titulo_agenda }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhum agendamento</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETALHES ALUNO --}}
<div class="modal fade" id="modalDetalheAluno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:20px;overflow:hidden;">

            {{-- Cabeçalho com foto --}}
            <div id="modal-header-bg" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);padding:2rem;text-align:center;position:relative;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="position:absolute;top:1rem;right:1rem;"></button>
                <img id="modal-foto" src="" alt=""
                    style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);margin-bottom:.75rem;box-shadow:0 4px 20px rgba(0,0,0,.3);">
                <h5 id="modal-nome" style="color:#fff;font-weight:700;margin:0;font-size:1.1rem;"></h5>
                <div id="modal-curso" style="color:rgba(255,255,255,.6);font-size:.78rem;margin-top:.25rem;"></div>
                <div style="margin-top:.75rem;">
                    <span id="modal-status-badge" style="font-size:.7rem;font-weight:700;padding:.3rem .85rem;border-radius:50px;letter-spacing:.05em;"></span>
                </div>
            </div>

            {{-- Corpo com detalhes --}}
            <div class="modal-body p-0">
                <div style="padding:1.5rem;">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1rem;">
                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">Data</div>
                            <div id="modal-data" style="font-size:.95rem;font-weight:700;color:#1e293b;"></div>
                        </div>
                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">Horário</div>
                            <div style="font-size:.95rem;font-weight:700;color:#1e293b;">
                                <span id="modal-inicio"></span> <span style="color:#94a3b8;">→</span> <span id="modal-fim"></span>
                            </div>
                        </div>
                    </div>

                    <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;margin-bottom:.75rem;">
                        <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">Aula</div>
                        <div id="modal-titulo" style="font-size:.9rem;font-weight:600;color:#1e293b;"></div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">Professor</div>
                            <div id="modal-professor" style="font-size:.85rem;font-weight:600;color:#1e293b;"></div>
                        </div>
                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">Nível</div>
                            <div id="modal-nivel" style="font-size:.85rem;font-weight:600;color:#1e293b;"></div>
                        </div>
                    </div>

                </div>

                <div style="padding:0 1.5rem 1.5rem;display:flex;gap:.6rem;">
                    <a id="modal-edit-btn" href="#" class="btn btn-sm" style="flex:1;background:linear-gradient(135deg,#1a1a2e,#0f3460);color:#fff;border:none;border-radius:10px;padding:.6rem;">
                        <i class="fas fa-pen me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" style="border-radius:10px;padding:.6rem 1rem;">Fechar</button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL EXCLUIR --}}
<div class="modal fade" id="modalExcluir">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5>Excluir</h5>
            </div>

            <div class="modal-body text-center">
                <p>Excluir agendamento:</p>
                <strong id="tituloAgendaModal"></strong>
            </div>

            <div class="modal-footer">
                <form id="formExcluir" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Sim</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/pt-br.global.min.js"></script>

<script>
document.getElementById('modalExcluir').addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const titulo = button.getAttribute('data-titulo');

    document.getElementById('tituloAgendaModal').textContent = titulo;
    document.getElementById('formExcluir').action = `/admin/agendas/${id}`;
});

// ── Abre o modal de detalhes do aluno ──────────────────
function abrirModalAluno(d) {
    const fotoBase = '{{ asset("traducaidiomas/alunos/") }}/';
    const fotoDefault = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(d.nome) + '&background=1a1a2e&color=fff&size=90';

    document.getElementById('modal-foto').src      = d.foto ? fotoBase + d.foto : fotoDefault;
    document.getElementById('modal-nome').textContent      = d.nome;
    document.getElementById('modal-curso').textContent     = d.curso + (d.nivel ? ' · ' + d.nivel : '');
    document.getElementById('modal-titulo').textContent    = d.titulo;
    document.getElementById('modal-data').textContent      = d.data;
    document.getElementById('modal-inicio').textContent    = d.inicio;
    document.getElementById('modal-fim').textContent       = d.fim;
    document.getElementById('modal-professor').textContent = d.professor;
    document.getElementById('modal-nivel').textContent     = d.nivel || '—';
    document.getElementById('modal-edit-btn').href         = d.editUrl || '#';

    // Badge de status
    const statusMap = {
        confirmado:  { cor: '#22c55e', bg: '#f0fdf4', label: 'Confirmado' },
        pendente:    { cor: '#f59e0b', bg: '#fffbeb', label: 'Pendente' },
        cancelado:   { cor: '#ef4444', bg: '#fff1f2', label: 'Cancelado' },
        reagendado:  { cor: '#6366f1', bg: '#eef3ff', label: 'Reagendamento' },
    };
    const st = (d.status || '').toLowerCase();
    const cfg = statusMap[st] || { cor: '#94a3b8', bg: '#f8fafc', label: d.status || '—' };
    const badge = document.getElementById('modal-status-badge');
    badge.textContent = cfg.label;
    badge.style.background = cfg.bg;
    badge.style.color = cfg.cor;
    badge.style.border = '1.5px solid ' + cfg.cor + '40';

    new bootstrap.Modal(document.getElementById('modalDetalheAluno')).show();
}

// ── Clique no nome do aluno na tabela ──────────────────
document.querySelectorAll('.agenda-aluno-link').forEach(function(el) {
    el.addEventListener('click', function() {
        abrirModalAluno({
            foto:      this.dataset.foto,
            nome:      this.dataset.nome,
            curso:     this.dataset.curso,
            nivel:     this.dataset.nivel,
            professor: this.dataset.professor,
            titulo:    this.dataset.titulo,
            data:      this.dataset.data,
            inicio:    this.dataset.inicio,
            fim:       this.dataset.fim,
            status:    this.dataset.status,
            editUrl:   this.dataset.edit,
        });
    });
});

// ── FullCalendar ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: "{{ route('admin.agendas.eventos') }}",
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            const p = info.event.extendedProps;
            abrirModalAluno({
                foto:      p.foto,
                nome:      p.aluno,
                curso:     p.curso,
                nivel:     p.nivel,
                professor: p.professor,
                titulo:    p.titulo,
                data:      p.data,
                inicio:    p.inicio,
                fim:       p.fim,
                status:    p.status,
                editUrl:   p.editUrl,
            });
        },
    });

    calendar.render();
});
</script>

@endsection
