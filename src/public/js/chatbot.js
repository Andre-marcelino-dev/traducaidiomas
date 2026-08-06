(function () {
    const actions = [
        { id: 'aulas', icon: 'bi-calendar-week', label: 'Minhas aulas' },
        { id: 'horarios', icon: 'bi-clock', label: 'Consultar horarios' },
        { id: 'materiais', icon: 'bi-journal-bookmark', label: 'Materiais de estudo' },
        { id: 'agendar', icon: 'bi-pencil-square', label: 'Agendar aula' },
        { id: 'professor', icon: 'bi-person-video3', label: 'Contatar professor' },
    ];

    const fallbackScheduleItems = [
        { day: 'Ter', date: '22/07', subject: 'Ingles Intermediario', teacher: 'Sarah Mitchell', time: '19:00', level: 'B1' },
        { day: 'Qui', date: '24/07', subject: 'Conversacao Avancada', teacher: 'James Carter', time: '18:00', level: 'B2' },
        { day: 'Sab', date: '26/07', subject: 'Gramatica & Writing', teacher: 'Sarah Mitchell', time: '10:00', level: 'B1' },
    ];

    const localResponsesFallback = {
        'Minhas aulas': {
            text: 'Encontrei suas proximas aulas. Voce tem **3 aulas agendadas** nesta semana. Posso ajudar a reagendar ou ver detalhes de alguma?',
            card: 'schedule',
        },
        'Consultar horarios': {
            text: 'Encontrei suas proximas aulas. Voce tem **3 aulas agendadas** nesta semana. Posso ajudar a reagendar ou ver detalhes de alguma?',
            card: 'schedule',
        },
        'Materiais de estudo': {
            text: 'Aqui estao seus **materiais mais recentes** disponiveis:\n\n- Unit 4: Business English - PDF\n- Listening Practice B1 - Audio\n- Grammar Workbook Ch.7 - PDF\n- Vocabulary Flashcards - 45 cards\n\nQuer que eu abra algum material agora?',
        },
        'Agendar aula': {
            text: 'Para agendar sua proxima aula, preciso de algumas informacoes:\n\n1. Qual idioma?\n2. Com qual professor?\n3. Qual horario prefere?\n\nResponda com suas preferencias e confirmo o agendamento.',
        },
        'Contatar professor': {
            text: 'Seus professores estao disponiveis para contato:\n\n**Sarah Mitchell** - Ingles, online agora.\n**James Carter** - Conversacao, disponivel apos 17h.\n\nQuer que eu envie uma mensagem para algum deles?',
        },
    };

    const fallbackErrorText = 'Entendido. Posso ajudar com aulas, horarios, materiais, agendamentos e contato com professores.';

    let chatbotData = null;

    function normalizeText(value) {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase();
    }

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function richText(value) {
        return escapeHtml(value)
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
    }

    function now() {
        return new Date().toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function formatDate(dateString) {
        if (!dateString) {
            return '';
        }

        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) {
            return String(dateString);
        }

        return date.toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
        });
    }

    function getScheduleItems() {
        if (!chatbotData || !Array.isArray(chatbotData.aulas) || chatbotData.aulas.length === 0) {
            return fallbackScheduleItems;
        }

        return chatbotData.aulas.map((item) => {
            const formattedDate = formatDate(item.date);

            return {
                day: item.day || formattedDate.split('/')[0] || '',
                date: formattedDate,
                subject: item.subject || 'Aula',
                teacher: item.teacher || 'Professor',
                time: item.time || '00:00',
                level: item.level || '',
            };
        });
    }

    function buildMaterialsText() {
        if (!chatbotData || !Array.isArray(chatbotData.materiais) || chatbotData.materiais.length === 0) {
            return localResponsesFallback['Materiais de estudo'].text;
        }

        const list = chatbotData.materiais
            .map((item) => `- ${item.title || 'Material'}${item.course ? ` (${item.course})` : ''}`)
            .join('\n');

        return `Aqui estao seus materiais mais recentes:\n\n${list}\n\nQuer que eu abra algum material agora?`;
    }

    async function fetchChatbotData(apiUrl) {
        if (!apiUrl) {
            chatbotData = { aulas: [], materiais: [] };
            return;
        }

        try {
            const response = await fetch(apiUrl, {
                credentials: 'same-origin',
                headers: { Accept: 'application/json' },
            });
if (!response.ok) {

    const errorData = await response.json();

    console.error('Erro API chatbot:', errorData);

    throw new Error(`HTTP ${response.status}`);
}

            chatbotData = await response.json();
        } catch (error) {
            chatbotData = { aulas: [], materiais: [] };
            console.warn('Chatbot usando fallback local:', error.message);
        }
    }

    function resolveLocalFallbackResponse(text) {
        const normalized = normalizeText(text);

        if (normalized.includes('aula') || normalized.includes('horario')) {
            const count = chatbotData?.aulas?.length || fallbackScheduleItems.length;
            return {
                text: `Encontrei suas proximas aulas. Voce tem **${count} aulas agendadas** nesta semana.`,
                card: 'schedule',
            };
        }

        if (normalized.includes('material')) {
            return {
                text: buildMaterialsText(),
                card: null,
            };
        }

        const match = Object.keys(localResponsesFallback).find((key) => normalized.includes(normalizeText(key)));
        return localResponsesFallback[text] || (match ? localResponsesFallback[match] : null);
    }

    function scheduleCard() {
        const rows = getScheduleItems().map((item) => {
            const dayNumber = item.date ? item.date.split('/')[0] : '';

            return `
                <div class="traduca-chatbot__schedule-item">
                    <div class="traduca-chatbot__schedule-date">
                        <small>${escapeHtml(item.day)}</small>
                        <strong>${escapeHtml(dayNumber)}</strong>
                    </div>
                    <div class="traduca-chatbot__schedule-copy">
                        <strong>${escapeHtml(item.subject)}</strong>
                        <span>Prof. ${escapeHtml(item.teacher)}</span>
                    </div>
                    <div class="traduca-chatbot__schedule-meta">
                        <strong>${escapeHtml(item.time)}</strong>
                        <span>${escapeHtml(item.level)}</span>
                    </div>
                </div>
            `;
        }).join('');

        return `<div class="traduca-chatbot__schedule">${rows}</div>`;
    }

    function initChatbot(widget) {
        if (!widget) {
            return;
        }

        const apiUrl = widget.dataset.chatbotDataUrl || '';
        const apiChatUrl = widget.dataset.chatbotMessageUrl || '';
        const csrfToken = widget.dataset.csrfToken || '';
        const userName = (widget.dataset.userName || '').trim();
        const toggleButton = widget.querySelector('[data-chatbot-toggle]');
        const closeButton = widget.querySelector('[data-chatbot-close]');
        const resetButton = widget.querySelector('[data-chatbot-reset]');
        const panel = widget.querySelector('[data-chatbot-panel]');
        const greeting = widget.querySelector('[data-chatbot-greeting]');
        const initial = widget.querySelector('[data-chatbot-initial]');
        const messages = widget.querySelector('[data-chatbot-messages]');
        const quickActions = widget.querySelector('[data-chatbot-quick-actions]');
        const suggestions = widget.querySelector('[data-chatbot-suggestions]');
        const form = widget.querySelector('[data-chatbot-form]');
        const input = widget.querySelector('[data-chatbot-input]');
        const sendButton = widget.querySelector('[data-chatbot-send]');
        let isTyping = false;
        let dataLoaded = false;

        async function loadChatbotData() {
            if (dataLoaded) {
                return;
            }

            dataLoaded = true;
            await fetchChatbotData(apiUrl);
        }

        if (greeting) {
            greeting.textContent = userName ? `Ola, ${userName}!` : 'Ola!';
        }

        function setOpen(open) {
            widget.classList.toggle('is-open', open);
            toggleButton?.setAttribute('aria-expanded', String(open));
            panel?.setAttribute('aria-hidden', String(!open));

            if (open && input) {
                window.setTimeout(() => input.focus(), 180);
                loadChatbotData();
            }
        }

        function setChatMode() {
            if (initial) {
                initial.hidden = true;
            }

            if (messages) {
                messages.hidden = false;
            }

            if (suggestions) {
                suggestions.hidden = false;
            }
        }

        function setInitialMode() {
            if (initial) {
                initial.hidden = false;
            }

            if (messages) {
                messages.hidden = true;
                messages.innerHTML = '';
            }

            if (suggestions) {
                suggestions.hidden = true;
            }

            if (input) {
                input.value = '';
                input.disabled = false;
            }

            if (sendButton) {
                sendButton.disabled = true;
            }

            isTyping = false;
        }

        function scrollToEnd() {
            if (messages) {
                messages.scrollTop = messages.scrollHeight;
            }
        }

        function addMessage(role, text, card) {
            if (!messages) {
                return;
            }

            const element = document.createElement('div');
            element.className = `traduca-chatbot__message is-${role}`;

            const avatar = role === 'ai'
                ? '<div class="traduca-chatbot__avatar" aria-hidden="true">AI</div>'
                : '';

            element.innerHTML = `
                ${avatar}
                <div class="traduca-chatbot__bubble-wrap">
                    <div class="traduca-chatbot__bubble">
                        ${richText(text)}
                        ${card === 'schedule' ? scheduleCard() : ''}
                    </div>
                    <span class="traduca-chatbot__time">${now()}</span>
                </div>
            `;

            messages.appendChild(element);
            scrollToEnd();
        }

        function addTyping() {
            if (!messages) {
                return;
            }

            const typing = document.createElement('div');
            typing.className = 'traduca-chatbot__message is-ai';
            typing.dataset.chatbotTyping = 'true';
            typing.innerHTML = `
                <div class="traduca-chatbot__avatar" aria-hidden="true">AI</div>
                <div class="traduca-chatbot__typing" aria-label="Traduca AI esta digitando">
                    <span></span><span></span><span></span>
                </div>
            `;
            messages.appendChild(typing);
            scrollToEnd();
        }

        function removeTyping() {
            messages?.querySelector('[data-chatbot-typing]')?.remove();
        }

        async function replyTo(text) {
            isTyping = true;

            if (input) {
                input.disabled = true;
            }

            if (sendButton) {
                sendButton.disabled = true;
            }

            addTyping();

            try {
                if (!apiChatUrl) {
                    throw new Error('Endpoint de mensagem nao disponivel.');
                }

                const response = await fetch(apiChatUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
               body: JSON.stringify({ 
    mensagem: text 
}),
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                removeTyping();
                addMessage('ai', data.text || fallbackErrorText, data.card || null);
            } catch (error) {
                console.warn('Chatbot usando fallback local:', error.message);
                removeTyping();

                const localResponse = resolveLocalFallbackResponse(text);
                addMessage(
                    'ai',
                    localResponse ? localResponse.text : fallbackErrorText,
                    localResponse ? localResponse.card : null
                );
            } finally {
                isTyping = false;

                if (input) {
                    input.disabled = false;
                    input.focus();
                }

                if (sendButton && input) {
                    sendButton.disabled = !input.value.trim();
                }
            }
        }

        async function send(text) {
            const cleanText = String(text || '').trim();

            if (!cleanText || isTyping) {
                return;
            }

            const firstInteraction = messages ? messages.hidden : false;
            setChatMode();

            if (firstInteraction) {
                addMessage(
                    'ai',
                    userName
                        ? `Ola, ${userName}! Sou a Traduca AI. Como posso ajudar com suas aulas hoje?`
                        : 'Ola! Sou a Traduca AI. Como posso ajudar com suas aulas hoje?'
                );
            }

            addMessage('user', cleanText);

            if (input) {
                input.value = '';
            }

            await loadChatbotData();
            replyTo(cleanText);
        }

        function renderActionButton(action, compact) {
            const button = document.createElement('button');
            button.type = 'button';
            button.dataset.chatbotAction = action.label;
            button.innerHTML = `<i class="bi ${action.icon}"></i><span>${escapeHtml(action.label)}</span>`;

            if (!compact) {
                button.className = 'traduca-chatbot__quick-action';
            }

            button.addEventListener('click', () => send(action.label));
            return button;
        }

        if (quickActions && suggestions) {
            actions.forEach((action) => {
                quickActions.appendChild(renderActionButton(action, false));
                suggestions.appendChild(renderActionButton(action, true));
            });
        }

        toggleButton?.addEventListener('click', () => setOpen(!widget.classList.contains('is-open')));
        closeButton?.addEventListener('click', () => setOpen(false));
        resetButton?.addEventListener('click', setInitialMode);

        input?.addEventListener('input', () => {
            if (sendButton) {
                sendButton.disabled = !input.value.trim() || isTyping;
            }
        });

        form?.addEventListener('submit', (event) => {
            event.preventDefault();
            send(input?.value || '');
        });

        if (sendButton) {
            sendButton.disabled = true;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-chatbot]').forEach(initChatbot);
    });
})();
