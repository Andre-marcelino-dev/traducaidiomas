@extends('admin.auth.layout-senha')

@section('titulo', 'Criar nova senha')
@section('subtitulo', 'Digite o código que chegou no seu e-mail e a senha nova')

@section('conteudo')
    <form method="POST" action="{{ route('admin.senha.salvar') }}" novalidate>
        @csrf

        <div class="grupo">
            <label for="email_professor">E-mail</label>
            <div class="campo-wrapper">
                <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <input type="email" id="email_professor" name="email_professor"
                    value="{{ old('email_professor', session('email_senha')) }}"
                    autocomplete="email" required
                    class="{{ $errors->has('email_professor') ? 'is-invalido' : '' }}">
            </div>
            @error('email_professor')
                <p class="erro-campo">{{ $message }}</p>
            @enderror
        </div>

        <div class="grupo">
            <label for="codigo">Código recebido</label>
            <div class="campo-wrapper">
                <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="text" id="codigo" name="codigo"
                    inputmode="numeric" maxlength="6" placeholder="000000"
                    autocomplete="one-time-code" required autofocus
                    class="{{ $errors->has('codigo') ? 'is-invalido' : '' }}">
            </div>
            @error('codigo')
                <p class="erro-campo">{{ $message }}</p>
            @enderror
        </div>

        <div class="grupo">
            <label for="nova_senha">Nova senha</label>
            <div class="campo-wrapper">
                <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="password" id="nova_senha" name="nova_senha"
                    placeholder="Mínimo de 6 caracteres"
                    autocomplete="new-password" required
                    class="{{ $errors->has('nova_senha') ? 'is-invalido' : '' }}">
            </div>
            @error('nova_senha')
                <p class="erro-campo">{{ $message }}</p>
            @enderror
        </div>

        <div class="grupo">
            <label for="nova_senha_confirmation">Confirmar nova senha</label>
            <div class="campo-wrapper">
                <svg class="campo-icone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation"
                    autocomplete="new-password" required>
            </div>
        </div>

        <button type="submit" class="btn-entrar">
            <span class="texto-btn">Salvar nova senha</span>
        </button>
    </form>

    <p class="info-suporte" style="margin-bottom: .75rem;">
        Não recebeu? Confira o spam ou <a href="{{ route('admin.senha.esqueci') }}">peça outro código</a>.
    </p>
@endsection
