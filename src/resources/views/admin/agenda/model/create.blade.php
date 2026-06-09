@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

{{-- HEADER --}}
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Novo Agendamento</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.agendas.index') }}">Agendamentos</a></li>
                    <li class="breadcrumb-item active">Novo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row fade-up">
            <div class="col-12">
                <div class="card recent-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-plus me-2 text-primary"></i>Dados do Agendamento</h5>
                    </div>
                    <div class="card-body p-4">

                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <form action="{{ route('admin.agendas.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Aluno <span class="text-danger">*</span></label>
                                    <select name="id_aluno" class="form-select @error('id_aluno') is-invalid @enderror" required>
                                        <option value="">Selecione o aluno</option>

                                        @foreach($alunos as $aluno)
                                        {{-- Alterado de $aluno->id para $aluno->id_aluno --}}
                                        <option value="{{ $aluno->id_aluno }}" {{ old('id_aluno') == $aluno->id_aluno ? 'selected' : '' }}>
                                            {{ $aluno->nome_aluno }} {{-- Alterado de $aluno->nome para $aluno->nome_aluno --}}
                                        </option>
                                        @endforeach

                                    </select>
                                    @error('id_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Professor <span class="text-danger">*</span></label>
                                    <select name="id_professor" class="form-select @error('id_professor') is-invalid @enderror" required>
                                        <option value="">Selecione o professor</option>
                                        @foreach($professores as $professor)
                                        <option value="{{ $professor->id_professor }}" {{ old('id_professor') == $professor->id_professor ? 'selected' : '' }}>
                                            {{ $professor->nome_professor }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('id_professor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                                    <input type="text" name="titulo_agenda" value="{{ old('titulo_agenda') }}"
                                        class="form-control @error('titulo_agenda') is-invalid @enderror"
                                        placeholder="Título do agendamento" required>
                                    @error('titulo_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Descrição</label>
                                    <textarea name="descricao_agenda" rows="3"
                                        class="form-control @error('descricao_agenda') is-invalid @enderror"
                                        placeholder="Descrição da aula ou evento">{{ old('descricao_agenda') }}</textarea>
                                    @error('descricao_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Data do Evento <span class="text-danger">*</span></label>
                                    <input type="date" name="data_evento_agenda" value="{{ old('data_evento_agenda') }}"
                                        class="form-control @error('data_evento_agenda') is-invalid @enderror" required>
                                    @error('data_evento_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Hora Início <span class="text-danger">*</span></label>
                                    <input type="time" name="hora_inicio_agenda" value="{{ old('hora_inicio_agenda') }}"
                                        class="form-control @error('hora_inicio_agenda') is-invalid @enderror" required>
                                    @error('hora_inicio_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Hora Fim <span class="text-danger">*</span></label>
                                    <input type="time" name="hora_fim_agenda" value="{{ old('hora_fim_agenda') }}"
                                        class="form-control @error('hora_fim_agenda') is-invalid @enderror" required>
                                    @error('hora_fim_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select name="status_agenda" class="form-select @error('status_agenda') is-invalid @enderror" required>
                                        <option value="">Selecione o status</option>
                                        <option value="pendente" {{ old('status_agenda') == 'pendente'     ? 'selected' : '' }}>Pendente</option>
                                        <option value="confirmado" {{ old('status_agenda') == 'confirmado'   ? 'selected' : '' }}>Confirmado</option>
                                        <option value="cancelado" {{ old('status_agenda') == 'cancelado'    ? 'selected' : '' }}>Cancelado</option>
                                        <option value="reagendado" {{ old('status_agenda') == 'reagendado'   ? 'selected' : '' }}>Reagendado</option>
                                    </select>
                                    @error('status_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Link da Aula</label>
                                    <input type="url" name="link_aula_agenda" value="{{ old('link_aula_agenda') }}"
                                        class="form-control @error('link_aula_agenda') is-invalid @enderror"
                                        placeholder="https://meet.google.com/...">
                                    @error('link_aula_agenda') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Salvar Agendamento
                                </button>
                                <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Voltar
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection