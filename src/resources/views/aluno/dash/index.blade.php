@extends('aluno.layout.aluno')

@section('content')

    <style>
        .mc {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s;
        }

        .mc:hover {
            transform: translateY(-2px);
        }

        .mc-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .mc-val {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .mc-lbl {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .mc-trend {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 20px;
            display: inline-block;
        }

        .mc-blue .mc-trend {
            background: #eff6ff;
            color: #2563eb;
        }

        .mc-green .mc-trend {
            background: #ecfdf5;
            color: #059669;
        }

        .metric-card-rose {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .metric-value {
            font-size: 20px;
            font-weight: 700;
            color: #e11d48;
        }

        .metric-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .metric-trend {
            font-size: 12px;
            color: #475569;
            font-weight: 500;
        }

        .stat-mini {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .stat-mini:last-child {
            border-bottom: none;
        }

        .stat-mini-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }

        .stat-mini-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
        }

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

        .qa-label {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .qa-desc {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }

        .qa-arrow {
            margin-left: auto;
            color: #94a3b8;
            font-size: 12px;
        }

        .recent-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reagend-item:last-child {
            border-bottom: none !important;
        }

        .reagend-item:hover {
            background: #f8fafc;
            transition: background 0.2s;
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

            {{-- Alertas --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- GREETING --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dash-greeting-card shadow-sm p-4 rounded-3"
                        style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff; border-radius: 12px;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="dash-hora mb-1" id="dash-hora"
                                    style="font-size: 14px; opacity: 0.8; font-weight: 500;"></p>
                                <h2 class="dash-nome mb-1" style="font-weight: 700; font-size: 26px;">
                                    @php
                                        $h = now()->format('H');
                                        $saudacao = $h < 12 ? 'Bom dia' : ($h < 18 ? 'Boa tarde' : 'Boa noite');
                                    @endphp
                                    {{ $saudacao }}, {{ explode(' ', $aluno->nome_aluno)[0] }}! 👋
                                </h2>
                                <p class="dash-sub mb-3" style="opacity: 0.7; font-size: 14px;">
                                    {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                                <div class="dash-badge-prof d-inline-flex align-items-center"
                                    style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    <i class="fas fa-graduation-cap me-2"></i> Aluno matriculado
                                </div>
                            </div>
                            <div class="col-md-4 d-none d-md-flex justify-content-end">
                                @if ($aluno->foto_aluno)
                                    <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                        style="width:110px;height:110px;object-fit:cover;border-radius:16px;border:3px solid rgba(255,255,255,.15);"
                                        alt="{{ $aluno->nome_aluno }}">
                                @else
                                    <div
                                        style="width:110px;height:110px;border-radius:16px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:700;color:#fff;">
                                        {{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- METRIC CARDS --}}
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

            {{-- CONTEÚDO PRINCIPAL --}}
            <div class="row">

                {{-- Coluna Esquerda --}}
                <div class="col-lg-8 mb-4">

                    {{-- Próxima Aula --}}
                    <div class="card recent-card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-video me-2 text-danger"></i>Sua Próxima Aula Ao Vivo</h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="p-3 mb-3 rounded"
                                style="background-color: #f8fafc; border-left: 4px solid #4f46e5;">
                                <h6 class="fw-bold mb-1" style="color: #1e293b; font-size: 15px;">Conversação Prática &
                                    Vocabulário Avançado</h6>
                                <p class="text-muted small mb-0"><strong>Agendado para:</strong> Terça-feira, às 19:30
                                    (Duração: 50min)</p>
                            </div>
                            <p class="text-muted small mb-3">Clique no botão da plataforma combinada com o seu professor
                                para abrir a sala virtual:</p>
                            <div class="row">
                                <div class="col-sm-6 mb-2">
                                    <a href="https://teams.microsoft.com/" target="_blank"
                                        class="btn btn-outline-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                                        <i class="fab fa-windows"></i> Microsoft Teams
                                    </a>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <a href="https://meet.google.com/" target="_blank"
                                        class="btn btn-outline-success w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                                        <i class="fab fa-google"></i> Google Meet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reagendamentos --}}
                    @if ($reagendamentos->isNotEmpty())
                        <div class="card recent-card mb-4">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2 text-warning"></i>Meus Reagendamentos
                                </h5>
                                <span class="badge bg-warning text-dark">{{ $reagendamentos->count() }}</span>
                            </div>
                            <div class="card-body p-0">
                                @foreach ($reagendamentos as $r)
                                    <div class="reagend-item d-flex align-items-center gap-3 px-3 py-3"
                                        style="border-bottom: 1px solid #f1f5f9; animation: fadeSlideIn 0.4s ease both; animation-delay: {{ $loop->index * 0.1 }}s;">

                                        <div
                                            style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                            background: {{ $r->status === 'confirmado' ? '#ecfdf5' : '#fffbeb' }};">
                                            <i class="fas {{ $r->status === 'confirmado' ? 'fa-check-circle' : 'fa-clock' }}"
                                                style="font-size:1.2rem; color: {{ $r->status === 'confirmado' ? '#059669' : '#d97706' }};"></i>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="fw-semibold" style="font-size:0.9rem;color:#1e293b;">
                                                {{ $r->aula?->titulo_aulas ?? 'Aula não encontrada' }}
                                            </div>
                                            <div class="text-muted" style="font-size:0.78rem;">
                                                @if ($r->status === 'confirmado')
                                                    <i class="fas fa-calendar-check me-1 text-success"></i>
                                                    Nova data:
                                                    @if ($r->data_nova)
                                                        {{ \Carbon\Carbon::parse($r->data_nova)->format('d/m/Y \à\s H:i') }}
                                                    @elseif ($r->aula?->data_aulas)
                                                        {{ \Carbon\Carbon::parse($r->aula->data_aulas)->format('d/m/Y') }}
                                                        às {{ \Carbon\Carbon::parse($r->aula->hora_aulas)->format('H:i') }}
                                                    @else
                                                        Data a confirmar
                                                    @endif
                                                @else
                                                    <i class="fas fa-hourglass-half me-1 text-warning"></i>
                                                    Aguardando confirmação do professor
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size:0.75rem;">
                                                Motivo: {{ Str::limit($r->motivo, 50) }}
                                            </div>
                                        </div>

                                        <div>
                                            @if ($r->status === 'confirmado')
                                                <span class="badge"
                                                    style="background:#ecfdf5;color:#059669;border-radius:20px;font-size:0.75rem;padding:5px 10px;">
                                                    ✓ Confirmado
                                                </span>
                                            @else
                                                <span class="badge"
                                                    style="background:#fffbeb;color:#d97706;border-radius:20px;font-size:0.75rem;padding:5px 10px;">
                                                    ⏳ Pendente
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Perfil --}}

                </div>

                {{-- Coluna Direita --}}
                <div class="col-lg-4 mb-4">
                    <div class="card recent-card mb-3">
                        <div class="card-header">
                            <h5><i class="fas fa-bolt me-2 text-warning"></i>Ações Rápidas</h5>
                        </div>
                        <div class="card-body p-3">

                            <a href="#" class="quick-action" onclick="togglePerfil(event)">
                                <div class="qa-icon" style="background:#eef3ff;color:#4f46e5;">
                                    <i class="fas fa-user-pen"></i>
                                </div>
                                <div>
                                    <div class="qa-label">Meu Perfil</div>
                                    <p class="qa-desc">Ver e editar dados</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow" id="iconePerfil"></i>
                            </a>

                            {{-- Painel expansível do perfil --}}
                            <div id="painelPerfil" style="display:none; overflow:hidden; transition: all 0.3s ease;">
                                <div class="p-3 rounded mt-1 mb-2"
                                    style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">

                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        @if ($aluno->foto_aluno)
                                            <img src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                                style="width:56px;height:56px;object-fit:cover;border-radius:12px;border:2px solid #e2e8f0;">
                                        @else
                                            <div
                                                style="width:56px;height:56px;border-radius:12px;background:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;color:#fff;">
                                                {{ strtoupper(substr($aluno->nome_aluno, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold" style="color:#1e293b;font-size:0.9rem;">
                                                {{ $aluno->nome_aluno }}</div>
                                            <div class="text-muted" style="font-size:0.75rem;">{{ $aluno->email_aluno }}
                                            </div>
                                        </div>
                                    </div>

                                    <div style="font-size:0.8rem;">
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted"><i class="fas fa-phone me-1"></i>Telefone</span>
                                            <span class="fw-semibold">{{ $aluno->telefone_aluno ?? '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted"><i class="fas fa-book me-1"></i>Curso</span>
                                            <span class="fw-semibold">{{ $aluno->curso_aluno ?? '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-muted"><i class="fas fa-star me-1"></i>Nível</span>
                                            <span class="fw-semibold">{{ $aluno->nivel_aluno ?? '—' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1">
                                            <span class="text-muted"><i
                                                    class="fas fa-cake-candles me-1"></i>Nascimento</span>
                                            <span class="fw-semibold">{{ $aluno->data_nasc_aluno ?? '—' }}</span>
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                            <a href="#" class="quick-action">
                                <div class="qa-icon" style="background:#ecfdf5;color:#059669;">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div>
                                    <div class="qa-label">{{ $aluno->curso_aluno }}</div>
                                    <p class="qa-desc">Ver aulas disponíveis</p>
                                </div>
                                <i class="fas fa-chevron-right qa-arrow"></i>
                            </a>

                            <a href="#" class="quick-action" data-bs-toggle="modal"
                                data-bs-target="#modalReagendamento">
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

                    {{-- Aviso de política --}}
                    <div class="p-3 rounded shadow-sm" style="background-color: #fffbeb; border: 1px solid #fef08a;">
                        <small class="fw-bold d-block mb-1" style="color: #b45309;">
                            <i class="fas fa-circle-info me-1"></i> Regra de Reagendamento
                        </small>
                        <p class="text-muted mb-0" style="font-size: 12px; line-height: 1.4;">
                            Caso precise alterar o horário da aula, envie a solicitação com no mínimo
                            <strong>24h úteis de antecedência</strong>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL DE REAGENDAMENTO --}}
    <div class="modal fade" id="modalReagendamento" tabindex="-1" aria-labelledby="modalReagendamentoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15);">

                <div class="modal-header border-0 pb-0"
                    style="background:linear-gradient(135deg,#fffbeb,#fef3c7); border-radius:16px 16px 0 0; padding:1.5rem 1.5rem 1rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width:48px;height:48px;background:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-calendar-alt text-white fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="modalReagendamentoLabel" style="color:#92400e;">
                                Solicitar Reagendamento
                            </h5>
                            <small style="color:#b45309;">Envie sua solicitação ao professor</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <form action="{{ route('aluno.reagendamento.solicitar') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">

                        {{-- Seleção de Aula --}}
                        <div class="mb-3">
                            <label for="aula_id" class="form-label fw-semibold text-secondary"
                                style="font-size:.85rem;">
                                <i class="fas fa-book-open me-1 text-warning"></i> Aula
                            </label>
                            <select name="aula_id" id="aula_id"
                                class="form-select @error('aula_id') is-invalid @enderror"
                                style="border-radius:10px;border-color:#e5e7eb;">
                                <option value="">Selecione a aula...</option>
                                @isset($aulas)
                                    @foreach ($aulas as $aula)
                                        <option value="{{ $aula->id_aulas }}"
                                            {{ old('aula_id') == $aula->id_aulas ? 'selected' : '' }}>
                                            {{ $aula->titulo_aulas }} —
                                            {{ $aula->data_aulas ? \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') : 'Sem data' }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('aula_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Motivo --}}
                        <div class="mb-2">
                            <label for="motivo" class="form-label fw-semibold text-secondary"
                                style="font-size:.85rem;">
                                <i class="fas fa-comment-alt me-1 text-warning"></i> Motivo
                            </label>
                            <textarea name="motivo" id="motivo" rows="3" class="form-control @error('motivo') is-invalid @enderror"
                                style="border-radius:10px;border-color:#e5e7eb;resize:none;" placeholder="Explique o motivo do reagendamento..."
                                maxlength="500">{{ old('motivo') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                @error('motivo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @else
                                    <small class="text-muted">Mínimo 10 caracteres</small>
                                @enderror
                                <small class="text-muted" id="motivoCount">0/500</small>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius:10px;border:1px solid #e5e7eb;">
                            Cancelar
                        </button>
                        <button type="submit" class="btn px-4 text-white fw-semibold"
                            style="background:#d97706;border-radius:10px;border:none;">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Solicitação
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}

    {{-- SCRIPTS --}}
    {{-- SCRIPTS --}}
    <script>
        function atualizarHora() {
            const agora = new Date();
            const horas = String(agora.getHours()).padStart(2, '0');
            const min = String(agora.getMinutes()).padStart(2, '0');
            const seg = String(agora.getSeconds()).padStart(2, '0');
            const el = document.getElementById('dash-hora');
            if (el) el.textContent = horas + ':' + min + ':' + seg;
        }
        atualizarHora();
        setInterval(atualizarHora, 1000);

        function togglePerfil(e) {
            e.preventDefault();
            const painel = document.getElementById('painelPerfil');
            const icone = document.getElementById('iconePerfil');
            if (painel.style.display === 'none') {
                painel.style.display = 'block';
                icone.style.transform = 'rotate(90deg)';
                icone.style.transition = 'transform 0.2s';
            } else {
                painel.style.display = 'none';
                icone.style.transform = 'rotate(0deg)';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const motivo = document.getElementById('motivo');
            const count = document.getElementById('motivoCount');
            if (motivo && count) {
                const update = () => count.textContent = motivo.value.length + '/500';
                motivo.addEventListener('input', update);
                update();
            }

            @if ($errors->any())
                const modal = new bootstrap.Modal(document.getElementById('modalReagendamento'));
                modal.show();
            @endif
        });
    </script>



@endsection
