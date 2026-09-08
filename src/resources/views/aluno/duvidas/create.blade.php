@extends('aluno.layout.aluno')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Tirar Dúvida</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('aluno.dash') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('aluno.duvidas.index') }}">Minhas Dúvidas</a></li>
                    <li class="breadcrumb-item active">Nova</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-card fade-up">
                    <div class="d-card-header">
                        <h6><i class="fas fa-envelope text-primary"></i> Falar com o Professor</h6>
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

                        <form action="{{ route('aluno.duvidas.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">

                                {{-- Assunto --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Assunto <span class="text-danger">*</span></label>
                                    <input type="text" name="assunto_duvida" class="form-control @error('assunto_duvida') is-invalid @enderror"
                                           value="{{ old('assunto_duvida') }}" placeholder="Ex: Dúvida sobre o material do curso">
                                    @error('assunto_duvida')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Mensagem --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mensagem <span class="text-danger">*</span></label>
                                    <textarea name="mensagem_duvida" rows="5"
                                              class="form-control @error('mensagem_duvida') is-invalid @enderror"
                                              placeholder="Descreva sua dúvida...">{{ old('mensagem_duvida') }}</textarea>
                                    @error('mensagem_duvida')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('aluno.duvidas.index') }}" class="del-btn-cancelar">
                                    <i class="fas fa-arrow-left me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="tbl-btn-success">
                                    <i class="fas fa-paper-plane"></i> Enviar Dúvida
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
