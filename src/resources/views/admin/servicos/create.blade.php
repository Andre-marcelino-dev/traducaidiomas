@extends('admin.layout.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Novo Serviço</h1>
            <a href="{{ route('admin.servicos.index') }}" class="btn btn-secondary">← Voltar</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.servicos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo_servico" class="form-control" value="{{ old('titulo_servico') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subtítulo</label>
                            <input type="text" name="subtitulo_servico" class="form-control" value="{{ old('subtitulo_servico') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Língua</label>
                            <input type="text" name="lingua_servico" class="form-control" value="{{ old('lingua_servico') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preço</label>
                            <input type="text" name="preco_servico" class="form-control" value="{{ old('preco_servico') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link WhatsApp</label>
                            <input type="text" name="link_whatsapp" class="form-control" value="{{ old('link_whatsapp') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Classe Estilo</label>
                            <input type="text" name="classe_estilo_servico" class="form-control" value="{{ old('classe_estilo_servico') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CTA Título</label>
                            <input type="text" name="cta_titulo_servico" class="form-control" value="{{ old('cta_titulo_servico') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CTA Texto</label>
                            <input type="text" name="cta_texto_servico" class="form-control" value="{{ old('cta_texto_servico') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Título Professor</label>
                            <input type="text" name="titulo_professor_servico" class="form-control" value="{{ old('titulo_professor_servico') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contato Texto</label>
                            <input type="text" name="contato_text_servico" class="form-control" value="{{ old('contato_text_servico') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ordem</label>
                            <input type="number" name="ordenar_servico" class="form-control" value="{{ old('ordenar_servico') }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Lista de Benefícios</label>
                            <textarea name="lista_beneficios_servico" class="form-control" rows="3">{{ old('lista_beneficios_servico') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Conteúdo</label>
                            <textarea name="conteudo_servico" class="form-control" rows="4">{{ old('conteudo_servico') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Imagem</label>
                            <input type="file" name="imagem_servico" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('admin.servicos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
