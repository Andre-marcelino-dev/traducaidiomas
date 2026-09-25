<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha seu curso</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            font-family: 'DM Sans', sans-serif;
            background: var(--paper);
            color: var(--ink);
            display: flex;
            flex-direction: column;
        }

        .topo {
            background: var(--ink);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .topo-nome { font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; }
        .topo-nome span {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: .65rem;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
        }
        .btn-sair {
            background: transparent;
            border: 1px solid rgba(255,255,255,.25);
            color: #fff;
            font-family: inherit;
            font-size: .8rem;
            padding: .45rem .9rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-sair:hover { border-color: #fff; }

        main {
            flex: 1;
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            line-height: 1.2;
            margin-bottom: .5rem;
        }
        h1 em { font-style: normal; color: var(--accent); }
        .subtitulo { color: var(--muted); font-size: .9rem; margin-bottom: 2rem; }

        .alerta {
            background: #ebf4ff;
            border: 1px solid #bee3f8;
            border-left: 3px solid var(--accent);
            color: var(--accent-dim);
            font-size: .85rem;
            padding: .75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .grade {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .card-curso { margin: 0; }
        .card-curso button {
            width: 100%;
            height: 100%;
            text-align: left;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 1.5rem;
            font-family: inherit;
            color: inherit;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: .75rem;
            transition: border-color .2s, box-shadow .2s, transform .2s;
        }
        .card-curso button:hover,
        .card-curso button:focus-visible {
            border-color: var(--accent);
            box-shadow: 0 8px 24px rgba(43,108,176,.15);
            transform: translateY(-2px);
            outline: none;
        }
        .card-curso.ativo button { border-color: var(--accent); border-width: 2px; }

        .curso-icone {
            width: 44px; height: 44px;
            border-radius: 8px;
            background: var(--ink);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .curso-nome { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; }
        .curso-info { display: flex; flex-wrap: wrap; gap: .5rem; }
        .tag {
            font-size: .7rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            background: var(--cream);
            color: var(--muted);
            padding: .3rem .6rem;
            border-radius: 3px;
        }
        .tag-nivel { background: var(--accent); color: #fff; }
        .curso-acao {
            margin-top: auto;
            padding-top: .75rem;
            border-top: 1px solid var(--cream);
            font-size: .85rem;
            font-weight: 500;
            color: var(--accent);
        }

        .vazio {
            background: #fff;
            border: 1px dashed var(--line);
            border-radius: 8px;
            padding: 3rem 1.5rem;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 600px) {
            .topo { padding: 1rem; }
            main { padding: 2rem 1rem; }
        }
    </style>
</head>
<body>

    <header class="topo">
        <div class="topo-nome">
            Traduca Idiomas
            <span>Portal do Aluno</span>
        </div>
        <form action="{{ route('aluno.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-sair">Sair</button>
        </form>
    </header>

    <main>
        <h1>Olá, <em>{{ \Illuminate\Support\Str::of($aluno->nome_aluno)->explode(' ')->first() }}</em>!</h1>
        <p class="subtitulo">Escolha o curso que você quer acessar agora. Você pode trocar a qualquer momento pelo menu.</p>

        @if (session('error'))
            <div class="alerta">{{ session('error') }}</div>
        @endif

        @if ($matriculas->isEmpty())
            <div class="vazio">
                Você ainda não possui matrícula ativa em nenhum curso.<br>
                Entre em contato com a secretaria.
            </div>
        @else
            <div class="grade">
                @foreach ($matriculas as $mat)
                    <form action="{{ route('aluno.cursos.selecionar') }}" method="POST"
                          class="card-curso {{ $selecionada == $mat->id_matricula ? 'ativo' : '' }}">
                        @csrf
                        <input type="hidden" name="id_matricula" value="{{ $mat->id_matricula }}">
                        <button type="submit">
                            <div class="curso-icone">
                                {{ mb_strtoupper(mb_substr($mat->curso->nome_curso ?? '?', 0, 1)) }}
                            </div>
                            <div class="curso-nome">{{ $mat->curso->nome_curso ?? 'Curso' }}</div>
                            <div class="curso-info">
                                <span class="tag tag-nivel">{{ $mat->nivel->nome_nivel ?? 'Sem nível' }}</span>
                                <span class="tag">{{ $mat->total_modulos }} {{ $mat->total_modulos == 1 ? 'módulo' : 'módulos' }}</span>
                            </div>
                            <div class="curso-acao">
                                {{ $selecionada == $mat->id_matricula ? 'Continuar neste curso →' : 'Acessar curso →' }}
                            </div>
                        </button>
                    </form>
                @endforeach
            </div>
        @endif
    </main>

</body>
</html>
