@extends('admin.layout.admin')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Nova Atividade</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <form action="{{ route('admin.atividades.store') }}" method="POST" id="formAtividade">
            @csrf

            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold" style="background:#1a1a2e;color:#fff;">
                    📋 Informações da Atividade
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo_atividade" class="form-control" required
                                   value="{{ old('titulo_atividade') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Curso</label>
                            <select name="id_curso" class="form-select" required>
                                <option value="">Selecione</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id_curso }}"
                                        {{ old('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                        {{ $curso->nome_curso }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Data de Entrega</label>
                            <input type="date" name="data_entrega" class="form-control" required
                                   value="{{ old('data_entrega') }}">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Descrição/Instruções</label>
                            <textarea name="descricao_atividade" class="form-control" rows="3">{{ old('descricao_atividade') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div id="questoes">
                <div class="card shadow-sm mb-3 questao-card" data-index="0">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background:#1a1a2e;color:#fff;">
                        <span>❓ Questão 1</span>
                        <button type="button" class="btn btn-sm btn-danger remover-questao">
                            Remover
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Enunciado</label>
                            <textarea name="enunciado[]" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo_questao[]" class="form-select tipo-questao" required>
                                <option value="multipla_escolha">Múltipla Escolha</option>
                                <option value="texto">Texto Dissertativo</option>
                                <option value="audio">🎙️ Resposta em Áudio</option>
                            </select>
                        </div>

                        <div class="opcoes-multipla">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Opção A</label>
                                    <input type="text" name="opcao_a[]" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Opção B</label>
                                    <input type="text" name="opcao_b[]" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Opção C</label>
                                    <input type="text" name="opcao_c[]" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Opção D</label>
                                    <input type="text" name="opcao_d[]" class="form-control">
                                </div>
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

                        <div class="aviso-audio alert alert-info mt-3 d-none">
                            <i class="fas fa-microphone me-1"></i>
                            O aluno deverá gravar a resposta usando o microfone. Escreva no enunciado o que ele deve falar.
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="addQuestao" class="btn btn-outline-primary mb-4">
                <i class="fas fa-plus me-1"></i> Adicionar Questão
            </button>

            <div class="d-flex gap-2 mb-5">
                <button type="submit" class="btn btn-success px-5">
                    <i class="fas fa-save me-1"></i> Salvar Atividade
                </button>
                <a href="{{ route('admin.atividades.index') }}" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('questoes');
    const botaoAdicionar = document.getElementById('addQuestao');

    function atualizarQuestao(card) {
        const tipo = card.querySelector('.tipo-questao').value;
        const opcoes = card.querySelector('.opcoes-multipla');
        const avisoAudio = card.querySelector('.aviso-audio');

        opcoes.classList.toggle('d-none', tipo !== 'multipla_escolha');
        avisoAudio.classList.toggle('d-none', tipo !== 'audio');

        // Evita que campos de alternativas escondidos sejam obrigatórios
        // no futuro, caso sejam adicionadas regras required.
        opcoes.querySelectorAll('input, select').forEach(function (campo) {
            campo.disabled = tipo !== 'multipla_escolha';
        });
    }

    function atualizarNumeracao() {
        container.querySelectorAll('.questao-card').forEach(function (card, indice) {
            card.dataset.index = indice;
            card.querySelector('.card-header span').textContent =
                '❓ Questão ' + (indice + 1);
        });
    }

    container.addEventListener('change', function (evento) {
        if (evento.target.matches('.tipo-questao')) {
            atualizarQuestao(evento.target.closest('.questao-card'));
        }
    });

    container.addEventListener('click', function (evento) {
        const botaoRemover = evento.target.closest('.remover-questao');

        if (!botaoRemover) return;

        if (container.querySelectorAll('.questao-card').length <= 1) {
            alert('A atividade precisa ter pelo menos uma questão.');
            return;
        }

        botaoRemover.closest('.questao-card').remove();
        atualizarNumeracao();
    });

    botaoAdicionar.addEventListener('click', function () {
        const original = container.querySelector('.questao-card');
        const novaQuestao = original.cloneNode(true);

        novaQuestao.querySelectorAll('input, textarea').forEach(function (campo) {
            campo.value = '';
        });

        novaQuestao.querySelectorAll('select').forEach(function (campo) {
            campo.selectedIndex = 0;
        });

        container.appendChild(novaQuestao);
        atualizarQuestao(novaQuestao);
        atualizarNumeracao();
    });

    container.querySelectorAll('.questao-card').forEach(atualizarQuestao);
});
</script>
@endsection