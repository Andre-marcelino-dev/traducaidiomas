@extends('admin.layout.admin')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold">Professores</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                        <li class="breadcrumb-item active">Professores</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-styled alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- METRIC CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-blue shadow">
                        <div class="mc-icon"><i class="fas fa-chalkboard-user"></i></div>
                        <div class="mc-val">{{ $professores->count() }}</div>
                        <p class="mc-lbl">Total de Professores</p>
                        <div class="mc-trend"><i class="fas fa-arrow-trend-up me-1"></i>cadastrados no sistema</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-amber shadow">
                        <div class="mc-icon"><i class="fas fa-book-open"></i></div>
                        <div class="mc-val">{{ $totalCursos }}</div>
                        <p class="mc-lbl">Cursos Oferecidos</p>
                        <div class="mc-trend"><i class="fas fa-language me-1"></i>idiomas no catálogo</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-clock"></i></div>
                        <div class="mc-val">{{ number_format($mediaExperiencia, 1) }}</div>
                        <p class="mc-lbl">Média de Experiência</p>
                        <div class="mc-trend"><i class="fas fa-calendar me-1"></i>anos em média</div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 fade-up">
                    <div class="mc mc-rose shadow">
                        <div class="mc-icon"><i class="fas fa-user-graduate"></i></div>
                        <div class="mc-val">{{ $totalAlunos }}</div>
                        <p class="mc-lbl">Total de Alunos</p>
                        <div class="mc-trend">
                            <i class="fas fa-circle me-1" style="color:#fde68a; font-size:.6rem;"></i>{{ $iniciantes }}
                            Inic. &nbsp;
                            <i class="fas fa-circle me-1" style="color:#a7f3d0; font-size:.6rem;"></i>{{ $intermediarios }}
                            Inter. &nbsp;
                            <i class="fas fa-circle me-1" style="color:#bfdbfe; font-size:.6rem;"></i>{{ $avancados }}
                            Avanç.
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABELA --}}
            <div class="d-card fade-up">
                <div class="d-card-header">
                    <h6><i class="fas fa-chalkboard-user text-primary"></i> Lista de Professores</h6>
                    <a href="{{ route('admin.professores.create') }}" class="tbl-btn-novo">
                        <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Novo Professor</span>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table mb-0">
                        <thead>
                            <tr>
                                <th>Professor</th>
                                <th>Especialidade</th>
                                <th>Nível</th>
                                <th>Experiência</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($professores as $professor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if (!empty($professor->foto_professor))
                                                <img src="{{ asset('traducaidiomas/professor/' . $professor->foto_professor) }}?v={{ time() }}"
                                                    class="prof-avatar" alt="{{ $professor->nome_professor }}">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($professor->nome_professor) }}&background=0D6EFD&color=fff&size=50"
                                                    class="prof-avatar" alt="{{ $professor->nome_professor }}">
                                            @endif
                                            <div>
                                                <div style="font-weight:600;font-size:.875rem;">{{ $professor->nome_professor }}</div>
                                                <div style="font-size:.72rem;color:#94a3b8;">{{ $professor->email_professor }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $professor->especialidade_professor ?? '—' }}</td>
                                    <td>
                                        @php
                                            $nivel = strtolower($professor->nivel_professor ?? '');
                                            $config = match (true) {
                                                in_array($nivel, ['basico', 'básico']) => ['pct' => 33, 'cor' => '#ef4444', 'label' => 'Básico'],
                                                in_array($nivel, ['intermediario', 'intermediário']) => ['pct' => 66, 'cor' => '#f59e0b', 'label' => 'Intermediário'],
                                                in_array($nivel, ['avancado', 'avançado']) => ['pct' => 100, 'cor' => '#22c55e', 'label' => 'Avançado'],
                                                default => ['pct' => 0, 'cor' => '#94a3b8', 'label' => '—'],
                                            };
                                        @endphp
                                        <div style="min-width: 100px;">
                                            <div style="font-size:.72rem; color:#64748b; margin-bottom:3px;">{{ $config['label'] }}</div>
                                            <div style="background:#e2e8f0; border-radius:99px; height:6px;">
                                                <div style="width:{{ $config['pct'] }}%; background:{{ $config['cor'] }}; height:6px; border-radius:99px;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $professor->experiencia_professor }}</td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('admin.professores.edit', $professor->id_professor) }}"
                                                class="tbl-btn-editar">
                                                <i class="fas fa-pen-to-square"></i> Editar
                                            </a>
                                            <form action="{{ route('admin.professores.destroy', $professor->id_professor) }}" method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="tbl-btn-excluir"
                                                    data-nome="{{ $professor->nome_professor }}"
                                                    onclick="abrirModalExcluir(this)">
                                                    <i class="fas fa-trash-alt"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="tbl-empty">
                                            <i class="fas fa-chalkboard-user tbl-empty-icon"></i>
                                            <span class="tbl-empty-text">Nenhum professor cadastrado ainda.</span>
                                            <a href="{{ route('admin.professores.create') }}" class="tbl-empty-btn">
                                                <i class="fas fa-plus"></i> Cadastrar Professor
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Frases EN / PT --}}
            <div class="row fade-up mt-4">
                <div class="col-12">
                    <div class="card recent-card" id="card-frases-prof" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);border:none;overflow:hidden;">
                        <div class="card-body p-0" style="position:relative;">
                            <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:rgba(99,102,241,.12);border-radius:50%;pointer-events:none;"></div>
                            <div style="position:absolute;bottom:-30px;left:-30px;width:120px;height:120px;background:rgba(167,139,250,.08);border-radius:50%;pointer-events:none;"></div>
                            <div class="d-flex align-items-center justify-content-between p-4" style="position:relative;z-index:1;">
                                <div style="flex:1;padding-right:2rem;">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span style="font-size:1.1rem;" id="fp-flag">🇺🇸</span>
                                        <span style="font-size:.7rem;font-weight:700;letter-spacing:.1em;color:#a78bfa;text-transform:uppercase;">Frase do Momento</span>
                                    </div>
                                    <p id="fp-en" style="font-size:1.1rem;font-weight:600;color:#f1f5f9;margin:0 0 10px;min-height:32px;line-height:1.5;"></p>
                                    <div id="fp-div" style="width:36px;height:2px;background:linear-gradient(90deg,#6366f1,#a78bfa);border-radius:99px;margin-bottom:10px;opacity:0;transition:opacity .4s;"></div>
                                    <p id="fp-pt" style="font-size:.9rem;color:#a78bfa;font-style:italic;margin:0;min-height:28px;opacity:0;transition:opacity .4s;"></p>
                                </div>
                                <div style="flex-shrink:0;" class="d-none d-md-block">
                                    <i class="fas fa-language" style="font-size:4rem;color:rgba(99,102,241,.25);"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            (function(){
                const frases = [
                    { en: "Knowledge is power.", pt: "Conhecimento é poder." },
                    { en: "Practice makes perfect.", pt: "A prática leva à perfeição." },
                    { en: "Every expert was once a beginner.", pt: "Todo especialista já foi iniciante." },
                    { en: "Language is the road map of a culture.", pt: "A língua é o mapa de uma cultura." },
                    { en: "To have another language is to possess a second soul.", pt: "Ter outro idioma é possuir uma segunda alma." },
                    { en: "Learning never exhausts the mind.", pt: "O aprendizado nunca esgota a mente." },
                    { en: "The limits of my language are the limits of my world.", pt: "Os limites da minha língua são os limites do meu mundo." },
                    { en: "A different language is a different vision of life.", pt: "Um idioma diferente é uma visão diferente da vida." },
                    { en: "Fluency comes one word at a time.", pt: "A fluência vem uma palavra de cada vez." },
                    { en: "Invest in yourself — it pays the best interest.", pt: "Invista em si mesmo — é o melhor retorno." },
                ];
                let idx = 0;
                const elEn   = document.getElementById('fp-en');
                const elPt   = document.getElementById('fp-pt');
                const elDiv  = document.getElementById('fp-div');
                const elFlag = document.getElementById('fp-flag');

                function typewriter(el, text, cb) {
                    el.textContent = '';
                    let i = 0;
                    const t = setInterval(function(){
                        el.textContent += text[i++];
                        if (i >= text.length) { clearInterval(t); if (cb) cb(); }
                    }, 40);
                }

                function mostrar() {
                    const f = frases[idx % frases.length];
                    elEn.style.opacity  = '0';
                    elPt.style.opacity  = '0';
                    elDiv.style.opacity = '0';
                    elFlag.textContent  = '🇺🇸';

                    setTimeout(function(){
                        elEn.style.opacity = '1';
                        typewriter(elEn, f.en, function(){
                            setTimeout(function(){
                                elDiv.style.opacity = '1';
                                elFlag.textContent  = '🇧🇷';
                                setTimeout(function(){
                                    elPt.style.opacity = '1';
                                    typewriter(elPt, f.pt, function(){
                                        setTimeout(function(){
                                            elEn.style.transition  = 'opacity .4s';
                                            elPt.style.transition  = 'opacity .4s';
                                            elDiv.style.transition = 'opacity .4s';
                                            elEn.style.opacity  = '0';
                                            elPt.style.opacity  = '0';
                                            elDiv.style.opacity = '0';
                                            setTimeout(function(){ idx++; mostrar(); }, 500);
                                        }, 3000);
                                    });
                                }, 200);
                            }, 700);
                        });
                    }, 300);
                }

                document.addEventListener('DOMContentLoaded', mostrar);
            })();
            </script>

        </div>
    </div>

    @include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Professor', 'delDescricao' => 'Você está prestes a excluir o professor:'])

@endsection
