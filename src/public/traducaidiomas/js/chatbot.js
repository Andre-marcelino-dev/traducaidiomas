(() => {
  const root = document.querySelector('[data-chatbot]');
  if (!root) return;
  const panel = root.querySelector('[data-chatbot-panel]');
  const messages = root.querySelector('[data-chatbot-messages]');
  const input = root.querySelector('[data-chatbot-input]');
  const form = root.querySelector('[data-chatbot-form]');
  const history = [];
  const add = (text, role) => { const el = document.createElement('div'); el.className = `traduca-chatbot__message is-${role}`; el.textContent = text; messages.appendChild(el); messages.scrollTop = messages.scrollHeight; };
  const toggle = (open) => { panel.hidden = !open; panel.classList.toggle('is-open', open); panel.setAttribute('aria-hidden', String(!open)); if (open) input.focus(); };
  const launcher = root.querySelector('[data-chatbot-toggle]');
  const close = root.querySelector('[data-chatbot-close]');
  if (!launcher || !close || !panel || !messages || !input || !form) return;
  launcher.addEventListener('click', () => toggle(true));
  close.addEventListener('click', () => toggle(false));
  add('Olá! Sou a Traduca AI. Posso ajudar com idiomas e traduções.', 'assistant');
  form.addEventListener('submit', async (event) => {
    event.preventDefault(); const text = input.value.trim(); if (!text) return;
    add(text, 'user'); history.push({ role: 'user', content: text }); input.value = '';
    const button = form.querySelector('[data-chatbot-send]'); button.disabled = true; add('Pensando…', 'assistant'); const pending = messages.lastElementChild;
    try { const response = await fetch('/chatbot/mensagem', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }, body: JSON.stringify({ message: text, history: history.slice(-7, -1) }) }); const data = await response.json(); if (!response.ok) throw new Error(data.message); pending.remove(); add(data.message || 'Não recebi uma resposta.', 'assistant'); history.push({ role: 'assistant', content: data.message }); }
    catch (error) { pending.textContent = error.message || 'Não foi possível enviar agora. Tente novamente.'; }
    finally { button.disabled = false; input.focus(); }
  });
})();
