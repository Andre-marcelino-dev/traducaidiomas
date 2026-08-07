```blade
@php

$chatbotUserName = '';
$chatbotDataUrl = '';
$chatbotMessageUrl = '';
$chatbotHistoricoUrl = '';

if (auth('admin')->check()) {

$chatbotUserName = auth('admin')->user()->nome_professor ?? '';

$chatbotMessageUrl = route('admin.professor.chatbot.mensagem');

$chatbotHistoricoUrl = route('admin.professor.chatbot.historico');

} elseif (auth('aluno')->check()) {

$chatbotUserName = auth('aluno')->user()->nome_aluno ?? '';

$chatbotDataUrl = route('aluno.chatbot.dados');

$chatbotMessageUrl = route('aluno.chatbot.mensagem');

$chatbotHistoricoUrl = route('aluno.chatbot.historico');

}

$chatbotFirstName = trim(explode(' ', $chatbotUserName)[0] ?? '');

@endphp


<script>
    window.chatbotHistoricoUrl = @json($chatbotHistoricoUrl);
    window.chatbotMessageUrl = @json($chatbotMessageUrl);
    window.chatbotDataUrl = @json($chatbotDataUrl);
    window.chatbotFirstName = @json($chatbotFirstName);
</script>


<div
    class="traduca-chatbot"
    data-chatbot
    data-chatbot-data-url="{{ $chatbotDataUrl }}"
    data-chatbot-message-url="{{ $chatbotMessageUrl }}"
    data-user-name="{{ $chatbotFirstName }}"
    data-csrf-token="{{ csrf_token() }}">


    <!-- BOTÃO FLUTUANTE DO CHAT -->

    <button
        type="button"
        class="traduca-chatbot__launcher"
        data-chatbot-toggle
        aria-label="Abrir Traduca AI">
        <i class="bi bi-robot"></i>
    </button>



    <!-- PAINEL DO CHAT -->

    <section
        class="traduca-chatbot__panel"
        data-chatbot-panel
        aria-hidden="true">


        <header class="traduca-chatbot__header">

            <div class="traduca-chatbot__avatar">
                AI
            </div>


            <div class="traduca-chatbot__header-copy">

                <div class="traduca-chatbot__title-row">

                    <h2>
                        Traduca AI
                    </h2>

                    <span>
                        PRO
                    </span>

                </div>


                <p>
                    <span></span>
                    Online - Assistente virtual da TraducaIdiomas
                </p>

            </div>



            <div class="traduca-chatbot__header-actions">


                <button
                    class="traduca-chatbot__icon-button"
                    type="button"
                    data-chatbot-reset
                    title="Nova conversa">

                    <i class="bi bi-stars"></i>

                </button>



                <button
                    class="traduca-chatbot__icon-button"
                    type="button"
                    data-chatbot-close
                    title="Fechar">

                    <i class="bi bi-chevron-down"></i>

                </button>


            </div>


        </header>





        <div class="traduca-chatbot__body">


            <div
                class="traduca-chatbot__initial"
                data-chatbot-initial>


                <div class="traduca-chatbot__hero">


                    <div class="traduca-chatbot__avatar traduca-chatbot__avatar--large">

                        AI

                    </div>



                    <h3 data-chatbot-greeting>

                        Ola!

                    </h3>



                    <p>

                        Sou a <strong>Traduca AI</strong>,
                        sua assistente virtual.
                        Como posso ajudar com suas aulas hoje?

                    </p>


                </div>





                <div class="traduca-chatbot__quick-area">


                    <p>
                        O que voce precisa?
                    </p>



                    <div
                        class="traduca-chatbot__quick-grid"
                        data-chatbot-quick-actions>

                    </div>


                </div>



            </div>





            <div
                class="traduca-chatbot__messages"
                data-chatbot-messages
                hidden>

            </div>



        </div>





        <div
            class="traduca-chatbot__suggestions"
            data-chatbot-suggestions
            hidden>

        </div>







        <form
            class="traduca-chatbot__input"
            data-chatbot-form>


            <div class="traduca-chatbot__field">


                <button
                    type="button"
                    aria-label="Anexar arquivo">

                    <i class="bi bi-paperclip"></i>

                </button>





                <input

                    type="text"

                    data-chatbot-input

                    placeholder="Digite sua mensagem..."

                    autocomplete="off">





                <button

                    type="submit"

                    data-chatbot-send

                    aria-label="Enviar mensagem">

                    <i class="bi bi-send-fill"></i>

                </button>



            </div>





            <p>

                Traduca AI - Assistente de IA Educacional

            </p>



        </form>





    </section>


</div>
```