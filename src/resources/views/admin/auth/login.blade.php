<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('traducaidiomas/css/css/login.css') }}">
</head>
<body>

    {{-- PAINEL ESQUERDO --}}
    <div class="panel-esquerdo">
        <div class="numero-grande">A</div>
        <div class="badge-sistema">Sistema Ativo</div>
        <h2 class="tagline">
            Gestão<br>
            <em>inteligente</em><br>
            de professores
        </h2>
        <p class="descricao">
            Plataforma administrativa para controle de professores, turmas e registros acadêmicos.
        </p>
        <div class="rodape-painel">
            <div class="avatar-stack">
                <span>RN</span>
               
            </div>
            <p class="rodape-texto">
                Acesso exclusivo<br>para administradores
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
                    Painel Administrativo
                    <span>TraducaIdiomas</span>
                    <span>Área Restrita</span>
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

            @if(session('success'))
                <div class="alerta-sucesso">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alerta-info">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    {{ session('info') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.autenticar') }}" id="formLogin" novalidate>
                @csrf

                <div class="grupo">
                    <label for="email_professor">E-mail</label>
                    <div class="campo-wrapper">
                        <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <input type="email" id="email_professor" name="email_professor"
                            value="{{ old('email_professor') }}"
                            placeholder="professor@escola.edu.br"
                            autocomplete="email"
                            class="{{ $errors->has('email_professor') ? 'is-invalido' : '' }}">
                    </div>
                    @error('email_professor')
                        <p class="erro-campo">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grupo">
                    <label for="senha_professor">Senha</label>
                    <div class="campo-wrapper">
                        <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" id="senha_professor" name="senha_professor"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="{{ $errors->has('senha_professor') ? 'is-invalido' : '' }}">
                        <button type="button" class="toggle-senha" onclick="toggleSenha()" aria-label="Mostrar senha">
                            <svg id="icone-olho" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('senha_professor')
                        <p class="erro-campo">{{ $message }}</p>
                    @enderror
                </div>

                <div class="linha-opcoes">
                    <span></span>
                    <a href="{{ route('admin.recuperar-senha') }}" class="link-esqueceu">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn-entrar" id="btnEntrar">
                    <span class="texto-btn">Entrar no sistema</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="divisor">ou</div>

            <p class="info-suporte">
                Primeiro acesso? <a href="{{ route('admin.verificar') }}">Cadastre suas credenciais</a>
            </p>

        </div>
    </div>

    <script>
        function toggleSenha() {
            const campo = document.getElementById('senha_professor');
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
        const emailInput = document.getElementById('email_professor');
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
