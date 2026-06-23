@extends('admin.layout.admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Agendamentos</h3>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dash') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Agendamentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- CARDS --}}
        <div class="row g-3 mb-4">

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow">
                    <div class="mc-icon">
                        <i class="fas fa-calendar-days"></i>
                    </div>

                    <div class="mc-val">{{ $totalAgendamentos ?? 0 }}</div>
                    <p class="mc-lbl">Total</p>

                    <div class="mc-trend">
                        <i class="fas fa-database me-1"></i>
                        agendamentos
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow">
                    <div class="mc-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>

                    <div class="mc-val">{{ $agendamentosPendentes ?? 0 }}</div>
                    <p class="mc-lbl">Pendentes</p>

                    <div class="mc-trend">
                        <i class="fas fa-clock me-1"></i>
                        aguardando
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow">
                    <div class="mc-icon">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>

                    <div class="mc-val">{{ $reagendamentos ?? 0 }}</div>
                    <p class="mc-lbl">Reagendamentos</p>

                    <div class="mc-trend">
                        <i class="fas fa-rotate me-1"></i>
                        alterados
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow">
                    <div class="mc-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>

                    <div class="mc-val">{{ $agendamentosHoje ?? 0 }}</div>
                    <p class="mc-lbl">Hoje</p>

                    <div class="mc-trend">
                        <i class="fas fa-bell me-1"></i>
                        agendados
                    </div>
                </div>
            </div>

        </div>

        {{-- CALENDÁRIO --}}
        <div class="d-card fade-up mb-4">
            <div class="d-card-header">
                <h6>
                    <i class="fas fa-calendar text-primary"></i>
                    Calendário de Agendamentos
                </h6>

                <a href="{{ route('admin.agendas.create') }}" class="tbl-btn-novo">
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

            <div class="card-body p-3">
                <div id="calendar"></div>
            </div>
        </div>

        {{-- TABELA --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6>
                    <i class="fas fa-list-check text-primary"></i>
                    Lista de Agendamentos
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Professor</th>
                            <th>Título</th>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($agendas as $agenda)
                            @php
                                $dataFormatada = $agenda->data_evento_agenda
                                    ? \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('d/m/Y')
                                    : '—';

                                $horaInicioFormatada = $agenda->hora_inicio_agenda
                                    ? \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i')
                                    : '—';

                                $horaFimFormatada = $agenda->hora_fim_agenda
                                    ? \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i')
                                    : '—';

                                $status = strtolower($agenda->status_agenda ?? '');

                                $stClass = match(true) {
                                    str_contains($status, 'confirmado') => 'tbl-status-confirmado',
                                    str_contains($status, 'pendente') => 'tbl-status-pendente',
                                    str_contains($status, 'cancelado') => 'tbl-status-cancelado',
                                    str_contains($status, 'reagend') => 'tbl-status-congelado',
                                    default => 'tbl-status-pendente',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <span class="agenda-aluno-link"
                                        data-foto="{{ $agenda->aluno?->foto_aluno }}"
                                        data-nome="{{ $agenda->aluno?->nome_aluno ?? '—' }}"
                                        data-curso="{{ $agenda->aluno?->curso_aluno ?? '—' }}"
                                        data-nivel="{{ $agenda->aluno?->nivel_aluno ?? '—' }}"
                                        data-professor="{{ $agenda->professor?->nome_professor ?? '—' }}"
                                        data-titulo="{{ $agenda->titulo_agenda ?? '—' }}"
                                        data-data="{{ $dataFormatada }}"
                                        data-inicio="{{ $horaInicioFormatada }}"
                                        data-fim="{{ $horaFimFormatada }}"
                                        data-status="{{ $agenda->status_agenda ?? '—' }}"
                                        data-edit="{{ route('admin.agendas.edit', $agenda->id_agenda) }}"
                                        style="cursor:pointer;font-weight:600;color:#1e293b;border-bottom:1px dashed #94a3b8;">

                                        {{ $agenda->aluno?->nome_aluno ?? '—' }}
                                    </span>
                                </td>

                                <td>{{ $agenda->professor?->nome_professor ?? '—' }}</td>

                                <td>{{ $agenda->titulo_agenda ?? '—' }}</td>

                                <td>
                                    <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                    {{ $dataFormatada }}
                                </td>

                                <td>
                                    {{ $horaInicioFormatada }} - {{ $horaFimFormatada }}
                                </td>

                                <td>
                                    <span class="tbl-status {{ $stClass }}">
                                        <span class="tbl-status-dot"></span>
                                        {{ $agenda->status_agenda ?? '—' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">

                                        <button type="button"
                                            class="tbl-btn-editar"
                                            onclick="abrirModalAluno({
                                                foto: @js($agenda->aluno?->foto_aluno),
                                                nome: @js($agenda->aluno?->nome_aluno ?? '—'),
                                                curso: @js($agenda->aluno?->curso_aluno ?? '—'),
                                                nivel: @js($agenda->aluno?->nivel_aluno ?? '—'),
                                                professor: @js($agenda->professor?->nome_professor ?? '—'),
                                                titulo: @js($agenda->titulo_agenda ?? '—'),
                                                data: @js($dataFormatada),
                                                inicio: @js($horaInicioFormatada),
                                                fim: @js($horaFimFormatada),
                                                status: @js($agenda->status_agenda ?? '—'),
                                                editUrl: @js(route('admin.agendas.edit', $agenda->id_agenda))
                                            })">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="#ffffff"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z" fill="#ffffff"/>
</svg>
                                        </button>

                                        <a href="{{ route('admin.agendas.edit', $agenda->id_agenda) }}" class="tbl-btn-editar">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25L13.5 1.25C13.9142 1.25 14.25 1.58579 14.25 2C14.25 2.41421 13.9142 2.75 13.5 2.75H12C9.62178 2.75 7.91356 2.75159 6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12V10.5C21.25 10.0858 21.5858 9.75 22 9.75C22.4142 9.75 22.75 10.0858 22.75 10.5V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM16.7705 2.27592C18.1384 0.908029 20.3562 0.908029 21.7241 2.27592C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27592ZM20.6634 3.33658C19.8813 2.55448 18.6133 2.55448 17.8312 3.33658L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33658ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z" fill="#ffffff"/>
</svg>  
                                        </a>

                                        <form action="{{ route('admin.agendas.destroy', $agenda->id_agenda) }}"
                                            method="POST"
                                            class="d-inline form-delete">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                class="tbl-btn-excluir"
                                                data-titulo="{{ $agenda->titulo_agenda ?? 'este agendamento' }}"
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
                                <td colspan="7">
                                    <div class="tbl-empty">
                                        <i class="fas fa-calendar-days tbl-empty-icon"></i>
                                        <span class="tbl-empty-text">Nenhum agendamento</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- MODAL DETALHES ALUNO --}}
<div class="modal fade" id="modalDetalheAluno" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:20px;overflow:hidden;">

            <div id="modal-header-bg" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);padding:2rem;text-align:center;position:relative;">
                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    style="position:absolute;top:1rem;right:1rem;">
                </button>

                <img id="modal-foto"
                    src=""
                    alt="Foto do aluno"
                    style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);margin-bottom:.75rem;box-shadow:0 4px 20px rgba(0,0,0,.3);">

                <h5 id="modal-nome" style="color:#fff;font-weight:700;margin:0;font-size:1.1rem;"></h5>

                <div id="modal-curso" style="color:rgba(255,255,255,.6);font-size:.78rem;margin-top:.25rem;"></div>

                <div style="margin-top:.75rem;">
                    <span id="modal-status-badge"
                        style="font-size:.7rem;font-weight:700;padding:.3rem .85rem;border-radius:50px;letter-spacing:.05em;">
                    </span>
                </div>
            </div>

            <div class="modal-body p-0">
                <div style="padding:1.5rem;">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1rem;">

                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">
                                Data
                            </div>

                            <div id="modal-data" style="font-size:.95rem;font-weight:700;color:#1e293b;"></div>
                        </div>

                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">
                                Horário
                            </div>

                            <div style="font-size:.95rem;font-weight:700;color:#1e293b;">
                                <span id="modal-inicio"></span>
                                <span style="color:#94a3b8;">→</span>
                                <span id="modal-fim"></span>
                            </div>
                        </div>

                    </div>

                    <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;margin-bottom:.75rem;">
                        <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">
                            Aula
                        </div>

                        <div id="modal-titulo" style="font-size:.9rem;font-weight:600;color:#1e293b;"></div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">

                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">
                                Professor
                            </div>

                            <div id="modal-professor" style="font-size:.85rem;font-weight:600;color:#1e293b;"></div>
                        </div>

                        <div style="background:#f8fafc;border-radius:12px;padding:.9rem 1rem;">
                            <div style="font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.3rem;">
                                Nível
                            </div>

                            <div id="modal-nivel" style="font-size:.85rem;font-weight:600;color:#1e293b;"></div>
                        </div>

                    </div>
                </div>

                <div style="padding:0 1.5rem 1.5rem;display:flex;gap:.6rem;">
                    <a id="modal-edit-btn"
                        href="#"
                        class="btn btn-sm"
                        style="flex:1;background:linear-gradient(135deg,#1a1a2e,#0f3460);color:#fff;border:none;border-radius:10px;padding:.6rem;">

                        <i class="fas fa-pen me-1"></i>
                        Editar
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-light"
                        data-bs-dismiss="modal"
                        style="border-radius:10px;padding:.6rem 1rem;">

                        Fechar
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@include('admin.partials.modal-delete', [
    'delTitulo' => 'Excluir Agendamento',
    'delDescricao' => 'Você está prestes a excluir o agendamento:'
])

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/pt-br.global.min.js"></script>

<script>
function valorSeguro(valor, padrao = '—') {
    if (valor === null || valor === undefined || valor === '') {
        return padrao;
    }

    return valor;
}

function configStatus(status) {
    const st = (status || '').toLowerCase();

    if (st.includes('confirmado')) {
        return {
            cor: '#22c55e',
            bg: '#f0fdf4',
            label: 'Confirmado'
        };
    }

    if (st.includes('pendente')) {
        return {
            cor: '#f59e0b',
            bg: '#fffbeb',
            label: 'Pendente'
        };
    }

    if (st.includes('cancelado')) {
        return {
            cor: '#ef4444',
            bg: '#fff1f2',
            label: 'Cancelado'
        };
    }

    if (st.includes('reagend')) {
        return {
            cor: '#6366f1',
            bg: '#eef3ff',
            label: 'Reagendamento'
        };
    }

    return {
        cor: '#94a3b8',
        bg: '#f8fafc',
        label: status || '—'
    };
}

function abrirModalAluno(d) {
    const fotoBase = '{{ asset("traducaidiomas/alunos/") }}/';

    const nome = valorSeguro(d.nome);
    const curso = valorSeguro(d.curso);
    const nivel = valorSeguro(d.nivel);
    const titulo = valorSeguro(d.titulo);
    const data = valorSeguro(d.data);
    const inicio = valorSeguro(d.inicio);
    const fim = valorSeguro(d.fim);
    const professor = valorSeguro(d.professor);
    const status = valorSeguro(d.status);

    const fotoDefault = 'https://ui-avatars.com/api/?name='
        + encodeURIComponent(nome)
        + '&background=1a1a2e&color=fff&size=90';

    document.getElementById('modal-foto').src = d.foto ? fotoBase + d.foto : fotoDefault;
    document.getElementById('modal-foto').alt = 'Foto de ' + nome;

    document.getElementById('modal-nome').textContent = nome;
    document.getElementById('modal-curso').textContent = curso + (nivel && nivel !== '—' ? ' · ' + nivel : '');
    document.getElementById('modal-titulo').textContent = titulo;
    document.getElementById('modal-data').textContent = data;
    document.getElementById('modal-inicio').textContent = inicio;
    document.getElementById('modal-fim').textContent = fim;
    document.getElementById('modal-professor').textContent = professor;
    document.getElementById('modal-nivel').textContent = nivel;

    const editBtn = document.getElementById('modal-edit-btn');

    if (d.editUrl) {
        editBtn.href = d.editUrl;
        editBtn.style.pointerEvents = 'auto';
        editBtn.style.opacity = '1';
    } else {
        editBtn.href = '#';
        editBtn.style.pointerEvents = 'none';
        editBtn.style.opacity = '.6';
    }

    const cfg = configStatus(status);
    const badge = document.getElementById('modal-status-badge');

    badge.textContent = cfg.label;
    badge.style.background = cfg.bg;
    badge.style.color = cfg.cor;
    badge.style.border = '1.5px solid ' + cfg.cor + '40';

    new bootstrap.Modal(document.getElementById('modalDetalheAluno')).show();
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.agenda-aluno-link').forEach(function(el) {
        el.addEventListener('click', function() {
            abrirModalAluno({
                foto: this.dataset.foto,
                nome: this.dataset.nome,
                curso: this.dataset.curso,
                nivel: this.dataset.nivel,
                professor: this.dataset.professor,
                titulo: this.dataset.titulo,
                data: this.dataset.data,
                inicio: this.dataset.inicio,
                fim: this.dataset.fim,
                status: this.dataset.status,
                editUrl: this.dataset.edit,
            });
        });
    });

    const calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        return;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        height: 'auto',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },

        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            list: 'Lista'
        },

        noEventsContent: 'Nenhum agendamento encontrado',

        events: @json(route('admin.agendas.eventos')),

        eventClick: function(info) {
            info.jsEvent.preventDefault();

            const p = info.event.extendedProps;

            abrirModalAluno({
                foto: p.foto,
                nome: p.aluno,
                curso: p.curso,
                nivel: p.nivel,
                professor: p.professor,
                titulo: p.titulo || info.event.title,
                data: p.data,
                inicio: p.inicio,
                fim: p.fim,
                status: p.status,
                editUrl: p.editUrl,
            });
        },
    });

    calendar.render();
});
</script>

@endsection