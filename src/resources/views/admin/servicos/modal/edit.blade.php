<div class="modal fade" id="modalEditarservico{{ $servico->id_servico }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $servico->id_servico }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel{{ $servico->id_servico }}">Editar Serviço: {{ $servico->titulo_servico }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.servicos.update', $servico->id_servico) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo_servico" class="form-control" value="{{ old('titulo_servico', $servico->titulo_servico) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtítulo</label>
                        <input type="text" name="subtitulo_servico" class="form-control" value="{{ old('subtitulo_servico', $servico->subtitulo_servico) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Benefícios</label>
                        <input type="text" name="lista_beneficios_servico" class="form-control" value="{{ old('lista_beneficios_servico', $servico->lista_beneficios_servico) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preço</label>
                        <input type="number" name="preco_servico" class="form-control" value="{{ old('preco_servico', $servico->preco_servico) }}" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Conteúdo</label>
                        <input type="text" name="conteudo_servico" class="form-control" value="{{ old('conteudo_servico', $servico->conteudo_servico) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" name="status_servico" class="form-control" value="{{ old('status_servico', $servico->status_servico) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Atual</label>
                        <br>
                        @if ($servico->imagem_servico)
                            <img src="{{ asset('davilla/images/' . $servico->imagem_servico) }}" alt="{{ $servico->titulo_servico }}" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                        @else
                            <p class="text-muted">Nenhuma foto cadastrada.</p>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nova Foto</label>
                        <input type="file" name="imagem_servico" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>

            </form>
        </div>
    </div>
</div>