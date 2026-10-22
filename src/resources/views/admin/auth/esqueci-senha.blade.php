@extends('admin.auth.layout-senha')

@section('titulo', 'Esqueci minha senha')
@section('subtitulo', 'Digite o e-mail cadastrado no painel para receber o código')

@section('conteudo')
    <form method="POST" action="{{ route('admin.senha.enviar') }}" novalidate>
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
                    autocomplete="email" required autofocus
                    class="{{ $errors->has('email_professor') ? 'is-invalido' : '' }}">
            </div>
            @error('email_professor')
                <p class="erro-campo">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-entrar">
            <span class="texto-btn">Enviar código</span>
        </button>
    </form>
@endsection
