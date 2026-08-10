document.addEventListener('DOMContentLoaded', function () {
    console.log('CHATBOT JS CARREGADO');

    const widget = document.querySelector('[data-chatbot]');

    if (!widget) {
        console.error('CHATBOT: elemento [data-chatbot] NÃO encontrado');
        return;
    }

    console.log('CHATBOT: widget encontrado', widget);

    const toggleButton = widget.querySelector('[data-chatbot-toggle]');
    const closeButton = widget.querySelector('[data-chatbot-close]');
    const panel = widget.querySelector('[data-chatbot-panel]');

    console.log('CHATBOT: botão abrir:', toggleButton);
    console.log('CHATBOT: botão fechar:', closeButton);
    console.log('CHATBOT: painel:', panel);

    if (!toggleButton) {
        console.error('CHATBOT: botão [data-chatbot-toggle] não encontrado');
        return;
    }

    if (!panel) {
        console.error('CHATBOT: painel [data-chatbot-panel] não encontrado');
        return;
    }

    toggleButton.addEventListener('click', function () {
        console.log('CHATBOT: botão clicado');

        panel.classList.toggle('is-open');

        const aberto = panel.classList.contains('is-open');

        panel.setAttribute('aria-hidden', aberto ? 'false' : 'true');

        console.log('CHATBOT: painel aberto?', aberto);
    });

    if (closeButton) {
        closeButton.addEventListener('click', function () {
            panel.classList.remove('is-open');
            panel.setAttribute('aria-hidden', 'true');

            console.log('CHATBOT: painel fechado');
        });
    }
});


(function () {

    'use strict';


    function initChatbot(widget) {

        if (!widget) {
            return;
        }


        console.log('INICIO CHATBOT');


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÕES
        |--------------------------------------------------------------------------
        */

        const apiUrl =
            widget.dataset.chatbotDataUrl || '';

        const apiChatUrl =
            widget.dataset.chatbotMessageUrl || '';

        const csrfToken =
            widget.dataset.csrfToken || '';

        const userName =
            (widget.dataset.userName || '').trim();

        const perfil =
            widget.dataset.chatbotPerfil || 'visitante';


        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const toggleButton =
            widget.querySelector(
                '[data-chatbot-toggle]'
            );

        const closeButton =
            widget.querySelector(
                '[data-chatbot-close]'
            );

        const resetButton =
            widget.querySelector(
                '[data-chatbot-reset]'
            );

        const panel =
            widget.querySelector(
                '[data-chatbot-panel]'
            );

        const initial =
            widget.querySelector(
                '[data-chatbot-initial]'
            );

        const messagesContainer =
            widget.querySelector(
                '[data-chatbot-messages]'
            );

        const quickActions =
            widget.querySelector(
                '[data-chatbot-quick-actions]'
            );

        const greeting =
            widget.querySelector(
                '[data-chatbot-greeting]'
            );

        const description =
            widget.querySelector(
                '[data-chatbot-description]'
            );

        const form =
            widget.querySelector(
                '[data-chatbot-form]'
            );

        const input =
            widget.querySelector(
                '[data-chatbot-input]'
            );


        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        const storageKey =
            'traduca_ai_chat_' + perfil;


        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        let isOpen = false;

        let isSending = false;


        /*
        |--------------------------------------------------------------------------
        | PERFIL
        |--------------------------------------------------------------------------
        */

        function iconePara(texto) {

            const t = String(texto).toLowerCase();

            if (t.indexOf('aula') !== -1) {
                return 'bi-calendar-week';
            }

            if (t.indexOf('material') !== -1) {
                return 'bi-journal-bookmark';
            }

            if (t.indexOf('horário') !== -1 ||
                t.indexOf('horario') !== -1) {
                return 'bi-clock';
            }

            if (t.indexOf('planejamento') !== -1) {
                return 'bi-clipboard-check';
            }

            if (t.indexOf('estudo') !== -1) {
                return 'bi-lightbulb';
            }

            if (t.indexOf('idioma') !== -1) {
                return 'bi-translate';
            }

            if (t.indexOf('inglês') !== -1 ||
                t.indexOf('ingles') !== -1) {
                return 'bi-question-circle';
            }

            if (t.indexOf('traduca') !== -1) {
                return 'bi-building';
            }

            if (t.indexOf('dica') !== -1) {
                return 'bi-lightbulb';
            }

            return 'bi-chat-dots';
        }


        function configurarPerfil() {

            if (perfil === 'professor') {

                const nome =
                    userName || 'Professor';

                greeting.textContent =
                    'Olá, ' + nome + '!';

                description.innerHTML =
                    'Sou a <strong>Traduca AI</strong>, ' +
                    'sua assistente para aulas, materiais e planejamento.';

                criarAcoes([
                    'Minhas aulas',
                    'Meus materiais',
                    'Planejamento de aula',
                    'Ajuda com idiomas'
                ]);

                return;
            }


            if (perfil === 'aluno') {

                const nome =
                    userName || 'Aluno';

                greeting.textContent =
                    'Olá, ' + nome + '!';

                description.innerHTML =
                    'Sou a <strong>Traduca AI</strong>, ' +
                    'sua assistente para aulas, horários e estudos.';

                criarAcoes([
                    'Minhas aulas',
                    'Meus materiais',
                    'Horários',
                    'Ajuda nos estudos'
                ]);

                return;
            }


            greeting.textContent =
                'Olá!';

            description.innerHTML =
                'Sou a <strong>Traduca AI</strong>, ' +
                'assistente virtual da Traduca Idiomas.';

            criarAcoes([
                'Como aprender inglês?',
                'Dicas para estudar idiomas',
                'Quero conhecer a Traduca',
                'Quais idiomas posso estudar?'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | AÇÕES RÁPIDAS
        |--------------------------------------------------------------------------
        */

        function criarAcoes(acoes) {

            if (!quickActions) {
                return;
            }

            quickActions.innerHTML = '';


            acoes.forEach(function (texto) {

                const button =
                    document.createElement('button');

                button.type = 'button';

                button.className =
                    'traduca-chatbot__quick-action';


                const icone =
                    document.createElement('i');

                icone.className =
                    'bi ' + iconePara(texto);

                icone.setAttribute(
                    'aria-hidden',
                    'true'
                );


                const span =
                    document.createElement('span');

                span.textContent =
                    texto;


                button.appendChild(icone);

                button.appendChild(span);


                button.addEventListener(
                    'click',
                    function () {

                        enviarMensagem(texto);

                    }
                );


                quickActions.appendChild(button);

            });
        }


        /*
        |--------------------------------------------------------------------------
        | ABRIR / FECHAR
        |--------------------------------------------------------------------------
        */

        function setOpen(value) {

            isOpen = value;

            if (!panel) {
                return;
            }


            if (isOpen) {

                panel.classList.add('is-open');

                panel.setAttribute(
                    'aria-hidden',
                    'false'
                );

                carregarHistorico();

                setTimeout(function () {

                    if (input) {
                        input.focus();
                    }

                }, 100);

            } else {

                panel.classList.remove('is-open');

                panel.setAttribute(
                    'aria-hidden',
                    'true'
                );

            }
        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        function obterHistorico() {

            try {

                const dados =
                    localStorage.getItem(
                        storageKey
                    );

                if (!dados) {
                    return [];
                }

                return JSON.parse(dados);

            } catch (error) {

                console.warn(
                    'Não foi possível carregar histórico.',
                    error
                );

                return [];
            }
        }


        function salvarHistorico(historico) {

            try {

                localStorage.setItem(
                    storageKey,
                    JSON.stringify(historico)
                );

            } catch (error) {

                console.warn(
                    'Não foi possível salvar histórico.',
                    error
                );
            }
        }


        function carregarHistorico() {

            const historico =
                obterHistorico();


            if (!historico.length) {

                mostrarInicial();

                return;
            }


            esconderInicial();

            messagesContainer.innerHTML = '';


            historico.forEach(function (item) {

                adicionarMensagemVisual(
                    item.text,
                    item.sender
                );

            });


            messagesContainer.hidden = false;

            scrollParaFim();
        }


        function limparHistorico() {

            localStorage.removeItem(
                storageKey
            );

            messagesContainer.innerHTML = '';

            mostrarInicial();
        }


        /*
        |--------------------------------------------------------------------------
        | INICIAL / MENSAGENS
        |--------------------------------------------------------------------------
        */

        function mostrarInicial() {

            if (initial) {
                initial.hidden = false;
            }

            if (messagesContainer) {
                messagesContainer.hidden = true;
            }
        }


        function esconderInicial() {

            if (initial) {
                initial.hidden = true;
            }

            if (messagesContainer) {
                messagesContainer.hidden = false;
            }
        }


        function adicionarMensagemVisual(
            texto,
            sender,
            opcoes
        ) {

            esconderInicial();


            const message =
                document.createElement('div');

            message.className =
                'traduca-chatbot__message ' +
                (
                    sender === 'user'
                        ? 'is-user'
                        : ''
                );


            const bubble =
                document.createElement('div');

            bubble.className =
                'traduca-chatbot__bubble';


            /*
            | Não usamos innerHTML para mensagens
            | vindas da IA.
            */

            bubble.textContent =
                texto;


            message.appendChild(
                bubble
            );


            // ★ AVALIAÇÃO DA RESPOSTA (opção 7)
            if (sender === 'ai' && opcoes && opcoes.avaliacao !== false) {

                const avaliacao =
                    document.createElement('div');

                avaliacao.className =
                    'traduca-chatbot__avaliacao';


                const btnGostei =
                    document.createElement('button');

                btnGostei.type = 'button';

                btnGostei.className =
                    'traduca-chatbot__avaliacao-btn';

                btnGostei.innerHTML = '👍';


                const btnNaoGostei =
                    document.createElement('button');

                btnNaoGostei.type = 'button';

                btnNaoGostei.className =
                    'traduca-chatbot__avaliacao-btn';

                btnNaoGostei.innerHTML = '👎';


                btnGostei.addEventListener(
                    'click',
                    function () {

                        avaliacao.classList.add(
                            'is-gostei'
                        );

                        btnGostei.disabled = true;

                        btnNaoGostei.disabled = true;

                    }
                );


                btnNaoGostei.addEventListener(
                    'click',
                    function () {

                        avaliacao.classList.add(
                            'is-nao-gostei'
                        );

                        btnGostei.disabled = true;

                        btnNaoGostei.disabled = true;

                    }
                );


                avaliacao.appendChild(
                    btnGostei
                );

                avaliacao.appendChild(
                    btnNaoGostei
                );


                message.appendChild(
                    avaliacao
                );

            }


            // ★ CARDS DE RESPOSTA (opção 4)
            if (
                sender === 'ai' &&
                opcoes &&
                opcoes.card
            ) {

                const card = opcoes.card;

                const cardEl =
                    document.createElement('div');

                cardEl.className =
                    'traduca-chatbot__card ' +
                    'traduca-chatbot__card--' +
                    card.type;


                const cardTitle =
                    document.createElement('div');

                cardTitle.className =
                    'traduca-chatbot__card-title';

                cardTitle.textContent =
                    card.title || '';


                cardEl.appendChild(
                    cardTitle
                );


                if (
                    card.type === 'schedule' &&
                    Array.isArray(card.items)
                ) {

                    const list =
                        document.createElement('div');

                    list.className =
                        'traduca-chatbot__card-list';


                    card.items.forEach(
                        function (item) {

                            const itemEl =
                                document.createElement('div');

                            itemEl.className =
                                'traduca-chatbot__card-item';


                            const itemMain =
                                document.createElement('strong');

                            itemMain.textContent =
                                item.titulo || '';


                            const itemMeta =
                                document.createElement('span');

                            itemMeta.textContent =
                                (item.data || '') +
                                ' às ' +
                                (item.hora || '') +
                                ' - ' +
                                (item.professor || '');


                            itemEl.appendChild(
                                itemMain
                            );

                            itemEl.appendChild(
                                itemMeta
                            );


                            list.appendChild(
                                itemEl
                            );

                        }
                    );


                    cardEl.appendChild(
                        list
                    );

                } else if (
                    card.type === 'progress' &&
                    Array.isArray(card.items)
                ) {

                    const grid =
                        document.createElement('div');

                    grid.className =
                        'traduca-chatbot__card-grid';


                    card.items.forEach(
                        function (item) {

                            const itemEl =
                                document.createElement('div');

                            itemEl.className =
                                'traduca-chatbot__card-stat';


                            const val =
                                document.createElement('strong');

                            val.textContent =
                                item.value || '0';


                            const lbl =
                                document.createElement('span');

                            lbl.textContent =
                                item.label || '';


                            itemEl.appendChild(
                                val
                            );

                            itemEl.appendChild(
                                lbl
                            );


                            grid.appendChild(
                                itemEl
                            );

                        }
                    );


                    cardEl.appendChild(
                        grid
                    );

                } else if (
                    (card.type === 'professors' ||
                     card.type === 'prices') &&
                    Array.isArray(card.items)
                ) {

                    const list =
                        document.createElement('div');

                    list.className =
                        'traduca-chatbot__card-list';


                    card.items.forEach(
                        function (item) {

                            const itemEl =
                                document.createElement('div');

                            itemEl.className =
                                'traduca-chatbot__card-item';


                            const itemMain =
                                document.createElement('strong');

                            if (card.type === 'professors') {

                                itemMain.textContent =
                                    item.nome || '';

                            } else {

                                itemMain.textContent =
                                    item.titulo || '';

                            }


                            const itemMeta =
                                document.createElement('span');

                            if (card.type === 'professors') {

                                itemMeta.textContent =
                                    (item.especialidade || '') +
                                    ' - ' +
                                    (item.curso || '') +
                                    ' (' +
                                    (item.nivel || '') +
                                    ')';

                            } else {

                                itemMeta.textContent =
                                    'R$ ' +
                                    (item.preco || '') +
                                    ' - ' +
                                    (item.idioma || '');

                            }


                            itemEl.appendChild(
                                itemMain
                            );

                            itemEl.appendChild(
                                itemMeta
                            );


                            list.appendChild(
                                itemEl
                            );

                        }
                    );


                    cardEl.appendChild(
                        list
                    );

                }


                message.appendChild(
                    cardEl
                );

            }


            messagesContainer.appendChild(
                message
            );


            messagesContainer.hidden =
                false;


            scrollParaFim();
        }


        // ★ SUGESTÕES DINÂMICAS (opção 3)
        function mostrarSugestoes(sugestoes) {

            const container = widget.querySelector(
                '[data-chatbot-suggestions]'
            );

            if (!container) {
                return;
            }

            container.innerHTML = '';

            if (
                !sugestoes ||
                !sugestoes.length
            ) {

                container.hidden = true;

                return;
            }


            sugestoes.forEach(
                function (sugestao) {

                    const btn =
                        document.createElement('button');

                    btn.type = 'button';

                    btn.textContent =
                        sugestao;


                    btn.addEventListener(
                        'click',
                        function () {

                            enviarMensagem(
                                sugestao
                            );

                        }
                    );


                    container.appendChild(
                        btn
                    );

                }
            );


            container.hidden = false;
        }


        function adicionarMensagem(
            texto,
            sender
        ) {

            adicionarMensagemVisual(
                texto,
                sender
            );


            const historico =
                obterHistorico();


            historico.push({

                text: texto,

                sender: sender,

                timestamp:
                    Date.now()

            });


            /*
            | Limita o histórico local
            | às últimas 50 mensagens.
            */

            if (historico.length > 50) {

                historico.splice(
                    0,
                    historico.length - 50
                );

            }


            salvarHistorico(
                historico
            );
        }


        function scrollParaFim() {

            if (!messagesContainer) {
                return;
            }

            messagesContainer.scrollTop =
                messagesContainer.scrollHeight;
        }


        /*
        |--------------------------------------------------------------------------
        | INDICADOR DE DIGITAÇÃO
        |--------------------------------------------------------------------------
        */

        function mostrarDigitando() {

            removerDigitando();


            const element =
                document.createElement('div');

            element.className =
                'traduca-chatbot__message ' +
                'traduca-chatbot__typing';


            element.innerHTML =
                '<span></span>' +
                '<span></span>' +
                '<span></span>';


            messagesContainer.appendChild(
                element
            );


            messagesContainer.hidden =
                false;


            scrollParaFim();
        }


        function removerDigitando() {

            const typing =
                messagesContainer.querySelector(
                    '.traduca-chatbot__typing'
                );


            if (typing) {
                typing.remove();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ENVIAR MENSAGEM
        |--------------------------------------------------------------------------
        */

        async function enviarMensagem(
            mensagem
        ) {

            mensagem =
                String(mensagem || '').trim();


            if (!mensagem) {
                return;
            }


            if (isSending) {
                return;
            }


            if (!apiChatUrl) {

                adicionarMensagem(
                    'O serviço da Traduca AI não está configurado.',
                    'ai'
                );

                return;
            }


            isSending = true;


                adicionarMensagem(
                    mensagem,
                    'user'
                );


                mostrarSugestoes(
                    []
                );


            mostrarDigitando();


            if (input) {
                input.value = '';
            }


            try {

                const response =
                    await fetch(
                        apiChatUrl,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: JSON.stringify({

                                mensagem:
                                    mensagem,

                                mensagemDoUsuario:
                                    mensagem

                            })
                        }
                    );


                const data =
                    await response.json();


                removerDigitando();


                if (!response.ok) {

                    throw new Error(
                        data.text ||
                        'Erro ao enviar mensagem.'
                    );
                }


                const resposta =
                    data.text ||
                    'Não consegui responder agora.';


                adicionarMensagem(
                    resposta,
                    'ai',
                    {
                        card: data.card || null
                    }
                );


                mostrarSugestoes(
                    data.sugestoes || []
                );


            } catch (error) {

                removerDigitando();


                console.error(
                    'Erro chatbot:',
                    error
                );


                adicionarMensagem(
                    'Não consegui acessar a Traduca AI agora. Tente novamente em alguns instantes.',
                    'ai'
                );


                mostrarSugestoes(
                    []
                );

            } finally {

                isSending = false;


                if (input) {
                    input.focus();
                }

            }
        }


        /*
        |--------------------------------------------------------------------------
        | EVENTOS
        |--------------------------------------------------------------------------
        */

        if (toggleButton) {

            toggleButton.addEventListener(
                'click',
                function () {

                    setOpen(!isOpen);

                }
            );

        }


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function () {

                    setOpen(false);

                }
            );

        }


        if (resetButton) {

            resetButton.addEventListener(
                'click',
                function () {

                    limparHistorico();

                    if (input) {
                        input.focus();
                    }

                }
            );

        }


        if (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();


                    if (!input) {
                        return;
                    }


                    enviarMensagem(
                        input.value
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    isOpen
                ) {

                    setOpen(false);

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INICIALIZA
        |--------------------------------------------------------------------------
        */

        configurarPerfil();


        console.log(
            'Chatbot encontrado.',
            {
                perfil: perfil,
                apiUrl: apiUrl,
                apiChatUrl: apiChatUrl,
                userName: userName
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOM READY
    |--------------------------------------------------------------------------
    */

    function iniciar() {

        const widgets =
            document.querySelectorAll(
                '[data-chatbot]'
            );


        widgets.forEach(function (widget) {

            initChatbot(widget);

        });

    }


    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            iniciar
        );

    } else {

        iniciar();

    }

})();