<div class="modal fade" id="modalNovoServico" tabindex="-1" aria-labelledby="modalCriarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="modalCriarLabel">Cadastrar Novo Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.servicos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo_servico" class="form-control" value="{{ old('titulo_servico') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subtítulo</label>
                        <input type="text" name="subtitulo_servico" class="form-control" value="{{ old('subtitulo_servico') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Benefícios</label>
                        <input type="text" name="lista_beneficios_servico" class="form-control" value="{{ old('lista_beneficios_servico') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preço</label>
                        <input type="number" name="preco_servico" class="form-control" value="{{ old('preco_servico') }}" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Conteúdo</label>
                        <input type="text" name="conteudo_servico" class="form-control" value="{{ old('conteudo_servico') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" name="status_servico" class="form-control" value="ATIVO">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto do Serviço</label>
                        <input type="file" name="imagem_servico" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Cadastrar Serviço</button>
                </div>

            </form>
        </div>
    </div>
</div>x