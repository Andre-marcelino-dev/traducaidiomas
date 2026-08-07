function initChatbot(widget) {
    console.log("INICIO CHATBOT");
    if (!widget) {
        return;
    }

    const apiUrl = widget.dataset.chatbotDataUrl || "";
    const apiChatUrl = widget.dataset.chatbotMessageUrl || "";
    const csrfToken = widget.dataset.csrfToken || "";
    const userName = (widget.dataset.userName || "").trim();
    const toggleButton = widget.querySelector("[data-chatbot-toggle]");
    const closeButton = widget.querySelector("[data-chatbot-close]");
    const resetButton = widget.querySelector("[data-chatbot-reset]");
    const panel = widget.querySelector("[data-chatbot-panel]");
    const greeting = widget.querySelector("[data-chatbot-greeting]");
    const initial = widget.querySelector("[data-chatbot-initial]");
    const messages = widget.querySelector("[data-chatbot-messages]");
    const quickActions = widget.querySelector("[data-chatbot-quick-actions]");
    const suggestions = widget.querySelector("[data-chatbot-suggestions]");
    const form = widget.querySelector("[data-chatbot-form]");
    const input = widget.querySelector("[data-chatbot-input]");
    const sendButton = widget.querySelector("[data-chatbot-send]");

    let isTyping = false;
    let dataLoaded = false;
    let historyLoaded = false; // Controle para não carregar mais de uma vez

    async function loadChatbotData() {
        if (dataLoaded) return;
        dataLoaded = true;
        await fetchChatbotData(apiUrl);
    }

    if (greeting) {
        greeting.textContent = userName ? `Ola, ${userName}!` : "Ola!";
    }

    async function loadHistory() {
        if (!window.chatbotHistoricoUrl || historyLoaded) {
            return;
        }

        try {
            const response = await fetch(window.chatbotHistoricoUrl, {
                credentials: "same-origin",
                headers: {
                    Accept: "application/json",
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const mensagens = await response.json();

            if (Array.isArray(mensagens) && mensagens.length > 0) {
                historyLoaded = true;

                if (messages) {
                    messages.innerHTML = "";
                }

                setChatMode();

                mensagens.forEach((msg) => {
                    addMessage(
                        msg.tipo === "user" ? "user" : "ai",
                        msg.mensagem,
                    );
                });
            }
        } catch (error) {
            console.error("Erro ao carregar histórico:", error);
        }
    }

    function setOpen(open) {
        widget.classList.toggle("is-open", open);

        toggleButton?.setAttribute("aria-expanded", String(open));
        

        if (open) {
            if (input) window.setTimeout(() => input.focus(), 180);
            loadChatbotData();
            loadHistory();
        }
    }

    function setChatMode() {
        if (initial) initial.hidden = true;
        if (messages) messages.hidden = false;
        if (suggestions) suggestions.hidden = false;
    }

    function setInitialMode() {
        if (initial) initial.hidden = false;
        if (messages) {
            messages.hidden = true;
            messages.innerHTML = "";
        }
        if (suggestions) suggestions.hidden = true;
        if (input) {
            input.value = "";
            input.disabled = false;
        }
        if (sendButton) sendButton.disabled = true;

        isTyping = false;
        historyLoaded = false; // Reseta a trava do histórico se o usuário resetar o chat
    }

    function scrollToEnd() {
        if (messages) {
            messages.scrollTop = messages.scrollHeight;
        }
    }

    function addMessage(role, text, card) {
        if (!messages) return;

        const element = document.createElement("div");
        element.className = `traduca-chatbot__message is-${role}`;

        const avatar =
            role === "ai"
                ? '<div class="traduca-chatbot__avatar" aria-hidden="true">AI</div>'
                : "";

        element.innerHTML = `
                ${avatar}
                <div class="traduca-chatbot__bubble-wrap">
                    <div class="traduca-chatbot__bubble">
                        ${richText(text)}
                        ${card === "schedule" ? scheduleCard() : ""}
                    </div>
                    <span class="traduca-chatbot__time">${now()}</span>
                </div>
            `;

        messages.appendChild(element);
        scrollToEnd();
    }

    function addTyping() {
        if (!messages) return;

        const typing = document.createElement("div");
        typing.className = "traduca-chatbot__message is-ai";
        typing.dataset.chatbotTyping = "true";
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
        messages?.querySelector("[data-chatbot-typing]")?.remove();
    }

    async function replyTo(text) {
        isTyping = true;

        if (input) input.disabled = true;
        if (sendButton) sendButton.disabled = true;

        addTyping();

        try {
            if (!apiChatUrl) {
                throw new Error("Endpoint de mensagem nao disponivel.");
            }

            const response = await fetch(apiChatUrl, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ mensagemDoUsuario: text }),
            });

            if (!response.ok) {
                const erro = await response.text();
                console.error("Resposta Laravel:", erro);
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();
            removeTyping();
            addMessage("ai", data.text || fallbackErrorText, data.card || null);
        } catch (error) {
            console.warn("Chatbot usando fallback local:", error.message);
            removeTyping();

            const localResponse = resolveLocalFallbackResponse(text);
            addMessage(
                "ai",
                localResponse ? localResponse.text : fallbackErrorText,
                localResponse ? localResponse.card : null,
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
        const cleanText = String(text || "").trim();

        if (!cleanText || isTyping) return;

        const firstInteraction = messages ? messages.hidden : false;
        setChatMode();

        if (firstInteraction) {
            addMessage(
                "ai",
                userName
                    ? `Ola, ${userName}! Sou a Traduca AI. Como posso ajudar com suas aulas hoje?`
                    : "Ola! Sou a Traduca AI. Como posso ajudar com suas aulas hoje?",
            );
        }

        addMessage("user", cleanText);

        if (input) input.value = "";

        await loadChatbotData();
        replyTo(cleanText);
    }

    function renderActionButton(action, compact) {
        const button = document.createElement("button");
        button.type = "button";
        button.dataset.chatbotAction = action.label;
        button.innerHTML = `<i class="bi ${action.icon}"></i><span>${escapeHtml(action.label)}</span>`;

        if (!compact) {
            button.className = "traduca-chatbot__quick-action";
        }

        button.addEventListener("click", () => send(action.label));
        return button;
    }

    if (quickActions && suggestions) {
        actions.forEach((action) => {
            quickActions.appendChild(renderActionButton(action, false));
            suggestions.appendChild(renderActionButton(action, true));
        });
    }

    if (toggleButton) {
        console.log("Botão encontrado", toggleButton);
        toggleButton.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = widget.classList.contains("is-open");
            setOpen(!isOpen);
        });
    }

    if (closeButton) {
        closeButton.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            setOpen(false);
        });
    }

    if (resetButton) {
        resetButton.addEventListener("click", (e) => {
            e.preventDefault();
            setInitialMode();
        });
    }
    input?.addEventListener("input", () => {
        if (sendButton) {
            sendButton.disabled = !input.value.trim() || isTyping;
        }
    });

    form?.addEventListener("submit", (event) => {
        event.preventDefault();
        send(input?.value || "");
    });

    if (sendButton) {
        sendButton.disabled = true;
    }
}

document.addEventListener("DOMContentLoaded", () => {

    console.log("DOM carregado");

    const chatbot = document.querySelector("[data-chatbot]");

    console.log("Chatbot encontrado:", chatbot);

    initChatbot(chatbot);

});