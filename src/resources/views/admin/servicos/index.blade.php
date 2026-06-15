@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Serviços</h1>
            <a href="{{ route('admin.servicos.create') }}" class="btn btn-primary">+ Novo Serviço</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success" id="flash-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Imagem</th>
                            <th>Título</th>
                            <th>Subtítulo</th>
                            <th>Língua</th>
                            <th>Preço</th>
                            <th>Ordem</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($servicos as $servico)
                            <tr>
                                <td>{{ $servico->id_servico }}</td>
                                <td>
                                    @if ($servico->imagem_servico)
                                        <img src="{{ asset($servico->imagem_servico) }}"
                                            alt="{{ $servico->titulo_servico }}" width="60" height="60"
                                            style="object-fit: cover; border-radius: 6px;">
                                    @else
                                        <span class="text-muted">Sem imagem</span>
                                    @endif
                                </td>
                                <td>{{ $servico->titulo_servico }}</td>
                                <td>{{ $servico->subtitulo_servico }}</td>
                                <td>{{ $servico->lingua_servico }}</td>
                                <td>{{ $servico->preco_servico }}</td>
                                <td>{{ $servico->ordenar_servico }}</td>
                                <td>{{ $servico->status_servico }}</td>

                                <td>
                                    <a href="{{ route('admin.servicos.edit', $servico->id_servico) }}"
                                        class="btn btn-sm btn-warning">Editar</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalExcluir" data-id="{{ $servico->id_servico }}"
                                        data-titulo="{{ $servico->titulo_servico }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Nenhum serviço cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <i class="bi bi-gear-fill text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3 fs-5">Tem certeza que deseja excluir o serviço</p>
                    <strong id="tituloServicoModal" class="fs-5"></strong>
                    <p class="text-muted mt-2">Esta ação não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <form id="formExcluir" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash-fill me-1"></i>Sim, excluir
                        </button>
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
                const titulo = button.getAttribute('data-titulo');
                document.getElementById('tituloServicoModal').textContent = titulo;
                document.getElementById('formExcluir').action = `/admin/servicos/${id}`;
            });
        </script>
    @endpush
@endsection
