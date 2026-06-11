@extends('admin.layout.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Materiais</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Materiais</li>
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

            {{-- CARDS DE RESUMO --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-folder-open"></i></div>
                        <div class="mc-val">{{ $materiais->total() }}</div>
                        <p class="mc-lbl">Total de Materiais</p>
                        <div class="mc-trend"><i class="fas fa-arrow-trend-up me-1"></i>cadastrados no sistema</div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-chalkboard-user"></i></div>
                        <div class="mc-val">{{ $materiais->unique('id_professor')->count() }}</div>
                        <p class="mc-lbl">Professores Autores</p>
                        <div class="mc-trend"><i class="fas fa-user me-1"></i>com materiais publicados</div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green shadow">
                        <div class="mc-icon"><i class="fas fa-book"></i></div>
                        <div class="mc-val">{{ $materiais->unique('id_curso')->count() }}</div>
                        <p class="mc-lbl">Cursos Contemplados</p>
                        <div class="mc-trend"><i class="fas fa-graduation-cap me-1"></i>com pelo menos 1 material</div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-file-arrow-up"></i></div>
                        <div class="mc-val">{{ $materiais->whereNotNull('arquivo_materiais')->count() }}</div>
                        <p class="mc-lbl">Com Arquivo</p>
                        <div class="mc-trend"><i class="fas fa-paperclip me-1"></i>materiais com download</div>
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
                    <h5 class="mb-0"><i class="fas fa-folder-open me-2 text-primary"></i>Lista de Materiais</h5>
                    <a href="{{ route('admin.materiais.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        <span class="d-none d-sm-inline" style="font-weight:600; color: white;">Novo Material</span>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Professor</th>
                                <th>Curso</th>
                                <th>Nível</th>
                                <th>Arquivo</th>
                                <th style="text-align:center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materiais as $material)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;font-size:.875rem;">{{ $material->titulo_materiais }}</div>
                                        @if($material->descricao_materiais)
                                            <div style="font-size:.72rem;color:#94a3b8;">
                                                {{ Str::limit($material->descricao_materiais, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $material->professor->nome_professor ?? '—' }}</td>
                                    <td>{{ $material->curso->nome_curso ?? $material->curso_materiais ?? '—' }}</td>
                                    <td>
                                        @php
                                            $nivel = strtolower($material->nivel_material ?? '');
                                            $config = match(true) {
                                                in_array($nivel, ['basico','básico'])               => ['pct' => 33,  'cor' => '#ef4444', 'label' => 'Básico'],
                                                in_array($nivel, ['intermediario','intermediário']) => ['pct' => 66,  'cor' => '#f59e0b', 'label' => 'Intermediário'],
                                                in_array($nivel, ['avancado','avançado'])           => ['pct' => 100, 'cor' => '#22c55e', 'label' => 'Avançado'],
                                                default                                             => ['pct' => 0,   'cor' => '#94a3b8', 'label' => '—'],
                                            };
                                        @endphp
                                        <div style="min-width:100px;">
                                            <div style="font-size:.72rem;color:#64748b;margin-bottom:3px;">{{ $config['label'] }}</div>
                                            <div style="background:#e2e8f0;border-radius:99px;height:6px;">
                                                <div style="width:{{ $config['pct'] }}%;background:{{ $config['cor'] }};height:6px;border-radius:99px;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($material->arquivo_materiais)
                                            {{-- Mudado para chamar a rota direta de download contornando o erro de link simbólico do Nginx --}}
                                            <a href="{{ route('admin.materiais.download', $material->id_materiais) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download fa-xs"></i> Baixar
                                            </a>
                                        @else
                                            <span class="text-muted" style="font-size:.75rem;">Sem arquivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.materiais.edit', $material->id_materiais) }}"
                                           class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-pen fa-xs"></i> Editar
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal" data-bs-target="#modalExcluir"
                                            data-id="{{ $material->id_materiais }}"
                                            data-titulo="{{ $material->titulo_materiais }}">
                                            <i class="fas fa-trash fa-xs"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                        Nenhum material cadastrado ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($materiais->hasPages())
                    <div class="card-footer">
                        {{ $materiais->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Excluir --}}
    <div class="modal fade" id="modalExcluir" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-file-earmark-x-fill text-danger" style="font-size:3rem;"></i>
                    <p class="mt-3 fs-5">Tem certeza que deseja excluir o material</p>
                    <strong id="tituloMaterialModal" class="fs-5"></strong>
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
                const id     = button.getAttribute('data-id');
                const titulo = button.getAttribute('data-titulo');

                document.getElementById('tituloMaterialModal').textContent = titulo;
                document.getElementById('formExcluir').action = `/admin/materiais/${id}`;
            });
        </script>
    @endpush
@endsection