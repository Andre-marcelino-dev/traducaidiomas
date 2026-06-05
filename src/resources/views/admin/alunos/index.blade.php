@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Alunos</h1>
            <a href="{{ route('admin.alunos.create') }}" class="btn btn-primary">+ Novo Aluno</a>
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
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Curso</th>
                            <th>Nível</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alunos as $aluno)
                            <tr>
                                <td>{{ $aluno->id_aluno }}</td>
                                <td>
                                    @if ($aluno->foto_aluno)
                                        <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                            alt="{{ $aluno->nome_aluno }}" width="50" height="50"
                                            style="object-fit: cover; border-radius: 50%;">
                                    @else
                                        <span class="text-muted">Sem foto</span>
                                    @endif
                                </td>
                                <td>{{ $aluno->nome_aluno }}</td>
                                <td>{{ $aluno->email_aluno }}</td>
                                <td>{{ $aluno->telefone_aluno }}</td>
                                <td>{{ $aluno->curso_aluno }}</td>
                                <td>{{ $aluno->nivel_aluno }}</td>
                                <td>
                                    <form action="{{ route('admin.alunos.update', $aluno->id_aluno) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nome_aluno" value="{{ $aluno->nome_aluno }}">
                                        <input type="hidden" name="email_aluno" value="{{ $aluno->email_aluno }}">
                                        @if ($aluno->status_aluno == 'EM CURSO')
                                            <input type="hidden" name="status_aluno" value="INATIVO">
                                            <button type="submit" class="badge bg-success border-0"
                                                style="cursor:pointer">EM CURSO</button>
                                        @elseif($aluno->status_aluno == 'INATIVO')
                                            <input type="hidden" name="status_aluno" value="EM CURSO">
                                            <button type="submit" class="badge bg-secondary border-0"
                                                style="cursor:pointer">INATIVO</button>
                                        @else
                                            <input type="hidden" name="status_aluno" value="INATIVO">
                                            <button type="submit" class="badge bg-info border-0"
                                                style="cursor:pointer">{{ $aluno->status_aluno }}</button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.alunos.edit', $aluno->id_aluno) }}"
                                        class="btn btn-sm btn-warning">Editar</a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalExcluir" data-id="{{ $aluno->id_aluno }}"
                                        data-nome="{{ $aluno->nome_aluno }}">
                                        Excluir
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Nenhum aluno cadastrado.</td>
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
                    <p class="mt-3 fs-5">Tem certeza que deseja excluir o aluno</p>
                    <strong id="nomeAlunoModal" class="fs-5"></strong>
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

                document.getElementById('nomeAlunoModal').textContent = nome;
                document.getElementById('formExcluir').action = `/admin/alunos/${id}`;
            });
        </script>
    @endpush
@endsection
