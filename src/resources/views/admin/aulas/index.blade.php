@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Aulas</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Aulas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3 alert-styled">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Metric Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-book-open-reader"></i></div>
                    <div class="mc-val">{{ $totalAulas }}</div>
                    <p class="mc-lbl">Total de Aulas</p>
                    <div class="mc-trend"><i class="fas fa-database me-1"></i> cadastradas</div>
                </div>
            </div>
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="mc-val">{{ $aulasAtivas }}</div>
                    <p class="mc-lbl">Aulas Ativas</p>
                    <div class="mc-trend"><i class="fas fa-signal me-1"></i> em andamento</div>
                </div>
            </div>
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-calendar-day"></i></div>
                    <div class="mc-val">{{ $aulasHoje }}</div>
                    <p class="mc-lbl">Aulas Hoje</p>
                    <div class="mc-trend"><i class="fas fa-clock me-1"></i> agendadas</div>
                </div>
            </div>
            <div class="col-6 col-xl-3 fade-up">
                <div class="mc mc-sky shadow-sm h-100">
                    <div class="mc-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="mc-val">{{ $totalCursos }}</div>
                    <p class="mc-lbl">Cursos</p>
                    <div class="mc-trend"><i class="fas fa-layer-group me-1"></i> disponíveis</div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-book-open text-primary"></i> Lista de Aulas</h6>
                <a href="{{ route('admin.aulas.create') }}" class="tbl-btn-novo">
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
                        stroke-linejoin="round">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table recent-table mb-0">
                    <thead>
                        <tr>
                            <th>Aula</th>
                            <th>Professor</th>
                            <th>Curso</th>
                            <th>Data & Hora</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aulas as $aula)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="tbl-icon-wrap">
                                        <i class="fas fa-chalkboard"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:.875rem;">{{ $aula->titulo_aulas }}</div>
                                        @if($aula->descricao_aulas)
                                        <div style="font-size:.7rem;color:#94a3b8;max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $aula->descricao_aulas }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($aula->professor)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="prof-avatar-placeholder" style="width:30px;height:30px;font-size:.65rem;border-radius:8px;">{{ strtoupper(substr($aula->professor->nome_professor, 0, 2)) }}</div>
                                    <span style="font-size:.84rem;">{{ $aula->professor->nome_professor }}</span>
                                </div>
                                @else
                                <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($aula->cursos_aulas)
                                <span class="tbl-badge">
                                    <i class="fas fa-language me-1"></i>{{ $aula->cursos_aulas }}
                                </span>
                                @else
                                <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($aula->data_aulas)
                                <div style="font-weight:600;font-size:.84rem;">
                                    <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                    {{ \Carbon\Carbon::parse($aula->data_aulas)->format('d/m/Y') }}
                                </div>
                                @endif
                                @if($aula->hora_aulas)
                                <div style="font-size:.72rem;color:#94a3b8;">
                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($aula->hora_aulas)->format('H:i') }}
                                </div>
                                @endif
                            </td>
                            <td>
                                @if ($aula->link_teams)
                                <a href="{{ $aula->link_teams }}" target="_blank" class="tbl-link-btn">
                                    <i class="fas fa-video me-1"></i> Teams
                                </a>
                                @else
                                <span style="color:#cbd5e1;font-size:.8rem;">Sem link</span>
                                @endif
                            </td>
                            <td>
                                @php
                                $statusClass = match(strtoupper($aula->status_aulas)) {
                                'ATIVO' => 'tbl-status-ativo',
                                'INATIVO' => 'tbl-status-inativo',
                                'CANCELADO' => 'tbl-status-cancelado',
                                default => 'tbl-status-inativo',
                                };
                                @endphp
                                <span class="tbl-status {{ $statusClass }}">
                                    <span class="tbl-status-dot"></span>
                                    {{ $aula->status_aulas }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.aulas.edit', $aula->id_aulas) }}"
                                        class="tbl-btn-editar" title="Editar">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25L13.5 1.25C13.9142 1.25 14.25 1.58579 14.25 2C14.25 2.41421 13.9142 2.75 13.5 2.75H12C9.62178 2.75 7.91356 2.75159 6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12V10.5C21.25 10.0858 21.5858 9.75 22 9.75C22.4142 9.75 22.75 10.0858 22.75 10.5V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM16.7705 2.27592C18.1384 0.908029 20.3562 0.908029 21.7241 2.27592C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27592ZM20.6634 3.33658C19.8813 2.55448 18.6133 2.55448 17.8312 3.33658L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33658ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z" fill="#ffffff" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.aulas.destroy', $aula->id_aulas) }}" method="POST" class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="tbl-btn-excluir"
                                            data-titulo="{{ $aula->titulo_aulas }}"
                                            onclick="abrirModalExcluir(this)">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.9999 2.75C11.0214 2.75 10.187 3.37503 9.87778 4.24993C9.73974 4.64047 9.31125 4.84517 8.92071 4.70713C8.53017 4.56909 8.32548 4.1406 8.46352 3.75007C8.97795 2.29459 10.366 1.25 11.9999 1.25C13.6339 1.25 15.0219 2.29459 15.5364 3.75007C15.6744 4.1406 15.4697 4.56909 15.0792 4.70713C14.6886 4.84517 14.2601 4.64047 14.1221 4.24993C13.8129 3.37503 12.9784 2.75 11.9999 2.75Z" fill="#ffffff" />
                                                <path d="M2.74991 6C2.74991 5.58579 3.08569 5.25 3.49991 5.25H20.5C20.9142 5.25 21.25 5.58579 21.25 6C21.25 6.41421 20.9142 6.75 20.5 6.75H3.49991C3.08569 6.75 2.74991 6.41421 2.74991 6Z" fill="#ffffff" />
                                                <path d="M5.91499 8.45011C5.88744 8.03681 5.53006 7.72411 5.11677 7.75166C4.70347 7.77921 4.39076 8.13659 4.41832 8.54989L4.88176 15.5016C4.96726 16.7844 5.03632 17.8205 5.19829 18.6336C5.36669 19.4789 5.65311 20.185 6.24471 20.7384C6.8363 21.2919 7.55985 21.5307 8.4145 21.6425C9.23654 21.75 10.275 21.75 11.5606 21.75H12.4394C13.725 21.75 14.7634 21.75 15.5855 21.6425C16.4401 21.5307 17.1637 21.2919 17.7553 20.7384C18.3469 20.185 18.6333 19.4789 18.8017 18.6336C18.9637 17.8205 19.0327 16.7844 19.1182 15.5016L19.5817 8.54989C19.6092 8.13659 19.2965 7.77921 18.8832 7.75166C18.4699 7.72411 18.1125 8.03681 18.085 8.45011L17.625 15.3492C17.5352 16.6971 17.4712 17.6349 17.3306 18.3405C17.1942 19.025 17.0039 19.3873 16.7305 19.6431C16.4571 19.8988 16.0829 20.0647 15.3909 20.1552C14.6775 20.2485 13.7375 20.25 12.3867 20.25H11.6133C10.2625 20.25 9.32246 20.2485 8.60906 20.1552C7.91705 20.0647 7.5429 19.8988 7.26948 19.6431C6.99607 19.3873 6.80574 19.025 6.66939 18.3405C6.52882 17.6349 6.46479 16.6971 6.37493 15.3492L5.91499 8.45011Z" fill="#ffffff" />
                                                <path d="M9.42537 10.2537C9.83753 10.2125 10.2051 10.5132 10.2463 10.9254L10.7463 15.9254C10.7875 16.3375 10.4868 16.7051 10.0746 16.7463C9.66247 16.7875 9.29493 16.4868 9.25372 16.0746L8.75372 11.0746C8.7125 10.6625 9.01321 10.2949 9.42537 10.2537Z" fill="#ffffff" />
                                                <path d="M15.2463 11.0746C15.2875 10.6625 14.9868 10.2949 14.5746 10.2537C14.1625 10.2125 13.7949 10.5132 13.7537 10.9254L13.2537 15.9254C13.2125 16.3375 13.5132 16.7051 13.9254 16.7463C14.3375 16.7875 14.7051 16.4868 14.7463 16.0746L15.2463 11.0746Z" fill="#ffffff" />
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
                                    <i class="fas fa-book-open tbl-empty-icon"></i>
                                    <span class="tbl-empty-text">Nenhuma aula cadastrada ainda</span>
                                    <a href="{{ route('admin.aulas.create') }}" class="tbl-empty-btn">
                                        <i class="fas fa-plus"></i> Cadastrar primeira aula
                                    </a>
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

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Aula', 'delDescricao' => 'Você está prestes a excluir a aula:'])

@endsection