@extends('aluno.layout.aluno')

@section('content')

    {{-- ── Bloco de Estilos CSS para garantir o Layout Moderno ── --}}
    <style>
        /* Estilos dos Cards de Métricas */
        .mc {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s;
        }
        .mc:hover { transform: translateY(-2px); }
        .mc-icon { font-size: 24px; margin-bottom: 10px; }
        .mc-val { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
        .mc-lbl { font-size: 13px; color: #64748b; margin-bottom: 8px; font-weight: 500; }
        .mc-trend { font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 20px; display: inline-block; }
        .mc-blue .mc-trend { background: #eff6ff; color: #2563eb; }
        .mc-green .mc-trend { background: #ecfdf5; color: #059669; }

        /* Card de Hoje / Calendário */
        .metric-card-rose {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .metric-value { font-size: 20px; font-weight: 700; color: #e11d48; }
        .metric-label { font-size: 13px; color: #64748b; margin-bottom: 6px; }
        .metric-trend { font-size: 12px; color: #475569; font-weight: 500; }

        /* Estrutura de Mini Linhas de Informação (Perfil) */
        .stat-mini {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .stat-mini:last-child { border-bottom: none; }
        .stat-mini-label { font-size: 14px; color: #64748b; font-weight: 500; }
        .stat-mini-value { font-size: 14px; color: #1e293b; font-weight: 600; }

        /* Botões de Ações Rápidas Modernos */
        .quick-action {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 10px;
            text-decoration: none !important;
            transition: all 0.2s;
        }
        .quick-action:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateX(3px);
        }
        .qa-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .qa-label { font-size: 14px; font-weight: 600; color: #1e293b; }
        .qa-desc { font-size: 11px; color: #64748b; margin: 0; }
        .qa-arrow { margin-left: auto; color: #94a3b8; font-size: 12px; }

        /* Títulos dos Cards */
        .recent-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .recent-card .card-header {
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px;
        }
        .recent-card .card-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
        }
    </style>

    {{-- HEADER --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mt-3 mb-3">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color: #1e293b;">Meu Painel</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent justify-content-end" style="padding: 0;">
                        <li class="breadcrumb-item"><a href="#" style="text-decoration:none;">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- ── GREETING ── --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dash-greeting-card shadow-sm p-4 rounded-3" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff; border-radius: 12px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="dash-hora mb-1" id="dash-hora" style="font-size: 14px; opacity: 0.8; font-weight: 500;"></p>
                                <h2 class="dash-nome mb-1" style="font-weight: 700; font-size: 26px;">
                                    @php
                                        $h = now()->format('H');
                                        $saudacao = $h < 12 ? 'Bom dia' : ($h < 18 ? 'Boa tarde' : 'Boa noite');
                                    @endphp
                                    {{ $saudacao }}, {{ explode(' ', $aluno->nome_aluno)[0] }}! 👋
                                </h2>
                                <p class="dash-sub mb-3" style="opacity: 0.7; font-size: 14px;">{{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                                <div class="dash-badge-prof d-inline-flex align-items-center" style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-graduation-cap me-2"></i> Aluno matriculado
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-flex justify-content-end">
                                @if($aluno->foto_aluno)
                                    <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                        style="width:110px;height:110px;object-fit:cover;border-radius:16px;border:3px solid rgba(255,255,255,.15);"
                                        alt="{{ $aluno->nome_aluno }}">
                                @else
                                    <div style="width:110px;height:110px;border-radius:16px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:700;color:#fff;">
                                        {{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── METRIC CARDS ── --}}
            <div class="row mb-4">
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="mc mc-blue">
                        <div class="mc-icon">📚</div>
                        <div class="mc-val">{{ $totalAulas ?? 0 }}</div>
                        <p class="mc-lbl">Minhas Aulas</p>
                        <div class="mc-trend">🎓 Aulas disponíveis</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="mc mc-green">
                        <div class="mc-icon">🌍</div>
                        <div class="mc-val">{{ $aluno->curso_aluno ?? '—' }}</div>
                        <p class="mc-lbl">Meu Curso</p>
                        <div class="mc-trend">📖 Em andamento</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="mc mc-blue">
                        <div class="mc-icon">⭐</div>
                        <div class="mc-val">{{ $aluno->nivel_aluno ?? '—' }}</div>
                        <p class="mc-lbl">Meu Nível</p>
                        <div class="mc-trend">🚀 Continue evoluindo</div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="metric-card-rose">
                        <div class="metric-label"><i class="fas fa-calendar-check me-1"></i> Hoje</div>
                        <div class="metric-value mb-2">{{ now()->format('d/m/Y') }}</div>
                        <div class="metric-trend">
                            @php
                                $hora = now('America/Sao_Paulo')->hour;
                                $icone = $hora >= 5 && $hora < 12 ? '🌅' : ($hora >= 12 && $hora < 18 ? '☀️' : '🌙');
                            @endphp
                            {{ $icone }} {{ now('America/Sao_Paulo')->format('H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CONTEÚDO: SALA VIRTUAL, PERFIL + AÇÕES ── --}}
            <div class="row">

                {{-- Coluna da Esquerda: Próxima Aula + Perfil --}}
                <div class="col-lg-8 mb-4">
                    
                    {{-- SEÇÃO NOVA: ACESSO ÀS AULAS AO VIVO --}}
                    <div class="card recent-card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-video me-2 text-danger"></i>Sua Próxima Aula Ao Vivo</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="p-3 mb-3 rounded" style="background-color: #f8fafc; border-left: 4px solid #4f46e5;">
                                <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 15px;">Conversação Prática & Vocabulário Avançado</h6>
                                <p class="text-muted small mb-0"><strong>Agendado para:</strong> Terça-feira, às 19:30 (Duração: 50min)</p>
                            </div>
                            
                            <p class="text-muted small mb-3">Clique no botão da plataforma combinada com o seu professor para abrir a sala virtual:</p>
                            
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <a href="https://teams.microsoft.com/" target="_blank" class="btn btn-outline-primary w-100 p-2.5 fw-bold d-flex align-items-center justify-content-center" style="border-color: #4b53bc; color: #4b53bc; gap: 8px; font-weight:600;">
                                        <i class="fab fa-windows"></i> Microsoft Teams
                                    </a>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <a href="https://meet.google.com/" target="_blank" class="btn btn-outline-success w-100 p-2.5 fw-bold d-flex align-items-center justify-content-center" style="border-color: #0f9d58; color: #0f9d58; gap: 8px; font-weight:600;">
                                        <i class="fab fa-google"></i> Google Meet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Perfil do aluno --}}
                    <div class="card recent-card">
                        <div class="card-header">
                            <h5><i class="fas fa-id-badge me-2 text-info"></i>Meu Perfil</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-user me-2 text-muted"></i>Nome</span>
                                <span class="stat-mini-value">{{ $aluno->nome_aluno }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-envelope me-2 text-muted"></i>E-mail</span>
                                <span class="stat-mini-value">{{ $aluno->email_aluno }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-phone me-2 text-muted"></i>Telefone</span>
                                <span class="stat-mini-value">{{ $aluno->telefone_aluno ?? '—' }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-cake-candles me-2 text-muted"></i>Nascimento</span>
                                <span class="stat-mini-value">{{ $aluno->data_nasc_aluno ?? '—' }}</span>
                            </div>
                            <div class="stat-mini">
                                <span class="stat-mini-label"><i class="fas fa-star me-2 text-muted"></i>Nível</span>
                                <span class="stat-mini-value">{{ $aluno->nivel_aluno ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Coluna da Direita: Ações Rápidas --}}
                <div class="col-lg-4 mb-4">
                    <div class="card recent-card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-bolt me-2 text-warning"></i>Ações Rápidas</h5>
                        </div>
                        <div class="card-body p-3">
                            
                            <a href="{{ url('/aluno/perfil') }}" class="quick-action">
                                <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;">
                                    <i class="fas fa-user-pen"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Meu Perfil</div>
                                    <p class="qa-desc">Ver e editar dados</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                            <a href="#" class="quick-action">
                                <div class="qa-icon" style="background:#ecfdf5;color:#059669;">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Minhas Aulas</div>
                                    <p class="qa-desc">Ver aulas disponíveis</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                            {{-- DISPARADOR DA MODAL DE REAGENDAMENTO --}}
                            <a href="#" class="quick-action" data-bs-toggle="modal" data-bs-target="#modalReagendamento">
                                <div class="qa-icon" style="background:#fffbeb;color:#d97706;">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Reagendar Aula</div>
                                    <p class="qa-desc">Solicitar novo horário</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                            <a href="#" class="quick-action"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="qa-icon" style="background:#fff0f0;color:#e53e3e;">
                                    <i class="fas fa-right-from-bracket"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Sair</div>
                                    <p class="qa-desc">Encerrar sessão</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                            <form id="logout-form" action="{{ url('/aluno/logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                    {{-- Card Informativo sobre Políticas da Escola --}}
                    <div class="p-3 rounded border-0 shadow-sm" style="background-color: #fffbeb; border: 1px solid #fef08a !important;">
                        <small class="fw-bold d-block mb-1" style="color: #b45309;"><i class="fas fa-circle-info me-1"></i> Regra de Reagendamento</small>
                        <p class="text-muted mb-0" style="font-size: 12px; line-height: 1.4;">
                            Caso precise alterar o horário da aula, envie a solicitação com no mínimo <strong>24h úteis de antecedência</strong>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL INTERATIVO: SOLICITAR REAGENDAMENTO --}}
    <div class="modal fade" id="modalReagendamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header text-white border-0" style="background: #4f46e5; border-top-left-radius: 16px; border-top-right-radius: 16px; padding: 16px 24px;">
                    <h5 class="modal-title fw-bold d-flex align-items-center" style="margin:0;"><i class="fas fa-calendar-plus me-2"></i>Solicitar Reagendamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1); opacity: 1; border: none; background: transparent; font-size: 20px;">&times;</button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-muted small mb-3">Selecione o dia e o melhor período. Validaremos a disponibilidade com o seu professor.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small mb-1">Data Pretendida</label>
                            <input type="date" class="form-control" name="data_sugerida" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small d-block mb-1">Período Ideal</label>
                            <div class="form-check form-check-inline me-3 d-inline-block">
                                <input class="form-check-input" type="radio" name="periodo" id="p_manha" value="MANHA" checked>
                                <label class="form-check-label small" for="p_manha">Manhã</label>
                            </div>
                            <div class="form-check form-check-inline me-3 d-inline-block">
                                <input class="form-check-input" type="radio" name="periodo" id="p_tarde" value="TARDE">
                                <label class="form-check-label small" for="p_tarde">Tarde</label>
                            </div>
                            <div class="form-check form-check-inline d-inline-block">
                                <input class="form-check-input" type="radio" name="periodo" id="p_noite" value="NOITE">
                                <label class="form-check-label small" for="p_noite">Noite</label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold small mb-1">Justificativa (Opcional)</label>
                            <textarea class="form-control" name="motivo" rows="2" placeholder="Ex: Ajuste de horário de trabalho..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 justify-content-end" style="padding: 0 24px 24px 24px;">
                        <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal" style="border-radius: 8px;">Voltar</button>
                        <button type="submit" class="btn text-white px-4" style="background: #4f46e5; border-radius: 8px;">Enviar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function atualizarHora() {
            const agora = new Date();
            const horas = String(agora.getHours()).padStart(2, '0');
            const min   = String(agora.getMinutes()).padStart(2, '0');
            const seg   = String(agora.getSeconds()).padStart(2, '0');
            const el = document.getElementById('dash-hora');
            if (el) el.textContent = horas + ':' + min + ':' + seg;
        }
        atualizarHora();
        setInterval(atualizarHora, 1000);
    </script>

@endsection