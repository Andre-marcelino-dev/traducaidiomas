<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso do Aluno</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:        #0f0e0d;
            --paper:      #f5f0e8;
            --cream:      #ede7d9;
            --accent:     #2b6cb0;
            --accent-dim: #2a5a9b;
            --muted:      #7a7065;
            --line:       #d0c9bc;
        }

        body {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            color: var(--ink);
        }

        .panel-esquerdo {
            position: relative;
            background: var(--ink);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
            overflow: hidden;
        }

        .panel-esquerdo::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                repeating-linear-gradient(0deg, transparent, transparent 59px, rgba(255,255,255,.04) 59px, rgba(255,255,255,.04) 60px),
                repeating-linear-gradient(90deg, transparent, transparent 59px, rgba(255,255,255,.04) 59px, rgba(255,255,255,.04) 60px);
        }

        .numero-grande {
            position: absolute;
            top: -1.5rem;
            right: -1rem;
            font-family: 'Playfair Display', serif;
            font-size: clamp(12rem, 20vw, 22rem);
            font-weight: 700;
            color: rgba(255,255,255,.04);
            line-height: 1;
            user-select: none;
            pointer-events: none;
        }

        .badge-sistema {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: var(--accent);
            color: #fff;
            font-size: .7rem;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: .35rem .75rem;
            border-radius: 2px;
            width: fit-content;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .badge-sistema::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fff;
            animation: pulso 1.8s ease-in-out infinite;
        }

        @keyframes pulso {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .4; transform: scale(.7); }
        }

        .tagline {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            color: #fff;
            line-height: 1.2;
            position: relative;
            z-index: 1;
            margin-bottom: 1rem;
        }

        .tagline em { font-style: normal; color: var(--accent); }

        .descricao {
            font-size: .875rem;
            color: rgba(255,255,255,.45);
            line-height: 1.7;
            max-width: 28ch;
            position: relative;
            z-index: 1;
            margin-bottom: 2.5rem;
        }

        .rodape-painel {
            border-top: 1px solid rgba(255,255,255,.1);
            padding-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .avatar-stack { display: flex; }

        .avatar-stack span {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--accent);
            border: 2px solid var(--ink);
            margin-left: -8px;
            font-size: .65rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }

        .avatar-stack span:first-child  { margin-left: 0; background: #3e6a8e; }
        .avatar-stack span:nth-child(2) { background: #2b6cb0; }
        .avatar-stack span:nth-child(3) { background: #1a4a7a; }

        .rodape-texto { font-size: .75rem; color: rgba(255,255,255,.4); line-height: 1.5; }

        .panel-direito {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: var(--paper);
        }

        .card-login { width: 100%; max-width: 420px; }

        .logo-topo { display: flex; align-items: center; gap: .75rem; margin-bottom: 2.5rem; }

        .logo-icone {
            width: 40px; height: 40px;
            background: var(--ink);
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
        }

        .logo-icone svg { width: 20px; height: 20px; }

        .logo-nome { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; }

        .logo-nome span {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: .65rem;
            font-weight: 400;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 1px;
        }

        h1.titulo { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; line-height: 1.2; margin-bottom: .5rem; }
        .subtitulo { font-size: .875rem; color: var(--muted); margin-bottom: 2rem; }

        .alerta-erro {
            background: #ebf4ff;
            border: 1px solid #bee3f8;
            border-left: 3px solid var(--accent);
            color: var(--accent-dim);
            font-size: .8rem;
            padding: .75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .grupo { margin-bottom: 1.25rem; position: relative; }

        label {
            display: block;
            font-size: .75rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .45rem;
        }

        .campo-wrapper { position: relative; }

        .campo-icone {
            position: absolute;
            left: 1rem; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--muted);
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: .8rem 1rem .8rem 2.75rem;
            background: var(--cream);
            border: 1px solid var(--line);
            border-radius: 4px;
            font-family: 'DM Sans', sans-serif;
            font-size: .9rem;
            color: var(--ink);
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
            -webkit-appearance: none;
        }

        input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(43,108,176,.1);
        }

        input.is-invalido { border-color: var(--accent); }
        .erro-campo { font-size: .72rem; color: var(--accent); margin-top: .3rem; }

        .toggle-senha {
            position: absolute;
            right: 1rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; padding: 0;
            color: var(--muted);
            display: flex; align-items: center;
            transition: color .15s;
        }
        .toggle-senha:hover { color: var(--ink); }

        .btn-entrar {
            width: 100%;
            padding: .9rem;
            background: var(--ink);
            color: var(--paper);
            border: none;
            border-radius: 4px;
            font-family: 'DM Sans', sans-serif;
            font-size: .875rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .2s, transform .1s;
            position: relative;
            overflow: hidden;
        }

        .btn-entrar:hover { background: #2a2927; }
        .btn-entrar:active { transform: scale(.99); }
        .btn-entrar.carregando { pointer-events: none; }

        .btn-entrar .spinner {
            display: none;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: girar .6s linear infinite;
            margin: 0 auto;
        }

        .btn-entrar.carregando .texto-btn { display: none; }
        .btn-entrar.carregando .spinner   { display: block; }

        @keyframes girar { to { transform: rotate(360deg); } }

        .divisor {
            display: flex; align-items: center; gap: 1rem;
            margin: 1.5rem 0;
            font-size: .7rem; letter-spacing: .08em;
            text-transform: uppercase; color: var(--muted);
        }
        .divisor::before, .divisor::after { content: ''; flex: 1; height: 1px; background: var(--line); }

        .info-suporte { text-align: center; font-size: .75rem; color: var(--muted); }
        .info-suporte a { color: var(--ink); text-decoration: none; font-weight: 500; border-bottom: 1px solid var(--line); }
        .info-suporte a:hover { border-color: var(--ink); }

        .card-login > * { opacity: 0; transform: translateY(12px); animation: surgir .5s ease forwards; }
        .card-login > *:nth-child(1) { animation-delay: .05s; }
        .card-login > *:nth-child(2) { animation-delay: .12s; }
        .card-login > *:nth-child(3) { animation-delay: .17s; }
        .card-login > *:nth-child(4) { animation-delay: .22s; }
        .card-login > *:nth-child(5) { animation-delay: .27s; }
        .card-login > *:nth-child(6) { animation-delay: .32s; }
        .card-login > *:nth-child(7) { animation-delay: .37s; }

        @keyframes surgir { to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .panel-esquerdo { display: none; }
            .panel-direito { padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>

    {{-- PAINEL ESQUERDO --}}
    <div class="panel-esquerdo">
        <div class="numero-grande">A</div>
        <div class="badge-sistema">Portal do Aluno</div>
        <h2 class="tagline">
            Aprenda<br>
            <em>novos</em><br>
            idiomas
        </h2>
        <p class="descricao">
            Acesse suas aulas, acompanhe seu progresso e evolua no seu ritmo.
        </p>
        <div class="rodape-painel">
            <div class="avatar-stack">
                <span>AL</span>
                <span>EN</span>
                <span>IT</span>
            </div>
            <p class="rodape-texto">
                Acesso exclusivo<br>para alunos matriculados
            </p>
        </div>
    </div>

    {{-- PAINEL DIREITO --}}
    <div class="panel-direito">
        <div class="card-login">

            <div class="logo-topo">
                <div class="logo-icone">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="logo-nome">
                    Portal do Aluno
                    <span>TraducaIdiomas</span>
                    <span>Área do Aluno</span>
                </div>
            </div>

            <h1 class="titulo">Bem-vindo de volta</h1>
            <p class="subtitulo">Insira suas credenciais para continuar</p>

            @if(session('sessao_expirada'))
                <div class="alerta-erro">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    Sessão encerrada por inatividade. Faça login novamente.
                </div>
            @endif

            @if(session('error'))
                <div class="alerta-erro">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('aluno.autenticar') }}" id="formLogin" novalidate>
                @csrf

                <div class="grupo">
                    <label for="email_aluno">E-mail</label>
                    <div class="campo-wrapper">
                        <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="email_aluno" name="email_aluno"
                            value="{{ old('email_aluno') }}"
                            placeholder="aluno@email.com"
                            autocomplete="email"
                            class="{{ $errors->has('email_aluno') ? 'is-invalido' : '' }}">
                    </div>
                    @error('email_aluno')
                        <p class="erro-campo">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grupo">
                    <label for="senha_aluno">Senha</label>
                    <div class="campo-wrapper">
                        <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" id="senha_aluno" name="senha_aluno"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="{{ $errors->has('senha_aluno') ? 'is-invalido' : '' }}">
                        <button type="button" class="toggle-senha" onclick="toggleSenha()" aria-label="Mostrar senha">
                            <svg id="icone-olho" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('senha_aluno')
                        <p class="erro-campo">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-entrar" id="btnEntrar">
                    <span class="texto-btn">Entrar no portal</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="divisor">ou</div>

            <p class="info-suporte">
                Problemas de acesso? <a href="mailto:suporte@escola.edu.br">Fale com o suporte</a>
            </p>

        </div>
    </div>

    <script>
        function toggleSenha() {
            const campo = document.getElementById('senha_aluno');
            const icone = document.getElementById('icone-olho');
            if (campo.type === 'password') {
                campo.type = 'text';
                icone.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;
            } else {
                campo.type = 'password';
                icone.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
            }
        }

        // Validação de email em tempo real
        const emailInput = document.getElementById('email_aluno');
        const formLogin = document.getElementById('formLogin');

        function validarEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        emailInput.addEventListener('input', function() {
            const val = this.value.trim();
            const erroExistente = this.parentElement.parentElement.querySelector('.erro-campo-js');
            if (val.length > 0 && !validarEmail(val)) {
                this.classList.add('is-invalido');
                if (!erroExistente) {
                    const p = document.createElement('p');
                    p.className = 'erro-campo erro-campo-js';
                    p.textContent = 'Digite um e-mail válido (ex: nome@email.com)';
                    this.parentElement.parentElement.appendChild(p);
                }
            } else {
                this.classList.remove('is-invalido');
                if (erroExistente) erroExistente.remove();
            }
        });

        formLogin.addEventListener('submit', function(e) {
            const val = emailInput.value.trim();
            if (val.length > 0 && !validarEmail(val)) {
                e.preventDefault();
                emailInput.focus();
                return;
            }
            document.getElementById('btnEntrar').classList.add('carregando');
        });
    </script>

</body>
</html>
