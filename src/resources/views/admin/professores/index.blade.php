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
                    <!--
category: Math
tags: [add, create, new, "+"]
version: "1.0"
unicode: "eb0b"
-->
<svg
  xmlns="http://www.w3.org/2000/svg"
  width="24"
  height="24"
  viewBox="0 0 24 24"
  fill="none"
  stroke="#ffffff"
  stroke-width="2"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path d="M12 5l0 14" />
  <path d="M5 12l14 0" />
</svg>
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
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25L13.5 1.25C13.9142 1.25 14.25 1.58579 14.25 2C14.25 2.41421 13.9142 2.75 13.5 2.75H12C9.62178 2.75 7.91356 2.75159 6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12V10.5C21.25 10.0858 21.5858 9.75 22 9.75C22.4142 9.75 22.75 10.0858 22.75 10.5V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM16.7705 2.27592C18.1384 0.908029 20.3562 0.908029 21.7241 2.27592C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27592ZM20.6634 3.33658C19.8813 2.55448 18.6133 2.55448 17.8312 3.33658L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33658ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z" fill="#ffffff" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.professores.destroy', $professor->id_professor) }}" method="POST" class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="tbl-btn-excluir"
                                            data-nome="{{ $professor->nome_professor }}"
                                            onclick="abrirModalExcluir(this)">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M10.3093 2.24996H13.6907C13.9071 2.24982 14.0956 2.2497 14.2736 2.27813C14.9769 2.39043 15.5855 2.82909 15.9145 3.46078C15.9978 3.62067 16.0573 3.79955 16.1256 4.00488L16.2372 4.33978C16.2561 4.39647 16.2615 4.41252 16.266 4.42516C16.4412 4.90927 16.8952 5.23653 17.4098 5.24958C17.4234 5.24992 17.4399 5.24998 17.5 5.24998H20.5C20.9142 5.24998 21.25 5.58576 21.25 5.99998C21.25 6.41419 20.9142 6.74998 20.5 6.74998H3.49991C3.08569 6.74998 2.74991 6.41419 2.74991 5.99998C2.74991 5.58576 3.08569 5.24998 3.49991 5.24998H6.49999C6.56004 5.24998 6.57661 5.24992 6.59014 5.24958C7.10479 5.23653 7.55881 4.90929 7.73393 4.42518C7.73854 4.41245 7.74383 4.39675 7.76282 4.33978L7.87443 4.0049C7.94272 3.79958 8.00223 3.62067 8.08549 3.46078C8.41444 2.82909 9.02304 2.39043 9.72634 2.27813C9.90436 2.2497 10.0929 2.24982 10.3093 2.24996ZM9.00806 5.24998C9.05957 5.14895 9.10521 5.04398 9.14448 4.93542C9.15641 4.90245 9.1681 4.86736 9.18313 4.82228L9.28293 4.52286C9.3741 4.24935 9.39509 4.19357 9.41592 4.15358C9.52557 3.94301 9.72843 3.7968 9.96287 3.75936C10.0074 3.75225 10.0669 3.74998 10.3553 3.74998H13.6447C13.933 3.74998 13.9926 3.75225 14.0371 3.75936C14.2716 3.7968 14.4744 3.94301 14.5841 4.15358C14.6049 4.19357 14.6259 4.24934 14.7171 4.52286L14.8168 4.8221L14.8555 4.93544C14.8948 5.04399 14.9404 5.14896 14.9919 5.24998H9.00806Z" fill="#ffffff"/>
<path d="M5.915 8.45009C5.88744 8.03679 5.53007 7.72409 5.11677 7.75164C4.70347 7.77919 4.39077 8.13657 4.41832 8.54987L4.88177 15.5016C4.96726 16.7843 5.03633 17.8205 5.1983 18.6336C5.3667 19.4789 5.65312 20.1849 6.24471 20.7384C6.83631 21.2919 7.55985 21.5307 8.41451 21.6425C9.23653 21.75 10.275 21.75 11.5605 21.75H12.4394C13.725 21.75 14.7635 21.75 15.5855 21.6425C16.4401 21.5307 17.1637 21.2919 17.7553 20.7384C18.3469 20.1849 18.6333 19.4789 18.8017 18.6336C18.9637 17.8205 19.0327 16.7844 19.1182 15.5016L19.5817 8.54987C19.6092 8.13657 19.2965 7.77919 18.8832 7.75164C18.4699 7.72409 18.1125 8.03679 18.085 8.45009L17.625 15.3492C17.5352 16.6971 17.4712 17.6349 17.3306 18.3405C17.1942 19.0249 17.0039 19.3872 16.7305 19.643C16.4571 19.8988 16.0829 20.0646 15.3909 20.1552C14.6775 20.2485 13.7375 20.25 12.3867 20.25H11.6133C10.2625 20.25 9.32246 20.2485 8.60906 20.1552C7.91706 20.0646 7.5429 19.8988 7.26949 19.643C6.99607 19.3872 6.80574 19.0249 6.66939 18.3405C6.52882 17.6349 6.4648 16.6971 6.37494 15.3492L5.915 8.45009Z" fill="#ffffff"/>
<path d="M9.42537 10.2537C9.83753 10.2125 10.2051 10.5132 10.2463 10.9253L10.7463 15.9253C10.7875 16.3375 10.4868 16.705 10.0746 16.7463C9.66247 16.7875 9.29494 16.4868 9.25372 16.0746L8.75372 11.0746C8.71251 10.6624 9.01321 10.2949 9.42537 10.2537Z" fill="#ffffff"/>
<path d="M14.5746 10.2537C14.9868 10.2949 15.2875 10.6624 15.2463 11.0746L14.7463 16.0746C14.7051 16.4868 14.3375 16.7875 13.9254 16.7463C13.5132 16.705 13.2125 16.3375 13.2537 15.9253L13.7537 10.9253C13.7949 10.5132 14.1625 10.2125 14.5746 10.2537Z" fill="#ffffff"/>
</svg>
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
            (function() {
                const frases = [{
                        en: "Knowledge is power.",
                        pt: "Conhecimento é poder."
                    },
                    {
                        en: "Practice makes perfect.",
                        pt: "A prática leva à perfeição."
                    },
                    {
                        en: "Every expert was once a beginner.",
                        pt: "Todo especialista já foi iniciante."
                    },
                    {
                        en: "Language is the road map of a culture.",
                        pt: "A língua é o mapa de uma cultura."
                    },
                    {
                        en: "To have another language is to possess a second soul.",
                        pt: "Ter outro idioma é possuir uma segunda alma."
                    },
                    {
                        en: "Learning never exhausts the mind.",
                        pt: "O aprendizado nunca esgota a mente."
                    },
                    {
                        en: "The limits of my language are the limits of my world.",
                        pt: "Os limites da minha língua são os limites do meu mundo."
                    },
                    {
                        en: "A different language is a different vision of life.",
                        pt: "Um idioma diferente é uma visão diferente da vida."
                    },
                    {
                        en: "Fluency comes one word at a time.",
                        pt: "A fluência vem uma palavra de cada vez."
                    },
                    {
                        en: "Invest in yourself — it pays the best interest.",
                        pt: "Invista em si mesmo — é o melhor retorno."
                    },
                ];
                let idx = 0;
                const elEn = document.getElementById('fp-en');
                const elPt = document.getElementById('fp-pt');
                const elDiv = document.getElementById('fp-div');
                const elFlag = document.getElementById('fp-flag');

                function typewriter(el, text, cb) {
                    el.textContent = '';
                    let i = 0;
                    const t = setInterval(function() {
                        el.textContent += text[i++];
                        if (i >= text.length) {
                            clearInterval(t);
                            if (cb) cb();
                        }
                    }, 40);
                }

                function mostrar() {
                    const f = frases[idx % frases.length];
                    elEn.style.opacity = '0';
                    elPt.style.opacity = '0';
                    elDiv.style.opacity = '0';
                    elFlag.textContent = '🇺🇸';

                    setTimeout(function() {
                        elEn.style.opacity = '1';
                        typewriter(elEn, f.en, function() {
                            setTimeout(function() {
                                elDiv.style.opacity = '1';
                                elFlag.textContent = '🇧🇷';
                                setTimeout(function() {
                                    elPt.style.opacity = '1';
                                    typewriter(elPt, f.pt, function() {
                                        setTimeout(function() {
                                            elEn.style.transition = 'opacity .4s';
                                            elPt.style.transition = 'opacity .4s';
                                            elDiv.style.transition = 'opacity .4s';
                                            elEn.style.opacity = '0';
                                            elPt.style.opacity = '0';
                                            elDiv.style.opacity = '0';
                                            setTimeout(function() {
                                                idx++;
                                                mostrar();
                                            }, 500);
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