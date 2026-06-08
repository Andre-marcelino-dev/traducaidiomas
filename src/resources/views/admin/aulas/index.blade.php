@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Aulas</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Aulas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="shadow"
                        style="border-radius:18px;padding:1.4rem;color:#fff;background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                        <div style="font-size:1.8rem;"><i class="fas fa-chalkboard"></i></div>
                        <div style="font-size:2.4rem;font-weight:900;">{{ $aulas->count() }}</div>
                        <p style="font-size:.7rem;text-transform:uppercase;opacity:.8;margin:.25rem 0 0;">Total de Aulas</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="shadow"
                        style="border-radius:18px;padding:1.4rem;color:#fff;background:linear-gradient(135deg,#059669,#10b981);">
                        <div style="font-size:1.8rem;"><i class="fas fa-circle-check"></i></div>
                        <div style="font-size:2.4rem;font-weight:900;">{{ $aulas->where('status_aulas', 'ATIVO')->count() }}
                        </div>
                        <p style="font-size:.7rem;text-transform:uppercase;opacity:.8;margin:.25rem 0 0;">Ativas</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="shadow"
                        style="border-radius:18px;padding:1.4rem;color:#fff;background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <div style="font-size:1.8rem;"><i class="fas fa-calendar-day"></i></div>
                        <div style="font-size:2.4rem;font-weight:900;">
                            {{ $aulas->where('data_aulas', now()->toDateString())->count() }}</div>
                        <p style="font-size:.7rem;text-transform:uppercase;opacity:.8;margin:.25rem 0 0;">Hoje</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="shadow"
                        style="border-radius:18px;padding:1.4rem;color:#fff;background:linear-gradient(135deg,#8b5cf6,#6d28d9);">
                        <div style="font-size:1.8rem;">
                            <i class="fas fa-book"></i>
                        </div>

                        <div style="font-size:2.4rem;font-weight:900;">
                            {{ $totalCursos }}
                        </div>


                        <p style="font-size:.7rem;text-transform:uppercase;opacity:.8;margin:.25rem 0 0;">
                            Cursos Disponíveis
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="border-radius:16px;border:none;box-shadow:0 2px 12px rgba(0,0,0,.07);">
            <div class="card-header bg-white d-flex justify-content-between align-items-center"
                style="border-radius:16px 16px 0 0;padding:1.2rem 1.5rem;">
                <h5 class="mb-0 fw-bold" style="font-size:.95rem;"><i class="fas fa-list me-2 text-primary"></i>Lista de
                    Aulas</h5>
                <a href="{{ route('admin.aulas.create') }}" class="btn btn-primary btn-sm" style="border-radius:8px;">
                    <i class="fas fa-plus me-1"></i> Nova Aula
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:.88rem;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;">
                                #</th>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;">
                                Título</th>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;">
                                Professor</th>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;">
                                Data / Hora</th>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;">
                                Link Teams</th>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;">
                                Status</th>
                            <th
                                style="padding:.9rem 1rem;font-size:.72rem;text-transform:uppercase;color:#94a3b8;border:none;text-align:center;">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aulas as $aula)
                            <tr style="border-color:#f1f5f9;">
                                <td style="padding:.9rem 1rem;vertical-align:middle;color:#94a3b8;font-weight:600;">
                                    {{ $loop->iteration }}</td>
                                <td style="padding:.9rem 1rem;vertical-align:middle;">
                                    <div style="font-weight:700;color:#1e293b;">{{ $aula->titulo_aulas }}</div>
                                    <div style="font-size:.72rem;color:#94a3b8;">
                                        {{ Str::limit($aula->descricao_aulas, 50) }}</div>
                                </td>
                                <td style="padding:.9rem 1rem;vertical-align:middle;">
                                    {{ $aula->professor->nome_professor ?? '—' }}</td>
                                <td style="padding:.9rem 1rem;vertical-align:middle;">
                                    <div style="font-weight:600;">
                                        {{ \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') }}</div>
                                    <div style="font-size:.75rem;color:#94a3b8;">{{ substr($aula->hora_aulas, 0, 5) }}
                                    </div>
                                </td>
                                <td style="padding:.9rem 1rem;vertical-align:middle;">
                                    @if ($aula->link_teams)
                                        <a href="{{ $aula->link_teams }}" target="_blank" class="btn btn-sm"
                                            style="background:#4f46e5;color:#fff;border-radius:8px;font-size:.75rem;">
                                            <i class="fas fa-video me-1"></i> Entrar
                                        </a>
                                    @else
                                        <span style="color:#94a3b8;font-size:.8rem;">Sem link</span>
                                    @endif
                                </td>
                                <td style="padding:.9rem 1rem;vertical-align:middle;">
                                    @php
                                        $cor = match ($aula->status_aulas) {
                                            'ATIVO' => 'background:#d1fae5;color:#065f46;',
                                            'INATIVO' => 'background:#fef3c7;color:#92400e;',
                                            'CANCELADO' => 'background:#fee2e2;color:#991b1b;',
                                            default => 'background:#f1f5f9;color:#475569;',
                                        };
                                    @endphp
                                    <span
                                        style="{{ $cor }}padding:.3rem .7rem;border-radius:20px;font-size:.72rem;font-weight:600;">
                                        {{ $aula->status_aulas }}
                                    </span>
                                </td>
                                <td style="padding:.9rem 1rem;vertical-align:middle;text-align:center;">
                                    <a href="{{ route('admin.aulas.edit', $aula->id_aulas) }}"
                                        class="btn btn-sm btn-warning me-1" style="border-radius:8px;">
                                        <i class="fas fa-pen fa-xs"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.aulas.destroy', $aula->id_aulas) }}" method="POST"
                                        style="display:inline">
                                        @csrf @method('DELETE')
                                        
                                         <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalExcluir" data-id="{{ $aula->id_aulas }}"
                                            data-nome="{{ $aula->titulo_aulas }}">
                                            <i class="fas fa-trash fa-xs"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-xmark fa-2x mb-2 d-block opacity-25"></i>
                                    Nenhuma aula cadastrada ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>



    <!-- Modal Excluir -->
    <div class="modal fade" id="modalExcluir" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-triangle-exclamation me-2"></i>
                        Confirmar Exclusão
                    </h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body text-center py-4">

                    <i class="fas fa-trash text-danger" style="font-size:3rem;"></i>

                    <p class="mt-3 fs-5">
                        Tem certeza que deseja excluir a aula:
                    </p>

                    <strong id="nomeAulaModal" class="fs-5"></strong>

                    <p class="text-muted mt-2">
                        Esta ação não poderá ser desfeita.
                    </p>

                </div>

                <div class="modal-footer justify-content-center">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <form id="formExcluir" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            Sim, excluir
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const modalExcluir = document.getElementById('modalExcluir');

            modalExcluir.addEventListener('show.bs.modal', function(event) {

                const button = event.relatedTarget;

                const id = button.getAttribute('data-id');
                const nome = button.getAttribute('data-nome');

                document.getElementById('nomeAulaModal').textContent = nome;

                document.getElementById('formExcluir').action =
                    "{{ url('/admin/aulas') }}/" + id;
            });

        });
    </script>
@endsection
