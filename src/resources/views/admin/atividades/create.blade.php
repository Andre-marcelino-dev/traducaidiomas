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

        <form
            action="{{ route('admin.atividades.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="formAtividade"
        >
            @csrf

            {{-- ============================= --}}
            {{-- INFORMAÇÕES DA ATIVIDADE       --}}
            {{-- ============================= --}}

            <div class="card shadow-sm mb-4">

                <div
                    class="card-header fw-bold"
                    style="background:#1a1a2e; color:#fff;"
                >
                    📋 Informações da Atividade
                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- ============================= --}}
                        {{-- ESTILO DA ATIVIDADE            --}}
                        {{-- ============================= --}}

                        <div class="col-md-6">

                            <label class="form-label fw-bold">
                                Estilo da Atividade
                            </label>

                            <select
                                name="tipo_atividade"
                                id="tipoAtividade"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Selecione o estilo da atividade
                                </option>

                                <option
                                    value="conjugacao"
                                    @selected(old('tipo_atividade') === 'conjugacao')
                                >
                                    📕 Conjugação de verbo
                                </option>

                                <option
                                    value="conversa"
                                    @selected(old('tipo_atividade') === 'conversa')
                                >
                                    🎧 Conversa
                                </option>

                                <option
                                    value="pronuncia"
                                    @selected(old('tipo_atividade') === 'pronuncia')
                                >
                                    🎙️ Pronúncia
                                </option>

                                <option
                                    value="leitura"
                                    @selected(old('tipo_atividade') === 'leitura')
                                >
                                    📖 Leitura
                                </option>
                            </select>

                            <div class="form-text">
                                Esse estilo será utilizado no aplicativo
                                do aluno para definir o ícone da atividade.
                            </div>

                            @error('tipo_atividade')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- TÍTULO                         --}}
                        {{-- ============================= --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Título
                            </label>

                            <input
                                type="text"
                                name="titulo_atividade"
                                value="{{ old('titulo_atividade') }}"
                                class="form-control"
                                required
                            >

                            @error('titulo_atividade')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- CURSO                          --}}
                        {{-- ============================= --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Curso
                            </label>

                            <select
                                name="id_curso"
                                class="form-select"
                                required
                            >
                                <option value="">
                                    Selecione
                                </option>

                                @foreach($cursos as $curso)

                                    <option
                                        value="{{ $curso->id_curso }}"
                                        @selected(old('id_curso') == $curso->id_curso)
                                    >
                                        {{ $curso->nome_curso }}
                                    </option>

                                @endforeach

                            </select>

                            @error('id_curso')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- DATA DE ENTREGA                --}}
                        {{-- ============================= --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Data de Entrega
                            </label>

                            <input
                                type="date"
                                name="data_entrega"
                                value="{{ old('data_entrega') }}"
                                class="form-control"
                                required
                            >

                            @error('data_entrega')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- MÓDULO                         --}}
                        {{-- ============================= --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Módulo
                            </label>

                            <select
                                name="id_modulo"
                                id="selectModulo"
                                class="form-select"
                            >
                                <option value="">
                                    Nenhum / não definido
                                </option>

                                @foreach($modulos as $modulo)

                                    <option
                                        value="{{ $modulo->id_modulo }}"
                                        data-curso="{{ $modulo->id_curso }}"
                                        @selected(old('id_modulo') == $modulo->id_modulo)
                                    >
                                        {{ $modulo->curso?->nome_curso }}
                                        ·
                                        {{ $modulo->nivel?->nome_nivel }}
                                        ·
                                        {{ $modulo->ordem_modulo }}
                                        -
                                        {{ $modulo->nome_modulo }}
                                    </option>

                                @endforeach

                            </select>

                            @error('id_modulo')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- CATEGORIA                      --}}
                        {{-- ============================= --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Categoria
                            </label>

                            <select
                                name="categoria_atividade"
                                class="form-select"
                            >
                                <option value="">
                                    Selecione
                                </option>

                                @foreach(\App\Models\Atividade::CATEGORIAS as $valor => $cat)

                                    <option
                                        value="{{ $valor }}"
                                        @selected(old('categoria_atividade') === $valor)
                                    >
                                        {{ $cat['label'] }}
                                    </option>

                                @endforeach

                            </select>

                            @error('categoria_atividade')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- FINALIDADE                     --}}
                        {{-- ============================= --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Finalidade
                            </label>

                            <select
                                name="finalidade_atividade"
                                class="form-select"
                            >

                                @foreach(\App\Models\Atividade::FINALIDADES as $valor => $label)

                                    <option
                                        value="{{ $valor }}"
                                        @selected(old('finalidade_atividade', 'FIXACAO') === $valor)
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                            @error('finalidade_atividade')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- DESCRIÇÃO                      --}}
                        {{-- ============================= --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Descrição/Instruções
                            </label>

                            <textarea
                                name="descricao_atividade"
                                class="form-control"
                                rows="3"
                            >{{ old('descricao_atividade') }}</textarea>

                            @error('descricao_atividade')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- ============================= --}}
                        {{-- ÁUDIO DA ATIVIDADE              --}}
                        {{-- ============================= --}}

                        <div
                            class="col-md-12"
                            id="campoAudioAtividade"
                            style="display:none;"
                        >

                            <div class="border rounded p-3 bg-light">

                                <label class="form-label fw-bold">
                                    🎧 Áudio da Atividade
                                </label>

                                <input
                                    type="file"
                                    name="audio"
                                    id="audioAtividade"
                                    class="form-control @error('audio') is-invalid @enderror"
                                    accept=".mp3,.wav,.ogg,.m4a,.webm,audio/*"
                                >

                                @error('audio')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text">
                                    Use este campo para atividades de
                                    <strong>Conversa</strong> ou
                                    <strong>Leitura</strong>.
                                    <br>
                                    Formatos aceitos:
                                    MP3, WAV, OGG, M4A e WEBM.
                                    <br>
                                    Tamanho máximo: 20 MB.
                                </div>

                                <audio
                                    id="previewAudioAtividade"
                                    controls
                                    class="w-100 mt-3"
                                    style="display:none;"
                                ></audio>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================= --}}
            {{-- QUESTÕES                       --}}
            {{-- ============================= --}}

            <div id="questoes">

                <div
                    class="card shadow-sm mb-3 questao-card"
                    data-index="0"
                >

                    <div
                        class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1a1a2e; color:#fff;"
                    >

                        <span>
                            ❓ Questão 1
                        </span>

                        <button
                            type="button"
                            class="btn btn-sm btn-danger remover-questao"
                        >
                            Remover
                        </button>

                    </div>

                    <div class="card-body">

                        {{-- ============================= --}}
                        {{-- ENUNCIADO                      --}}
                        {{-- ============================= --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Enunciado
                            </label>

                            <textarea
                                name="enunciado[]"
                                class="form-control"
                                rows="2"
                                required
                            >{{ old('enunciado.0') }}</textarea>

                        </div>


                        {{-- ============================= --}}
                        {{-- TIPO DA QUESTÃO                --}}
                        {{-- ============================= --}}

                        <div class="mb-3">

                            <label class="form-label">
                                Tipo da Questão
                            </label>

                            <select
                                name="tipo_questao[]"
                                class="form-select tipo-questao"
                            >

                                <option value="multipla_escolha">
                                    Múltipla Escolha
                                </option>

                                <option value="texto">
                                    Texto Dissertativo
                                </option>

                                <option
                                    value="audio"
                                    class="opcao-audio"
                                    hidden
                                >
                                    🎙️ Resposta em Áudio
                                </option>

                            </select>

                            <div class="form-text texto-ajuda-audio">
                                Na questão de áudio, o aluno deverá
                                gravar sua resposta.
                            </div>

                        </div>


                        {{-- ============================= --}}
                        {{-- OPÇÕES DA MÚLTIPLA ESCOLHA     --}}
                        {{-- ============================= --}}

                        <div class="opcoes-multipla">

                            <div class="row g-2">

                                {{-- OPÇÃO A --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Opção A
                                    </label>

                                    <input
                                        type="text"
                                        name="opcao_a[]"
                                        class="form-control"
                                    >

                                </div>

                                {{-- OPÇÃO B --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Opção B
                                    </label>

                                    <input
                                        type="text"
                                        name="opcao_b[]"
                                        class="form-control"
                                    >

                                </div>

                                {{-- OPÇÃO C --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Opção C
                                    </label>

                                    <input
                                        type="text"
                                        name="opcao_c[]"
                                        class="form-control"
                                    >

                                </div>

                                {{-- OPÇÃO D --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Opção D
                                    </label>

                                    <input
                                        type="text"
                                        name="opcao_d[]"
                                        class="form-control"
                                    >

                                </div>

                                {{-- RESPOSTA CORRETA --}}
                                <div class="col-md-3">

                                    <label class="form-label">
                                        Resposta Correta
                                    </label>

                                    <select
                                        name="resposta_correta[]"
                                        class="form-select"
                                    >
                                        <option value="A">
                                            A
                                        </option>

                                        <option value="B">
                                            B
                                        </option>

                                        <option value="C">
                                            C
                                        </option>

                                        <option value="D">
                                            D
                                        </option>
                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ============================= --}}
            {{-- ADICIONAR QUESTÃO              --}}
            {{-- ============================= --}}

            <button
                type="button"
                id="addQuestao"
                class="btn btn-outline-primary mb-4"
            >
                <i class="fas fa-plus me-1"></i>
                Adicionar Questão
            </button>


            {{-- ============================= --}}
            {{-- BOTÕES                         --}}
            {{-- ============================= --}}

            <div class="d-flex gap-2 mb-5">

                <button
                    type="submit"
                    class="btn btn-success px-5"
                >
                    <i class="fas fa-save me-1"></i>
                    Salvar Atividade
                </button>

                <a
                    href="{{ route('admin.atividades.index') }}"
                    class="btn btn-outline-secondary px-4"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>
</div>


<script>

let questaoIndex = 1;


/*
|--------------------------------------------------------------------------
| ELEMENTOS PRINCIPAIS
|--------------------------------------------------------------------------
*/

const tipoAtividade =
    document.getElementById('tipoAtividade');

const campoAudioAtividade =
    document.getElementById('campoAudioAtividade');

const audioAtividade =
    document.getElementById('audioAtividade');

const previewAudioAtividade =
    document.getElementById('previewAudioAtividade');


/*
|--------------------------------------------------------------------------
| PREVIEW DO ÁUDIO
|--------------------------------------------------------------------------
*/

if (audioAtividade) {

    audioAtividade.addEventListener(
        'change',
        function () {

            if (!previewAudioAtividade) {
                return;
            }

            if (previewAudioAtividade.src) {
                URL.revokeObjectURL(
                    previewAudioAtividade.src
                );
            }

            if (this.files.length) {

                previewAudioAtividade.src =
                    URL.createObjectURL(
                        this.files[0]
                    );

                previewAudioAtividade.style.display =
                    'block';

            } else {

                previewAudioAtividade.removeAttribute(
                    'src'
                );

                previewAudioAtividade.style.display =
                    'none';
            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLAR ÁUDIO DA ATIVIDADE
|--------------------------------------------------------------------------
|
| Conversa:
|   possui áudio para o conteúdo.
|
| Leitura:
|   pode possuir áudio adicional.
|
| Conjugação:
|   não utiliza áudio da atividade.
|
| Pronúncia:
|   não utiliza áudio da atividade.
|
*/

function atualizarAudioAtividade() {

    if (
        !tipoAtividade ||
        !campoAudioAtividade
    ) {
        return;
    }

    const tipo =
        tipoAtividade.value;

    const mostrarAudio =
        tipo === 'conversa' ||
        tipo === 'leitura';

    campoAudioAtividade.style.display =
        mostrarAudio
            ? 'block'
            : 'none';

    /*
     * Quando não for necessário áudio,
     * limpamos o campo para evitar
     * enviar um arquivo desnecessário.
     */

    if (
        !mostrarAudio &&
        audioAtividade
    ) {

        audioAtividade.value = '';

        if (previewAudioAtividade) {

            previewAudioAtividade.removeAttribute(
                'src'
            );

            previewAudioAtividade.style.display =
                'none';
        }
    }

}


/*
|--------------------------------------------------------------------------
| ALTERAR ESTILO DA ATIVIDADE
|--------------------------------------------------------------------------
*/

if (tipoAtividade) {

    tipoAtividade.addEventListener(
        'change',
        function () {

            atualizarAudioAtividade();

            atualizarTiposDasQuestoes();

        }
    );

}


/*
|--------------------------------------------------------------------------
| ATUALIZAR TIPOS DE QUESTÃO
|--------------------------------------------------------------------------
|
| Pronúncia:
|   permite:
|   - múltipla escolha
|   - texto
|   - áudio
|
| Outros estilos:
|   - múltipla escolha
|   - texto
|
*/

function atualizarTiposDasQuestoes() {

    const tipo =
        tipoAtividade
            ? tipoAtividade.value
            : '';

    const permitirAudio =
        tipo === 'pronuncia';

    document
        .querySelectorAll('.questao-card')
        .forEach(function(card) {

            const select =
                card.querySelector(
                    '.tipo-questao'
                );

            if (!select) {
                return;
            }

            const opcaoAudio =
                select.querySelector(
                    '.opcao-audio'
                );

            if (!opcaoAudio) {
                return;
            }

            /*
             * Mostra ou esconde a opção
             * de resposta em áudio.
             */

            opcaoAudio.hidden =
                !permitirAudio;


            /*
             * Se saiu de Pronúncia e a
             * questão estava como áudio,
             * volta para texto.
             */

            if (
                !permitirAudio &&
                select.value === 'audio'
            ) {

                select.value =
                    'texto';

            }

            atualizarOpcoesQuestao(card);

        });

}


/*
|--------------------------------------------------------------------------
| ATUALIZAR VISUAL DA QUESTÃO
|--------------------------------------------------------------------------
*/

function atualizarOpcoesQuestao(card) {

    const select =
        card.querySelector(
            '.tipo-questao'
        );

    const opcoes =
        card.querySelector(
            '.opcoes-multipla'
        );

    if (
        !select ||
        !opcoes
    ) {
        return;
    }


    /*
     * Só mostra alternativas quando
     * a questão é múltipla escolha.
     */

    opcoes.style.display =
        select.value === 'multipla_escolha'
            ? 'block'
            : 'none';

}


/*
|--------------------------------------------------------------------------
| ADICIONAR QUESTÃO
|--------------------------------------------------------------------------
*/

document
    .getElementById('addQuestao')
    .addEventListener(
        'click',
        function() {

            const original =
                document.querySelector(
                    '.questao-card'
                );

            if (!original) {
                return;
            }

            const template =
                original.cloneNode(true);


            /*
             * Atualiza o índice.
             */

            template.setAttribute(
                'data-index',
                questaoIndex
            );


            /*
             * Atualiza o título.
             */

            const titulo =
                template.querySelector(
                    '.card-header span'
                );

            if (titulo) {

                titulo.textContent =
                    '❓ Questão ' +
                    (questaoIndex + 1);

            }


            /*
             * Limpa inputs e textareas.
             */

            template
                .querySelectorAll(
                    'input, textarea'
                )
                .forEach(function(el) {

                    el.value = '';

                });


            /*
             * Reseta selects.
             */

            template
                .querySelectorAll('select')
                .forEach(function(el) {

                    if (
                        el.classList.contains(
                            'tipo-questao'
                        )
                    ) {

                        el.value =
                            'multipla_escolha';

                    } else {

                        el.value = '';

                    }

                });


            /*
             * Garante que o botão de remover
             * funcione na nova questão.
             */

            document
                .getElementById('questoes')
                .appendChild(template);


            questaoIndex++;


            /*
             * Reativa os eventos.
             */

            bindEvents();


            /*
             * Atualiza a disponibilidade
             * da opção de áudio.
             */

            atualizarTiposDasQuestoes();

        }
    );


/*
|--------------------------------------------------------------------------
| EVENTOS DAS QUESTÕES
|--------------------------------------------------------------------------
*/

function bindEvents() {

    /*
     * Remover questão
     */

    document
        .querySelectorAll('.remover-questao')
        .forEach(function(btn) {

            btn.onclick = function() {

                const quantidade =
                    document
                        .querySelectorAll(
                            '.questao-card'
                        )
                        .length;

                if (quantidade > 1) {

                    this
                        .closest('.questao-card')
                        .remove();

                }

            };

        });


    /*
     * Alterar tipo da questão
     */

    document
        .querySelectorAll('.tipo-questao')
        .forEach(function(select) {

            select.onchange = function() {

                const card =
                    this.closest(
                        '.questao-card'
                    );

                atualizarOpcoesQuestao(
                    card
                );

            };

        });

}


/*
|--------------------------------------------------------------------------
| FILTRAR MÓDULOS PELO CURSO
|--------------------------------------------------------------------------
*/

(function () {

    const cursoSelect =
        document.querySelector(
            'select[name="id_curso"]'
        );

    const moduloSelect =
        document.getElementById(
            'selectModulo'
        );


    if (
        !cursoSelect ||
        !moduloSelect
    ) {

        return;

    }


    function filtrarModulos() {

        const idCurso =
            cursoSelect.value;


        [
            ...moduloSelect.options
        ].forEach(function(opt) {

            if (!opt.value) {
                return;
            }

            opt.hidden =
                idCurso !== '' &&
                opt.dataset.curso !== idCurso;

        });


        if (
            moduloSelect.selectedOptions[0]?.hidden
        ) {

            moduloSelect.value = '';

        }

    }


    cursoSelect.addEventListener(
        'change',
        filtrarModulos
    );


    filtrarModulos();

})();


/*
|--------------------------------------------------------------------------
| INICIALIZAÇÃO
|--------------------------------------------------------------------------
*/

atualizarAudioAtividade();

atualizarTiposDasQuestoes();

bindEvents();

</script>

@endsection