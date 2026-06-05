@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Professor</h1>
            <a href="{{ route('admin.professores.create') }}" class="btn btn-primary">+ Novo Professor</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success" id="flash-success">{{ session('success') }}</div>
        @endif

<<<<<<< Updated upstream
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Especialidade</th>
                            <th>Email</th>
                            <th>Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($professores as $professor)
                            <tr>
                                <td>{{ $professor->id_professor }}</td>
                                <td>
                                    @if ($professor->foto_professor)
                                        <img src="{{ asset('traducaidiomas/professor/' . $professor->foto_professor) }}"
                                            alt="{{ $professor->nome_professor }}" width="50" height="50"
                                            style="object-fit: cover; border-radius: 50%;">
                                    @else
                                        <span class="text-muted">Sem foto</span>
                                    @endif
                                </td>
                                <td>{{ $professor->nome_professor }}</td>
                                <td>{{ $professor->especialidade_professor }}</td>
                                <td>{{ $professor->email_professor }}</td>
                                <td>
                                    <a href="{{ route('admin.professores.edit', $professor->id_professor) }}"
                                        class="btn btn-sm btn-warning">Editar</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalExcluir" data-id="{{ $professor->id_professor }}"
                                        data-nome="{{ $professor->nome_professor }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Nenhum professor cadastrado.</td>
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
                    <i class="bi bi-person-x-fill text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3 fs-5">Tem certeza que deseja excluir o professor</p>
                    <strong id="nomeProfessorModal" class="fs-5"></strong>
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
                const nome = button.getAttribute('data-nome');

                document.getElementById('nomeProfessorModal').textContent = nome;
                document.getElementById('formExcluir').action = `/admin/professores/${id}`;
            });
        </script>
    @endpush

@endsection
=======
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th style="width: 80px; text-align: center;">Foto</th>
                        <th>Nome</th>
                        <th>Especialidade</th>
                        <th>Email</th>
                        <th>Nível</th>
                        <th style="width: 150px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($professores as $professor)
                    <tr>
                        <td class="text-center">{{ $professor->id_professor }}</td>
                        <td class="text-center">
                            @if(!empty($professor->foto_professor))
                                <img src="{{ asset('traducaidiomas/professor/'.$professor->foto_professor) }}?v={{ time() }}" 
                                     alt="Foto" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 1px solid #ccc;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($professor->nome_professor) }}&background=0D6EFD&color=fff&size=50" 
                                     alt="Iniciais" 
                                     style="width: 50px; height: 50px; border-radius: 50%;">
                            @endif
                        </td>
                        <td>{{ $professor->nome_professor }}</td>
                        <td>{{ $professor->especialidade_professor }}</td>
                        <td>{{ $professor->email_professor }}</td>
                        <td>{{ $professor->nivel_professor }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.professores.edit', $professor->id_professor) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('admin.professores.destroy', $professor->id_professor) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Nenhum professor cadastrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
>>>>>>> Stashed changes
