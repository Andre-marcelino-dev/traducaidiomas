@php
    $chatbotUserName = '';
    $chatbotDataUrl = '';
    $chatbotMessageUrl = '';

    if (auth('admin')->check()) {

        $chatbotUserName = auth('admin')->user()->nome_professor ?? '';

        $chatbotDataUrl = '';
        $chatbotMessageUrl = route('admin.professor.chatbot.mensagem');


    } elseif (auth('aluno')->check()) {

        $chatbotUserName = auth('aluno')->user()->nome_aluno ?? '';

        $chatbotDataUrl = route('aluno.chatbot.dados');
        $chatbotMessageUrl = route('aluno.chatbot.mensagem');


    } else {

    $chatbotMessageUrl = route('chatbot.mensagem');

}

    $chatbotFirstName = trim(explode(' ', $chatbotUserName)[0] ?? '');

@endphp
<!--
Aluno: {{ auth('aluno')->check() ? 'SIM' : 'NAO' }}
Admin: {{ auth('admin')->check() ? 'SIM' : 'NAO' }}
-->




<div
    class="traduca-chatbot"
    data-chatbot
    data-user-name="{{ $chatbotFirstName }}"
    data-chatbot-data-url="{{ $chatbotDataUrl }}"
    data-chatbot-message-url="{{ $chatbotMessageUrl }}"
    data-csrf-token="{{ csrf_token() }}"
>
    <button class="traduca-chatbot__launcher" type="button" data-chatbot-toggle aria-expanded="false" aria-label="Abrir Traduca AI">
        <span class="traduca-chatbot__launcher-icon">
            <i class="bi bi-chat-dots-fill"></i>
        </span>
        <span class="traduca-chatbot__launcher-text">Traduca AI</span>
    </button>

    <section class="traduca-chatbot__panel" data-chatbot-panel aria-hidden="true">
        <header class="traduca-chatbot__header">
            <div class="traduca-chatbot__avatar" aria-hidden="true">AI</div>
            <div class="traduca-chatbot__header-copy">
                <div class="traduca-chatbot__title-row">
                    <h2>Traduca AI</h2>
                    <span>PRO</span>
                </div>
                <p><span></span> Online - Assistente virtual da TraducaIdiomas</p>
            </div>
            <div class="traduca-chatbot__header-actions">
                <button class="traduca-chatbot__icon-button" type="button" data-chatbot-reset title="Nova conversa" aria-label="Nova conversa">
                    <i class="bi bi-stars"></i>
                </button>
                <button class="traduca-chatbot__icon-button" type="button" data-chatbot-close title="Fechar" aria-label="Fechar chatbot">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        </header>

        <div class="traduca-chatbot__body">
            <div class="traduca-chatbot__initial" data-chatbot-initial>
                <div class="traduca-chatbot__hero">
                    <div class="traduca-chatbot__avatar traduca-chatbot__avatar--large" aria-hidden="true">AI</div>
                    <h3 data-chatbot-greeting>Ola!</h3>
                    <p>Sou a <strong>Traduca AI</strong>, sua assistente virtual. Como posso ajudar com suas aulas hoje?</p>
                </div>

                <div class="traduca-chatbot__quick-area">
                    <p>O que voce precisa?</p>
                    <div class="traduca-chatbot__quick-grid" data-chatbot-quick-actions></div>
                </div>
            </div>

            <div class="traduca-chatbot__messages" data-chatbot-messages hidden></div>
        </div>

        <div class="traduca-chatbot__suggestions" data-chatbot-suggestions hidden></div>

        <form class="traduca-chatbot__input" data-chatbot-form>
            <div class="traduca-chatbot__field">
                <button type="button" aria-label="Anexar arquivo">
                    <i class="bi bi-paperclip"></i>
                </button>
                <input type="text" data-chatbot-input placeholder="Digite sua mensagem..." autocomplete="off">
                <button type="submit" data-chatbot-send aria-label="Enviar mensagem">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <p>Traduca AI - Assistente de IA Educacional</p>
        </form>
    </section>
</div>
