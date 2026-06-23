@extends('admin.layout.admin')

@section('content')
<div class="container-fluid py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0"><i class="bi bi-clipboard2-check me-2"></i>Registro de Presença</h3>
            <small class="text-muted">{{ $aula->cursos_aulas }} — {{ date('d/m/Y') }}</small>
        </div>
        <a href="{{ route('admin.presenca.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    {{-- Card da Aula --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body d-flex gap-4">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Aula</small>
                <p class="mb-0 fw-bold">{{ $aula->titulo_aulas }}</p>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-semibold">Curso</small>
                <p class="mb-0 fw-bold">{{ $aula->cursos_aulas }}</p>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-semibold">Data</small>
                <p class="mb-0 fw-bold">{{ date('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    @if($alunos->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>Nenhum aluno encontrado para este curso.
        </div>
    @else
    <form action="{{ route('admin.presenca.salvar') }}" method="POST">
        @csrf
        <input type="hidden" name="id_aulas" value="{{ $aula->id_aulas }}">

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-people me-2"></i>Alunos ({{ $alunos->count() }})</span>
                <button type="button" class="btn btn-sm btn-outline-success" id="marcarTodos">
                    <i class="bi bi-check-all me-1"></i> Marcar todos presentes
                </button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Aluno</th>
                            <th class="text-center" style="width:130px">
                                <span class="text-success"><i class="bi bi-check-circle me-1"></i>Presente</span>
                            </th>
                            <th class="text-center" style="width:130px">
                                <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Falta</span>
                            </th>
                            <th class="text-center" style="width:130px">
                                <span class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>Justificado</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alunos as $aluno)
                        @php
                            $statusAtual = $presencas[$aluno->id_aluno]->status_presenca ?? 'presente';
                        @endphp
                        <tr class="align-middle presenca-row" data-id="{{ $aluno->id_aluno }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                         style="width:36px;height:36px;font-size:14px;flex-shrink:0">
                                        {{ strtoupper(substr($aluno->nome_aluno, 0, 1)) }}
                                    </div>
                                    <span>{{ $aluno->nome_aluno }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <input class="form-check-input presenca-radio" type="radio"
                                    name="presencas[{{ $aluno->id_aluno }}]" value="presente"
                                    {{ $statusAtual == 'presente' ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input class="form-check-input presenca-radio" type="radio"
                                    name="presencas[{{ $aluno->id_aluno }}]" value="falta"
                                    {{ $statusAtual == 'falta' ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input class="form-check-input presenca-radio" type="radio"
                                    name="presencas[{{ $aluno->id_aluno }}]" value="justificado"
                                    {{ $statusAtual == 'justificado' ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i> Salvar Presença
                </button>
                <a href="{{ route('admin.presenca.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('marcarTodos')?.addEventListener('click', function () {
        document.querySelectorAll('.presenca-row').forEach(row => {
            row.querySelector('input[value="presente"]').checked = true;
            row.classList.remove('table-success', 'table-danger', 'table-warning');
            row.classList.add('table-success');
        });
    });

    function aplicarCor(radio) {
        const row = radio.closest('tr');
        row.classList.remove('table-success', 'table-danger', 'table-warning');
        if (radio.value === 'presente') row.classList.add('table-success');
        else if (radio.value === 'falta') row.classList.add('table-danger');
        else row.classList.add('table-warning');
    }

    document.querySelectorAll('.presenca-radio').forEach(radio => {
        radio.addEventListener('change', function () { aplicarCor(this); });
        if (radio.checked) aplicarCor(radio);
    });

});
</script>
@endpush