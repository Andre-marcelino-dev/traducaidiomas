<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primeiro Acesso</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:        #0f0e0d;
            --paper:      #f5f0e8;
            --cream:      #ede7d9;
            --accent:     #c0392b;
            --accent-dim: #9b2e22;
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
            background: #fdf0ef;
            border: 1px solid #f5c2be;
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

        input[type="text"],
        input[type="tel"] {
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
            border-color: var(--ink);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15,14,13,.07);
        }

        input.is-invalido { border-color: var(--accent); }
        .erro-campo { font-size: .72rem; color: var(--accent); margin-top: .3rem; }

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
            margin-top: .5rem;
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

        .link-voltar {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            color: var(--muted);
            text-decoration: none;
            margin-top: 1.5rem;
            transition: color .15s;
        }
        .link-voltar:hover { color: var(--ink); }

        .card-login > * { opacity: 0; transform: translateY(12px); animation: surgir .5s ease forwards; }
        .card-login > *:nth-child(1) { animation-delay: .05s; }
        .card-login > *:nth-child(2) { animation-delay: .12s; }
        .card-login > *:nth-child(3) { animation-delay: .17s; }
        .card-login > *:nth-child(4) { animation-delay: .22s; }
        .card-login > *:nth-child(5) { animation-delay: .27s; }
        .card-login > *:nth-child(6) { animation-delay: .32s; }

        @keyframes surgir { to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .panel-esquerdo { display: none; }
            .panel-direito { padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>

    <div class="panel-esquerdo">
        <div class="numero-grande">1</div>
        <div class="badge-sistema">Primeiro Acesso</div>
        <h2 class="tagline">
            Configure<br>
            seu <em>acesso</em><br>
            ao sistema
        </h2>
        <p class="descricao">
            Informe seus dados cadastrais para verificar se você já possui credenciais de acesso.
        </p>
    </div>

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
                    Primeiro Acesso
                    <span>TraducaIdiomas</span>
                </div>
            </div>

            <h1 class="titulo">Verificar cadastro</h1>
            <p class="subtitulo">Informe seu nome e telefone para verificar seu acesso</p>

            @if(session('error'))
                <div class="alerta-erro">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.verificar.acesso') }}" id="formVerificar" novalidate>
                @csrf

                <div class="grupo">
                    <label for="nome_professor">Nome completo</label>
                    <div class="campo-wrapper">
                        <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" id="nome_professor" name="nome_professor"
                            value="{{ old('nome_professor') }}"
                            placeholder="Seu nome completo"
                            class="{{ $errors->has('nome_professor') ? 'is-invalido' : '' }}">
                    </div>
                    @error('nome_professor')
                        <p class="erro-campo">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grupo">
                    <label for="telefone_professor">Telefone</label>
                    <div class="campo-wrapper">
                        <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <input type="tel" id="telefone_professor" name="telefone_professor"
                            value="{{ old('telefone_professor') }}"
                            placeholder="(00) 00000-0000"
                            class="{{ $errors->has('telefone_professor') ? 'is-invalido' : '' }}">
                    </div>
                    @error('telefone_professor')
                        <p class="erro-campo">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-entrar" id="btnVerificar">
                    <span class="texto-btn">Verificar meu cadastro</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <a href="{{ route('admin.login') }}" class="link-voltar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
                Voltar ao login
            </a>

        </div>
    </div>

    <script>
        // Máscara de telefone
        const telefoneInput = document.getElementById('telefone_professor');
        telefoneInput.addEventListener('input', function() {
            let v = this.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            if (v.length > 6) {
                this.value = '(' + v.slice(0,2) + ') ' + v.slice(2,7) + '-' + v.slice(7);
            } else if (v.length > 2) {
                this.value = '(' + v.slice(0,2) + ') ' + v.slice(2);
            } else if (v.length > 0) {
                this.value = '(' + v;
            }

            const erroExistente = this.parentElement.parentElement.querySelector('.erro-campo-js');
            if (v.length > 0 && v.length < 10) {
                this.classList.add('is-invalido');
                if (!erroExistente) {
                    const p = document.createElement('p');
                    p.className = 'erro-campo erro-campo-js';
                    p.textContent = 'Digite um telefone válido (ex: (11) 99999-9999)';
                    this.parentElement.parentElement.appendChild(p);
                }
            } else {
                this.classList.remove('is-invalido');
                if (erroExistente) erroExistente.remove();
            }
        });

        // Bloquear teclas que não são números no telefone
        telefoneInput.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which || e.keyCode);
            if (!/[\d]/.test(char) && e.which !== 8 && e.which !== 0) {
                e.preventDefault();
            }
        });

        const formVerificar = document.getElementById('formVerificar');
        formVerificar.addEventListener('submit', function(e) {
            const tel = telefoneInput.value.replace(/\D/g, '');
            if (tel.length < 10) {
                e.preventDefault();
                telefoneInput.focus();
                return;
            }
            document.getElementById('btnVerificar').classList.add('carregando');
        });
    </script>

</body>
</html>
