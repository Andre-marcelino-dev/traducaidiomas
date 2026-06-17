@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold">Materiais</h3></div>
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
                <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-folder-open"></i></div>
                        <div class="mc-val">{{ $materiais->total() }}</div>
                        <p class="mc-lbl">Total de Materiais</p>
                        <div class="mc-trend"><i class="fas fa-arrow-trend-up me-1"></i>cadastrados</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-chalkboard-user"></i></div>
                        <div class="mc-val">{{ $materiais->unique('id_professor')->count() }}</div>
                        <p class="mc-lbl">Professores Autores</p>
                        <div class="mc-trend"><i class="fas fa-user me-1"></i>com materiais</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-green shadow">
                        <div class="mc-icon"><i class="fas fa-book"></i></div>
                        <div class="mc-val">{{ $materiais->unique('id_curso')->count() }}</div>
                        <p class="mc-lbl">Cursos Contemplados</p>
                        <div class="mc-trend"><i class="fas fa-graduation-cap me-1"></i>com material</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-file-arrow-up"></i></div>
                        <div class="mc-val">{{ $materiais->whereNotNull('arquivo_materiais')->count() }}</div>
                        <p class="mc-lbl">Com Arquivo</p>
                        <div class="mc-trend"><i class="fas fa-paperclip me-1"></i>com download</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- TABELA --}}
    <div class="row fade-up">
        <div class="col-12">
            <div class="d-card">
                <div class="d-card-header">
                    <h6><i class="fas fa-folder-open text-primary"></i> Lista de Materiais</h6>
                    <a href="{{ route('admin.materiais.create') }}" class="tbl-btn-novo">
                        <i class="fas fa-plus"></i> Novo Material
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
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materiais as $material)
                                <tr>
                                    <td>
                                        <div style="font-weight:600;font-size:.875rem;">{{ $material->titulo_materiais }}</div>
                                        @if($material->descricao_materiais)
                                            <div style="font-size:.72rem;color:#94a3b8;">{{ Str::limit($material->descricao_materiais, 50) }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $material->professor->nome_professor ?? '—' }}</td>
                                    <td><span class="tbl-badge">{{ $material->curso->nome_curso ?? $material->curso_materiais ?? '—' }}</span></td>
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
                                            <a href="{{ route('admin.materiais.download', $material->id_materiais) }}" class="tbl-link-btn">
                                                <i class="fas fa-download"></i> Baixar
                                            </a>
                                        @else
                                            <span style="color:#cbd5e1;font-size:.75rem;">Sem arquivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('admin.materiais.edit', $material->id_materiais) }}" class="tbl-btn-editar">
                                                <i class="fas fa-pen-to-square"></i> Editar
                                            </a>
                                            <form action="{{ route('admin.materiais.destroy', $material->id_materiais) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="tbl-btn-excluir"
                                                    data-titulo="{{ $material->titulo_materiais }}"
                                                    onclick="abrirModalExcluir(this)">
                                                    <i class="fas fa-trash-alt"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="tbl-empty">
                                            <i class="fas fa-folder-open tbl-empty-icon"></i>
                                            <span class="tbl-empty-text">Nenhum material cadastrado ainda.</span>
                                            <a href="{{ route('admin.materiais.create') }}" class="tbl-empty-btn">
                                                <i class="fas fa-plus"></i> Cadastrar Material
                                            </a>
                                        </div>
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

    @include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Material', 'delDescricao' => 'Você está prestes a excluir o material:'])

@endsection
