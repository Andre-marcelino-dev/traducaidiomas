@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

{{-- HEADER --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Editar Agendamento</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agendas.index') }}">Agendamentos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        {{-- Exibição de erros de validação caso ocorram --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORMULÁRIO --}}
        <div class="row fade-up">
            <div class="col-12">
                <div class="card recent-card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Alterar Informações da Agenda #{{ $agenda->id_agenda }}
                        </h5>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('admin.agendas.update', $agenda->id_agenda) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                {{-- 1. Título do Agendamento --}}
                                <div class="col-md-12">
                                    <label for="titulo_agenda" class="form-label fw-semibold">Título do Agendamento</label>
                                    <input type="text" class="form-control" id="titulo_agenda" name="titulo_agenda" 
                                           value="{{ old('titulo_agenda', $agenda->titulo_agenda) }}" required>
                                </div>

                                {{-- 2. Seleção do Aluno --}}
                                <div class="col-md-6">
                                    <label for="id_aluno" class="form-label fw-semibold">Aluno</label>
                                    <select class="form-select" id="id_aluno" name="id_aluno" required>
                                        <option value="">Selecione um Aluno</option>
                                        @foreach($alunos as $aluno)
                                            <option value="{{ $aluno->id_aluno }}" {{ old('id_aluno', $agenda->id_aluno) == $aluno->id_aluno ? 'selected' : '' }}>
                                                {{ $aluno->nome_aluno }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 3. Seleção do Professor --}}
                                <div class="col-md-6">
                                    <label for="id_professor" class="form-label fw-semibold">Professor</label>
                                    <select class="form-select" id="id_professor" name="id_professor" required>
                                        <option value="">Selecione um Professor</option>
                                        @foreach($professores as $professor)
                                            <option value="{{ $professor->id_professor }}" {{ old('id_professor', $agenda->id_professor) == $professor->id_professor ? 'selected' : '' }}>
                                                {{ $professor->nome_professor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 4. Data do Evento --}}
                                <div class="col-md-4">
                                    <label for="data_evento_agenda" class="form-label fw-semibold">Data do Evento</label>
                                    <input type="date" class="form-control" id="data_evento_agenda" name="data_evento_agenda" 
                                           value="{{ old('data_evento_agenda', $agenda->data_evento_agenda) }}" required>
                                </div>

                                {{-- 5. Horário de Início --}}
                                <div class="col-md-4">
                                    <label for="hora_inicio_agenda" class="form-label fw-semibold">Horário de Início</label>
                                    <input type="time" class="form-control" id="hora_inicio_agenda" name="hora_inicio_agenda" 
                                           value="{{ old('hora_inicio_agenda', \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i')) }}" required>
                                </div>

                                {{-- 6. Horário de Término --}}
                                <div class="col-md-4">
                                    <label for="hora_fim_agenda" class="form-label fw-semibold">Horário de Término</label>
                                    <input type="time" class="form-control" id="hora_fim_agenda" name="hora_fim_agenda" 
                                           value="{{ old('hora_fim_agenda', \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i')) }}" required>
                                </div>

                                {{-- 7. Link da Aula Aula --}}
                                <div class="col-md-8">
                                    <label for="link_aula_agenda" class="form-label fw-semibold">Link da Videochamada (Aula)</label>
                                    <input type="url" class="form-control" id="link_aula_agenda" name="link_aula_agenda" 
                                           value="{{ old('link_aula_agenda', $agenda->link_aula_agenda) }}" placeholder="https://teams.microsoft.com/...">
                                </div>

                                {{-- 8. Status da Agenda --}}
                                <div class="col-md-4">
                                    <label for="status_agenda" class="form-label fw-semibold">Status</label>
                                    <select class="form-select" id="status_agenda" name="status_agenda" required>
                                        <option value="pendente" {{ old('status_agenda', $agenda->status_agenda) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="confirmado" {{ old('status_agenda', $agenda->status_agenda) == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="cancelado" {{ old('status_agenda', $agenda->status_agenda) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="reagendado" {{ old('status_agenda', $agenda->status_agenda) == 'reagendado' ? 'selected' : '' }}>Reagendamento</option>
                                    </select>
                                </div>

                                {{-- 9. Descrição / Notas --}}
                                <div class="col-12">
                                    <label for="descricao_agenda" class="form-label fw-semibold">Descrição / Observações</label>
                                    <textarea class="form-control" id="descricao_agenda" name="descricao_agenda" rows="4" required>{{ old('descricao_agenda', $agenda->descricao_agenda) }}</textarea>
                                </div>

                                {{-- BOTOÕES DE AÇÃO --}}
                                <div class="col-12 text-end mt-4">
                                    <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary me-2">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-1"></i> Salvar Alterações
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection