/**
 * Teste de nivelamento — front-end.
 *
 * A página vem pronta do Blade (resources/views/site/quiz/quiz.blade.php). Este
 * módulo só liga a interface aos endpoints do QuizController:
 *   GET    /quiz/{idioma}/perguntas   perguntas (sem gabarito) + progresso da sessão
 *   POST   /quiz/{idioma}/responder   corrige uma resposta
 *   POST   /quiz/{idioma}/finalizar   calcula nível CEFR e curso recomendado
 *   DELETE /quiz/{idioma}/respostas   refazer o teste
 */

const app = document.getElementById('quiz-app');

if (app) {
  iniciar(app);
}

function iniciar(app) {
  const BASE = app.dataset.base;
  const CSRF = app.dataset.csrf;
  const TOTAL = Number(app.dataset.total) || 12;
  const CHAVE_STORAGE = 'traducaidiomas.quiz.v1';
  const config = JSON.parse(document.getElementById('quiz-config').textContent);
  const reduzMovimento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const $ = (id) => document.getElementById(id);
  const el = {
    cardsIdioma: [...document.querySelectorAll('.quizIdiomaCard')],
    cardsCurso: [...document.querySelectorAll('.quizCursoCard')],

    tituloAtivo: $('quiz-active-title'),
    metaIdioma: $('quiz-meta-language'),

    intro: $('quiz-guided-intro'),
    introTexto: $('quiz-intro-text'),
    botaoComecar: $('quiz-start-button'),

    runtime: $('quiz-runtime'),
    progressoRotulo: $('quiz-progress-label'),
    progressoPlacar: $('quiz-progress-score'),
    progressoBarra: $('quiz-progress-bar'),
    nivelPill: $('quiz-level-pill'),
    pergunta: $('quiz-question-text'),
    alternativas: $('quiz-answer-buttons'),
    retorno: $('quiz-feedback'),
    proxima: $('quiz-next-button'),

    resultado: $('quiz-result'),
    resultadoTitulo: $('quiz-result-title'),
    resultadoResumo: $('quiz-result-summary'),
    resultadoPontuacao: $('quiz-result-score'),
    resultadoNivel: $('quiz-result-level'),
    resultadoCurso: $('quiz-result-course'),
    resultadoDescricao: $('quiz-result-description'),
    resultadoSugestao: $('quiz-result-suggestion'),
    resultadoCta: $('quiz-result-primary-cta'),
    refazer: $('quiz-retry-button'),

    resumoIdioma: $('summary-language'),
    resumoPontuacao: $('summary-score'),
    resumoNivel: $('summary-level'),
    resumoCurso: $('summary-course'),
    resumoProgresso: $('summary-progress'),
    resumoBarra: $('summary-progress-bar'),
    resumoProximo: $('summary-next-step'),

    cursoTitulo: $('quiz-course-title'),
    cursoDescricao: $('quiz-course-description'),
  };

  const estado = {
    idioma: null,
    perguntas: [],
    atual: 0,
    acertos: 0,
    respondidas: 0,
    jaRespondeu: false,
    ocupado: false,
  };

  // ── Armazenamento local (só lembra idioma e último resultado) ─────
  let salvo = { idioma: null, resultados: {} };
  try {
    salvo = { ...salvo, ...JSON.parse(localStorage.getItem(CHAVE_STORAGE) || '{}') };
  } catch (e) { /* navegador sem localStorage: segue sem lembrar */ }

  function guardar() {
    try {
      localStorage.setItem(CHAVE_STORAGE, JSON.stringify(salvo));
    } catch (e) { /* ignora */ }
  }

  // ── Requisições ───────────────────────────────────────────────────
  async function requisicao(metodo, caminho, corpo) {
    let resposta;
    try {
      resposta = await fetch(`${BASE}/${caminho}`, {
        method: metodo,
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
        body: corpo ? JSON.stringify(corpo) : undefined,
      });
    } catch (e) {
      throw new Error('Sem conexão. Verifique sua internet e tente de novo.');
    }

    if (resposta.status === 419) {
      throw new Error('Sua sessão expirou. Recarregue a página para continuar.');
    }
    if (resposta.status === 429) {
      throw new Error('Muitas tentativas seguidas. Aguarde um instante e tente de novo.');
    }

    const dados = await resposta.json().catch(() => ({}));
    if (!resposta.ok) {
      throw new Error(dados.message || 'Não foi possível concluir agora. Tente novamente.');
    }
    return dados;
  }

  // ── Utilidades de interface ───────────────────────────────────────
  const nomeIdioma = (slug) => config.idiomas[slug]?.nome ?? '';
  const mostrar = (node, visivel) => { node.hidden = !visivel; };

  function rolarPara(seletor) {
    const alvo = document.querySelector(seletor);
    if (alvo) {
      alvo.scrollIntoView({ behavior: reduzMovimento ? 'auto' : 'smooth', block: 'start' });
    }
  }

  function definirProgresso(respondidas) {
    const pct = Math.round((respondidas / TOTAL) * 100);
    el.progressoBarra.style.width = `${pct}%`;
    el.resumoBarra.style.width = `${pct}%`;
    el.resumoProgresso.textContent = `${pct}%`;
  }

  function plural(n, singular, pluralTxt) {
    return `${n} ${n === 1 ? singular : pluralTxt}`;
  }

  // ── Resumo lateral ────────────────────────────────────────────────
  function atualizarResumo() {
    const slug = estado.idioma;
    const ultimo = slug ? salvo.resultados[slug] : null;

    el.resumoIdioma.textContent = slug ? nomeIdioma(slug) : 'Nenhum ainda';

    if (ultimo) {
      el.resumoPontuacao.textContent = `${ultimo.pontuacao}/${ultimo.total}`;
      el.resumoNivel.textContent = `${ultimo.nivel} - ${ultimo.nivel_rotulo}`;
      el.resumoCurso.textContent = ultimo.curso;
    } else {
      el.resumoPontuacao.textContent = 'Ainda não disponível';
      el.resumoNivel.textContent = 'Em definição';
      el.resumoCurso.textContent = slug
        ? 'Termine o teste para receber a recomendação.'
        : 'Escolha um idioma para receber a recomendação.';
    }

    if (!slug) {
      el.resumoProximo.textContent = 'Escolha um idioma para iniciar o teste de nivelamento na mesma página.';
    } else if (ultimo) {
      el.resumoProximo.textContent = `Fale com a gente para começar o curso ${ultimo.curso} ou refaça o teste quando quiser.`;
    } else if (estado.respondidas > 0 && estado.respondidas < TOTAL) {
      el.resumoProximo.textContent = `Você já respondeu ${estado.respondidas} de ${TOTAL}. Continue de onde parou.`;
    } else {
      el.resumoProximo.textContent = `Clique em "Começar teste de ${nomeIdioma(slug)}" na área do quiz.`;
    }
  }

  function filtrarCursos() {
    const slug = estado.idioma;
    el.cardsCurso.forEach((card) => mostrar(card, card.dataset.idioma === slug));

    if (slug) {
      el.cursoTitulo.textContent = `Trilha de ${nomeIdioma(slug)}: do A1 ao C2.`;
      el.cursoDescricao.textContent = 'Cada curso corresponde a um nível CEFR. Comece pelo que o teste recomendou.';
    } else {
      el.cursoTitulo.textContent = 'Explore a trilha do idioma escolhido.';
      el.cursoDescricao.textContent = 'Selecione um idioma para ver os cursos organizados por nível CEFR e seguir com clareza.';
    }

    const recomendado = slug ? salvo.resultados[slug]?.nivel : null;
    el.cardsCurso.forEach((card) => {
      card.classList.toggle('is-recomendado', Boolean(recomendado) && card.dataset.idioma === slug && card.dataset.nivel === recomendado);
    });
  }

  // ── Seleção de idioma ─────────────────────────────────────────────
  function selecionarIdioma(slug, { rolar = true } = {}) {
    if (!config.idiomas[slug]) return;

    const mudou = estado.idioma !== slug;
    estado.idioma = slug;
    if (mudou) {
      estado.perguntas = [];
      estado.respondidas = 0;
      estado.acertos = 0;
      mostrar(el.runtime, false);
      mostrar(el.resultado, false);
      mostrar(el.intro, true);
      definirProgresso(0);
    }

    salvo.idioma = slug;
    guardar();

    el.cardsIdioma.forEach((card) => {
      const ativo = card.dataset.idioma === slug;
      card.classList.toggle('is-ativo', ativo);
      card.setAttribute('aria-pressed', String(ativo));
    });

    const nome = nomeIdioma(slug);
    el.tituloAtivo.textContent = `Teste de nivelamento de ${nome}`;
    el.metaIdioma.textContent = nome;
    el.introTexto.textContent =
      `Você vai responder ${TOTAL} perguntas de ${nome}, uma por vez, e no final recebe seu nível CEFR ` +
      'e o curso recomendado. Se sair da página, suas respostas ficam guardadas.';
    el.botaoComecar.disabled = false;
    el.botaoComecar.textContent = `Começar teste de ${nome}`;

    atualizarResumo();
    filtrarCursos();

    if (rolar) rolarPara('#quiz-workspace');
  }

  // ── Fluxo do teste ────────────────────────────────────────────────
  async function comecar() {
    if (!estado.idioma || estado.ocupado) return;
    estado.ocupado = true;
    el.botaoComecar.disabled = true;
    const rotuloOriginal = el.botaoComecar.textContent;
    el.botaoComecar.textContent = 'Carregando perguntas…';

    try {
      const dados = await requisicao('GET', `${estado.idioma}/perguntas`);
      estado.perguntas = dados.perguntas;

      const respondidas = dados.respondidas || {};
      estado.respondidas = Object.keys(respondidas).length;
      estado.acertos = Object.values(respondidas).filter((r) => r.correta).length;

      if (dados.resultado) {
        mostrarResultado(dados.resultado);
        return;
      }

      // Retoma na primeira pergunta ainda sem resposta.
      const primeiraAberta = estado.perguntas.findIndex((p) => !(p.indice in respondidas));
      estado.atual = primeiraAberta === -1 ? TOTAL - 1 : primeiraAberta;

      mostrar(el.intro, false);
      mostrar(el.resultado, false);
      mostrar(el.runtime, true);
      mostrarPergunta(respondidas[estado.atual] ?? null);
      atualizarResumo();
    } catch (erro) {
      el.botaoComecar.textContent = rotuloOriginal;
      el.introTexto.textContent = erro.message;
    } finally {
      estado.ocupado = false;
      el.botaoComecar.disabled = false;
      if (!el.intro.hidden) el.botaoComecar.textContent = rotuloOriginal;
    }
  }

  function mostrarPergunta(feedbackExistente = null) {
    const pergunta = estado.perguntas[estado.atual];
    estado.jaRespondeu = false;

    el.progressoRotulo.textContent = `Pergunta ${estado.atual + 1} de ${TOTAL}`;
    el.progressoPlacar.textContent = plural(estado.acertos, 'acerto', 'acertos');
    definirProgresso(estado.respondidas);

    el.nivelPill.textContent = pergunta.nivel;
    el.pergunta.textContent = pergunta.enunciado;
    el.retorno.textContent = '';
    el.retorno.className = 'quizRetorno';

    el.proxima.disabled = true;
    el.proxima.textContent = estado.atual === TOTAL - 1 ? 'Ver resultado' : 'Próxima pergunta';

    el.alternativas.replaceChildren(
      ...pergunta.alternativas.map((texto, i) => {
        const botao = document.createElement('button');
        botao.type = 'button';
        botao.className = 'quizAlternativa';
        botao.dataset.indice = String(i);

        const letra = document.createElement('span');
        letra.className = 'quizAlternativaLetra';
        letra.textContent = String.fromCharCode(65 + i);

        const conteudo = document.createElement('span');
        conteudo.textContent = texto;

        botao.append(letra, conteudo);
        botao.addEventListener('click', () => responder(i));
        return botao;
      })
    );

    if (feedbackExistente) {
      aplicarFeedback(feedbackExistente);
    } else {
      el.pergunta.focus({ preventScroll: true });
    }
  }

  async function responder(indiceAlternativa) {
    if (estado.jaRespondeu || estado.ocupado) return;
    estado.ocupado = true;
    el.alternativas.classList.add('is-carregando');

    try {
      const dados = await requisicao('POST', `${estado.idioma}/responder`, {
        pergunta: estado.atual,
        resposta: indiceAlternativa,
      });
      estado.respondidas = dados.respondidas;
      estado.acertos = dados.acertos;
      aplicarFeedback(dados);
      definirProgresso(estado.respondidas);
      el.progressoPlacar.textContent = plural(estado.acertos, 'acerto', 'acertos');
      atualizarResumo();
    } catch (erro) {
      el.retorno.textContent = erro.message;
      el.retorno.className = 'quizRetorno is-erro';
    } finally {
      estado.ocupado = false;
      el.alternativas.classList.remove('is-carregando');
    }
  }

  function aplicarFeedback(dados) {
    estado.jaRespondeu = true;

    [...el.alternativas.children].forEach((botao, i) => {
      botao.disabled = true;
      botao.classList.toggle('is-correta', i === dados.correta_indice);
      botao.classList.toggle('is-errada', i === dados.escolhida && !dados.correta);
      if (i === dados.escolhida) botao.setAttribute('aria-current', 'true');
    });

    el.retorno.className = `quizRetorno ${dados.correta ? 'is-certo' : 'is-errado'}`;
    el.retorno.textContent = `${dados.correta ? 'Correto!' : 'Quase lá.'} ${dados.explicacao}`;
    el.proxima.disabled = false;
    el.proxima.focus({ preventScroll: true });
  }

  async function avancar() {
    if (!estado.jaRespondeu || estado.ocupado) return;

    if (estado.atual < TOTAL - 1) {
      estado.atual += 1;
      mostrarPergunta();
      return;
    }

    estado.ocupado = true;
    el.proxima.disabled = true;
    el.proxima.textContent = 'Calculando…';
    try {
      mostrarResultado(await requisicao('POST', `${estado.idioma}/finalizar`));
    } catch (erro) {
      el.retorno.textContent = erro.message;
      el.retorno.className = 'quizRetorno is-erro';
      el.proxima.disabled = false;
      el.proxima.textContent = 'Ver resultado';
    } finally {
      estado.ocupado = false;
    }
  }

  function mostrarResultado(r) {
    salvo.resultados[r.idioma] = r;
    guardar();

    estado.respondidas = TOTAL;
    estado.acertos = r.pontuacao;
    definirProgresso(TOTAL);

    el.resultadoTitulo.textContent = `Seu nível em ${r.idioma_nome} é ${r.nivel}.`;
    el.resultadoResumo.textContent =
      `Você acertou ${r.pontuacao} de ${r.total} perguntas, o que corresponde ao nível ${r.nivel} (${r.nivel_rotulo}).`;
    el.resultadoPontuacao.textContent = `${r.pontuacao}/${r.total}`;
    el.resultadoNivel.textContent = `${r.nivel} - ${r.nivel_rotulo}`;
    el.resultadoCurso.textContent = r.curso;
    el.resultadoDescricao.textContent = r.descricao;
    el.resultadoSugestao.textContent = r.sugestao;
    el.resultadoCta.href = r.cta_url;

    mostrar(el.intro, false);
    mostrar(el.runtime, false);
    mostrar(el.resultado, true);
    el.resultadoTitulo.focus({ preventScroll: true });
    rolarPara('#quiz-result');

    atualizarResumo();
    filtrarCursos();
  }

  async function refazer() {
    if (!estado.idioma || estado.ocupado) return;
    estado.ocupado = true;
    try {
      await requisicao('DELETE', `${estado.idioma}/respostas`);
      delete salvo.resultados[estado.idioma];
      guardar();
      estado.respondidas = 0;
      estado.acertos = 0;
      estado.perguntas = [];
      definirProgresso(0);
      mostrar(el.resultado, false);
      mostrar(el.intro, true);
      atualizarResumo();
      filtrarCursos();
    } catch (erro) {
      el.resultadoSugestao.textContent = erro.message;
      return;
    } finally {
      estado.ocupado = false;
    }
    comecar();
  }

  // ── Eventos ───────────────────────────────────────────────────────
  el.cardsIdioma.forEach((card) => {
    card.addEventListener('click', () => selecionarIdioma(card.dataset.idioma));
  });

  el.botaoComecar.addEventListener('click', comecar);
  el.proxima.addEventListener('click', avancar);
  el.refazer.addEventListener('click', refazer);

  document.querySelectorAll('[data-scroll-target]').forEach((botao) => {
    botao.addEventListener('click', () => rolarPara(botao.dataset.scrollTarget));
  });

  // Atalhos 1–4 / A–D escolhem a alternativa enquanto o quiz está aberto.
  document.addEventListener('keydown', (evento) => {
    if (el.runtime.hidden || estado.jaRespondeu || evento.metaKey || evento.ctrlKey || evento.altKey) return;
    if (/^(INPUT|TEXTAREA|SELECT)$/.test(evento.target.tagName)) return;

    const mapa = { 1: 0, 2: 1, 3: 2, 4: 3, a: 0, b: 1, c: 2, d: 3 };
    const indice = mapa[evento.key.toLowerCase()];
    if (indice !== undefined && el.alternativas.children[indice]) responder(indice);
  });

  // ── Estado inicial ────────────────────────────────────────────────
  atualizarResumo();
  filtrarCursos();
  if (salvo.idioma && config.idiomas[salvo.idioma]) {
    selecionarIdioma(salvo.idioma, { rolar: false });
  }
}
