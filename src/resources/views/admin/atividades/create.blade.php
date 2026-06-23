@extends('admin.layout.admin')

@section('content')

<style>
    .fade-in,
    .fade-up {
        opacity: 0;
        will-change: opacity, transform;
    }

    .fade-in {
        transform: translate3d(0, 0, 0) scale(.985);
        transition: opacity .55s ease, transform .55s ease;
    }

    .fade-up {
        transform: translate3d(0, 42px, 0);
        transition: opacity .8s cubic-bezier(.22, 1, .36, 1), transform .8s cubic-bezier(.22, 1, .36, 1);
    }

    .fade-in.is-visible,
    .fade-up.is-visible {
        opacity: 1;
        transform: translate3d(0, 0, 0) scale(1);
    }

    .delay-1 {
        transition-delay: .12s;
    }

    .delay-2 {
        transition-delay: .24s;
    }

    .delay-3 {
        transition-delay: .36s;
    }

    .atividade-page {
        padding-bottom: 34px;
    }

    .atividade-form-card,
    .questao-card {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 45px rgba(15, 23, 42, .08);
        background: #fff;
    }

    .atividade-form-header,
    .questao-card-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-bottom: 1px solid #e5edf8;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .atividade-title-box {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .atividade-icon,
    .questao-icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: #163d8f;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 10px 22px rgba(22, 61, 143, .18);
    }

    .atividade-form-header h5,
    .questao-card-header h5 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
    }

    .atividade-form-header span,
    .questao-card-header span {
        display: block;
        margin-top: 2px;
        font-size: .82rem;
        color: #64748b;
    }

    .atividade-form-body,
    .questao-card-body {
        padding: 26px;
    }

    .form-section-title {
        font-size: .78rem;
        font-weight: 800;
        color: #163d8f;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin: 4px 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-section-title::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #163d8f;
        display: inline-block;
    }

    .form-label {
        font-size: .86rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 13px;
        border: 1px solid #dbe3ef;
        min-height: 44px;
        font-size: .92rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #163d8f;
        box-shadow: 0 0 0 .18rem rgba(22, 61, 143, .12);
    }

    textarea.form-control {
        resize: vertical;
    }

    .atividade-hint {
        color: #94a3b8;
        font-size: .76rem;
        margin-top: 5px;
    }

    .questao-card {
        margin-bottom: 18px;
    }

    .questao-card-header {
        background: linear-gradient(135deg, #ffffff, #f8fbff);
    }

    .questao-icon {
        width: 40px;
        height: 40px;
        border-radius: 13px;
        background: #10b981;
        box-shadow: 0 10px 20px rgba(16, 185, 129, .16);
    }

    .btn-remover-questao {
        border: 0;
        border-radius: 11px;
        padding: 8px 12px;
        background: #fee2e2;
        color: #dc2626;
        font-size: .8rem;
        font-weight: 800;
        transition: .2s ease;
    }

    .btn-remover-questao:hover {
        background: #ef4444;
        color: #fff;
    }

    .opcoes-multipla {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        margin-top: 12px;
    }

    .opcoes-title {
        font-size: .78rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 12px;
    }

    .atividade-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin: 24px 0 50px;
        flex-wrap: wrap;
    }

    .btn-add-questao {
        border-radius: 12px;
        padding: 10px 18px;
        background: #eef3ff;
        color: #163d8f;
        border: 1px solid #dbeafe;
        font-weight: 800;
        transition: .2s ease;
    }

    .btn-add-questao:hover {
        background: #163d8f;
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-atividade-cancelar {
        border-radius: 12px;
        padding: 10px 18px;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 10px 22px rgba(239, 68, 68, .12);
        transition: .22s ease;
    }

    .btn-atividade-cancelar:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(239, 68, 68, .22);
    }

    .btn-atividade-salvar {
        border-radius: 12px;
        padding: 10px 24px;
        background: #10b981;
        color: #fff;
        border: 0;
        font-weight: 800;
        box-shadow: 0 10px 20px rgba(16, 185, 129, .18);
    }

    .btn-atividade-salvar:hover {
        background: #059669;
        color: #fff;
    }

    @media (max-width: 768px) {
        .atividade-form-body,
        .questao-card-body {
            padding: 18px;
        }

        .atividade-form-header,
        .questao-card-header {
            padding: 18px;
        }

        .questao-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-remover-questao,
        .btn-add-questao,
        .btn-atividade-cancelar,
        .btn-atividade-salvar {
            width: 100%;
            text-align: center;
        }

        .atividade-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .atividade-actions > div {
            width: 100%;
            display: flex;
            flex-direction: column-reverse;
            gap: 10px;
        }
    }
</style>

<div class="atividade-page fade-in">

    <div class="app-content-header fade-up">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Nova Atividade</h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dash') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.atividades.index') }}">Atividades</a>
                        </li>
                        <li class="breadcrumb-item active">Nova</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <form action="{{ route('admin.atividades.store') }}" method="POST" id="formAtividade">
                @csrf

                <div class="card atividade-form-card mb-4 fade-up delay-1">
                    <div class="atividade-form-header">
                        <div class="atividade-title-box">
                            <div class="atividade-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                    stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                            </div>

                            <div>
                                <h5>Informações da Atividade</h5>
                                <span>Defina título, curso, prazo e instruções para os alunos.</span>
                            </div>
                        </div>
                    </div>

                    <div class="atividade-form-body">
                        <div class="form-section-title">Dados principais</div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Título <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="titulo_atividade"
                                    class="form-control"
                                    value="{{ old('titulo_atividade') }}"
                                    placeholder="Ex: Exercícios de revisão"
                                    required
                                >
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">
                                    Curso <span class="text-danger">*</span>
                                </label>

                                <select name="id_curso" class="form-select" required>
                                    <option value="">Selecione</option>

                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id_curso }}" {{ old('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                            {{ $curso->nome_curso }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">
                                    Data de Entrega <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="data_entrega"
                                    class="form-control"
                                    value="{{ old('data_entrega') }}"
                                    required
                                >
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Descrição/Instruções</label>

                                <textarea
                                    name="descricao_atividade"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Descreva o que o aluno deve fazer, critérios de entrega ou orientações..."
                                >{{ old('descricao_atividade') }}</textarea>

                                <div class="atividade-hint">
                                    Essa descrição aparecerá como orientação geral da atividade.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section-title">Questões</div>

                <div id="questoes" class="fade-up delay-2">
                    <div class="card questao-card" data-index="0">
                        <div class="questao-card-header">
                            <div class="atividade-title-box">
                                <div class="questao-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                        viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                        stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9.09 9a3 3 0 1 1 5.82 1c0 2-3 2-3 4"></path>
                                        <path d="M12 17h.01"></path>
                                    </svg>
                                </div>

                                <div>
                                    <h5 class="questao-titulo">Questão 1</h5>
                                    <span>Configure o enunciado e o tipo de resposta.</span>
                                </div>
                            </div>

                            <button type="button" class="btn-remover-questao remover-questao">
                                Remover
                            </button>
                        </div>

                        <div class="questao-card-body">
                            <div class="mb-3">
                                <label class="form-label">
                                    Enunciado <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    name="enunciado[]"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Digite o enunciado da questão..."
                                    required
                                ></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo</label>

                                <select name="tipo_questao[]" class="form-select tipo-questao">
                                    <option value="multipla_escolha">Múltipla Escolha</option>
                                    <option value="texto">Texto Dissertativo</option>
                                </select>
                            </div>

                            <div class="opcoes-multipla">
                                <div class="opcoes-title">Alternativas da questão</div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Opção A</label>
                                        <input type="text" name="opcao_a[]" class="form-control" placeholder="Digite a opção A">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Opção B</label>
                                        <input type="text" name="opcao_b[]" class="form-control" placeholder="Digite a opção B">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Opção C</label>
                                        <input type="text" name="opcao_c[]" class="form-control" placeholder="Digite a opção C">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Opção D</label>
                                        <input type="text" name="opcao_d[]" class="form-control" placeholder="Digite a opção D">
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
                        </div>
                    </div>
                </div>

                <div class="atividade-actions fade-up delay-3">
                    <button type="button" id="addQuestao" class="btn-add-questao">
                        + Adicionar Questão
                    </button>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.atividades.index') }}" class="btn-atividade-cancelar">
                            Cancelar
                        </a>

                        <button type="submit" class="btn-atividade-salvar">
                            Salvar Atividade
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    requestAnimationFrame(function() {
        document.querySelectorAll('.fade-in, .fade-up').forEach(function(element) {
            element.classList.add('is-visible');
        });
    });
});
</script>

<script>
let questaoIndex = 1;

const questoesContainer = document.getElementById('questoes');
const botaoAddQuestao = document.getElementById('addQuestao');

botaoAddQuestao.addEventListener('click', function() {
    const primeiraQuestao = document.querySelector('.questao-card');
    const novaQuestao = primeiraQuestao.cloneNode(true);

    novaQuestao.setAttribute('data-index', questaoIndex);

    novaQuestao.querySelectorAll('input, textarea').forEach(function(el) {
        el.value = '';
    });

    novaQuestao.querySelectorAll('select').forEach(function(el) {
        if (el.classList.contains('tipo-questao')) {
            el.value = 'multipla_escolha';
        } else {
            el.value = 'A';
        }
    });

    const opcoes = novaQuestao.querySelector('.opcoes-multipla');
    if (opcoes) {
        opcoes.style.display = 'block';
    }

    questoesContainer.appendChild(novaQuestao);

    questaoIndex++;

    atualizarNumeracaoQuestoes();
    bindEvents();
});

function atualizarNumeracaoQuestoes() {
    document.querySelectorAll('.questao-card').forEach(function(card, index) {
        const titulo = card.querySelector('.questao-titulo');

        if (titulo) {
            titulo.textContent = 'Questão ' + (index + 1);
        }
    });
}

function bindEvents() {
    document.querySelectorAll('.remover-questao').forEach(function(btn) {
        btn.onclick = function() {
            const totalQuestoes = document.querySelectorAll('.questao-card').length;

            if (totalQuestoes > 1) {
                this.closest('.questao-card').remove();
                atualizarNumeracaoQuestoes();
            }
        };
    });

    document.querySelectorAll('.tipo-questao').forEach(function(sel) {
        sel.onchange = function() {
            const cardBody = this.closest('.questao-card-body');
            const opcoes = cardBody.querySelector('.opcoes-multipla');

            if (opcoes) {
                opcoes.style.display = this.value === 'multipla_escolha' ? 'block' : 'none';
            }
        };
    });
}

bindEvents();
atualizarNumeracaoQuestoes();
</script>

@endsection