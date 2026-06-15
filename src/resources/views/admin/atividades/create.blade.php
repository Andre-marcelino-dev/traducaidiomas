@extends('admin.layout.admin')
@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Nova Atividade</h3></div>
        </div>
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        <form action="{{ route('admin.atividades.store') }}" method="POST" id="formAtividade">
            @csrf
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold" style="background:#1a1a2e; color:#fff;">📋 Informações da Atividade</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo_atividade" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Curso</label>
                            <select name="id_curso" class="form-select" required>
                                <option value="">Selecione</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id_curso }}">{{ $curso->nome_curso }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data de Entrega</label>
                            <input type="date" name="data_entrega" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Descrição/Instruções</label>
                            <textarea name="descricao_atividade" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div id="questoes">
                <div class="card shadow-sm mb-3 questao-card" data-index="0">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background:#1a1a2e; color:#fff;">
                        <span>❓ Questão 1</span>
                        <button type="button" class="btn btn-sm btn-danger remover-questao">Remover</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Enunciado</label>
                            <textarea name="enunciado[]" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo_questao[]" class="form-select tipo-questao">
                                <option value="multipla_escolha">Múltipla Escolha</option>
                                <option value="texto">Texto Dissertativo</option>
                            </select>
                        </div>
                        <div class="opcoes-multipla">
                            <div class="row g-2">
                                <div class="col-md-6"><label class="form-label">Opção A</label><input type="text" name="opcao_a[]" class="form-control"></div>
                                <div class="col-md-6"><label class="form-label">Opção B</label><input type="text" name="opcao_b[]" class="form-control"></div>
                                <div class="col-md-6"><label class="form-label">Opção C</label><input type="text" name="opcao_c[]" class="form-control"></div>
                                <div class="col-md-6"><label class="form-label">Opção D</label><input type="text" name="opcao_d[]" class="form-control"></div>
                                <div class="col-md-3">
                                    <label class="form-label">Resposta Correta</label>
                                    <select name="resposta_correta[]" class="form-select">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addQuestao" class="btn btn-outline-primary mb-4">
                <i class="fas fa-plus me-1"></i> Adicionar Questão
            </button>

            <div class="d-flex gap-2 mb-5">
                <button type="submit" class="btn btn-success px-5"><i class="fas fa-save me-1"></i> Salvar Atividade</button>
                <a href="{{ route('admin.atividades.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
let questaoIndex = 1;

document.getElementById('addQuestao').addEventListener('click', function() {
    const template = document.querySelector('.questao-card').cloneNode(true);
    template.setAttribute('data-index', questaoIndex);
    template.querySelector('.card-header span').textContent = '❓ Questão ' + (questaoIndex + 1);
    template.querySelectorAll('input, textarea, select').forEach(el => el.value = '');
    document.getElementById('questoes').appendChild(template);
    questaoIndex++;
    bindEvents();
});

function bindEvents() {
    document.querySelectorAll('.remover-questao').forEach(btn => {
        btn.onclick = function() {
            if (document.querySelectorAll('.questao-card').length > 1) {
                this.closest('.questao-card').remove();
            }
        };
    });
    document.querySelectorAll('.tipo-questao').forEach(sel => {
        sel.onchange = function() {
            const opcoes = this.closest('.card-body').querySelector('.opcoes-multipla');
            opcoes.style.display = this.value === 'multipla_escolha' ? 'block' : 'none';
        };
    });
}
bindEvents();
</script>
@endsection
