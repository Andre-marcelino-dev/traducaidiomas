@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Professores</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Professores</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-chalkboard-user"></i></div>
                        <div class="mc-val">{{ $professores->count() }}</div>
                        <p class="mc-lbl">Total de Professores</p>
                        <div class="mc-trend"><i class="fas fa-arrow-trend-up me-1"></i>cadastrados no sistema</div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mc-val">{{ $totalCursos }}</div>
                        <p class="mc-lbl">Cursos Oferecidos</p>
                        <div class="mc-trend"><i class="fas fa-language me-1"></i>idiomas no catálogo</div>
                    </div>
                </div>



                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-clock"></i></div>
                        <div class="mc-val">{{ number_format($mediaExperiencia, 1) }}</div>
                        <p class="mc-lbl">Média de Experiência</p>
                        <div class="mc-trend"><i class="fas fa-calendar me-1"></i>anos em média</div>
                    </div>
                </div>


                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="mc-val">{{ $totalAlunos }}</div>
                        <p class="mc-lbl">Total de Alunos</p>
                        <div class="mc-trend">
                            <i class="fas fa-circle me-1" style="color:#fde68a; font-size:.6rem;"></i>{{ $iniciantes }}
                            Inic. &nbsp;
                            <i class="fas fa-circle me-1" style="color:#a7f3d0; font-size:.6rem;"></i>{{ $intermediarios }}
                            Inter. &nbsp;
                            <i class="fas fa-circle me-1" style="color:#bfdbfe; font-size:.6rem;"></i>{{ $avancados }}
                            Avanç.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="row fade-up">
        <div class="col-12">
            <div class="card recent-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-user me-2 text-primary"></i>Lista de Professores
                    </h5>
                    <a href="{{ route('admin.professores.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> <span class="d-none d-sm-inline"
                            style="font-weight:600; color: white;">Novo Professor</span>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>

                                <th>Professor</th>
                                <th>Especialidade</th>
                                <th>Nível</th>
                                <th>Experiência</th>
                                <th style="text-align:center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($professores as $professor)
                                <tr>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if (!empty($professor->foto_professor))
                                                <img src="{{ asset('traducaidiomas/professor/' . $professor->foto_professor) }}?v={{ time() }}"
                                                    class="prof-avatar" alt="{{ $professor->nome_professor }}">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($professor->nome_professor) }}&background=0D6EFD&color=fff&size=50"
                                                    class="prof-avatar" alt="{{ $professor->nome_professor }}">
                                            @endif
                                            <div>
                                                <div style="font-weight:600;font-size:.875rem;">
                                                    {{ $professor->nome_professor }}</div>
                                                <div style="font-size:.72rem;color:#94a3b8;">
                                                    {{ $professor->email_professor }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $professor->especialidade_professor ?? '—' }}</td>
                                    <td>
                                        @php
                                            $nivel = strtolower($professor->nivel_professor ?? '');
                                            $config = match (true) {
                                                in_array($nivel, ['basico', 'básico']) => [
                                                    'pct' => 33,
                                                    'cor' => '#ef4444',
                                                    'label' => 'Básico',
                                                ],
                                                in_array($nivel, ['intermediario', 'intermediário']) => [
                                                    'pct' => 66,
                                                    'cor' => '#f59e0b',
                                                    'label' => 'Intermediário',
                                                ],
                                                in_array($nivel, ['avancado', 'avançado']) => [
                                                    'pct' => 100,
                                                    'cor' => '#22c55e',
                                                    'label' => 'Avançado',
                                                ],
                                                default => ['pct' => 0, 'cor' => '#94a3b8', 'label' => '—'],
                                            };
                                        @endphp
                                        <div style="min-width: 100px;">
                                            <div style="font-size:.72rem; color:#64748b; margin-bottom:3px;">
                                                {{ $config['label'] }}</div>
                                            <div style="background:#e2e8f0; border-radius:99px; height:6px;">
                                                <div
                                                    style="width:{{ $config['pct'] }}%; background:{{ $config['cor'] }}; height:6px; border-radius:99px;">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- DEPOIS --}}
                                    <td>{{ $professor->experiencia_professor }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.professores.edit', $professor->id_professor) }}"
                                            class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-pen fa-xs"></i> Editar
                                        </a>
                                        {{-- BOTÃO QUE ABRE A MODAL --}}
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalExcluir" data-id="{{ $professor->id_professor }}"
                                            data-nome="{{ $professor->nome_professor }}">
                                            <i class="fas fa-trash fa-xs"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                        Nenhum professor cadastrado ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>

    <!-- Modal Excluir -->
    <div class="modal fade" id="modalExcluir" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-person-x-fill text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3 fs-5">Tem certeza que deseja excluir o professor</p>
                    <strong id="nomeAlunoModal" class="fs-5"></strong>
                    <p class="text-muted mt-2">Esta ação não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formExcluir" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sim, excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('modalExcluir').addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nome = button.getAttribute('data-nome');

                document.getElementById('nomeAlunoModal').textContent = nome;
                document.getElementById('formExcluir').action = `/admin/professores/${id}`;
            });
        </script>
    @endpush
@endsection
