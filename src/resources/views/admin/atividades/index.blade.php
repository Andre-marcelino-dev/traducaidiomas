@extends('admin.layout.admin')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6"><h3 class="mb-0 fw-bold">Atividades</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dash') }}">Home</a></li>
                    <li class="breadcrumb-item active">Atividades</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-card fade-up">
            <div class="d-card-header">
                <h6><i class="fas fa-clipboard-list text-primary"></i> Lista de Atividades</h6>
                <a href="{{ route('admin.atividades.create') }}" class="tbl-btn-novo">
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
  stroke="#FFFFFF"
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
                            <th>Título</th>
                            <th>Curso</th>
                            <th>Entrega</th>
                            <th>Respostas</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atividades as $atividade)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-weight:600;font-size:.875rem;">{{ $atividade->titulo_atividade }}</span>
                                </div>
                            </td>
                            <td><span class="tbl-badge">{{ $atividade->curso?->nome_curso ?? '—' }}</span></td>
                            <td>
                                <div style="font-weight:600;font-size:.84rem;">
                                    <i class="fas fa-calendar-alt me-1" style="color:#6366f1;font-size:.72rem;"></i>
                                    {{ \Carbon\Carbon::parse($atividade->data_entrega)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td><span class="tbl-badge blue">{{ $atividade->respostas->count() }} enviadas</span></td>
                            <td>
                                @php
                                    $st = strtolower($atividade->status_atividade ?? '');
                                    $stClass = match(true) {
                                        str_contains($st, 'ativ') || str_contains($st, 'abert') => 'tbl-status-ativo',
                                        str_contains($st, 'inativ') || str_contains($st, 'fechad') => 'tbl-status-inativo',
                                        str_contains($st, 'cancel') => 'tbl-status-cancelado',
                                        str_contains($st, 'conclu') => 'tbl-status-concluido',
                                        default => 'tbl-status-ativo',
                                    };
                                @endphp
                                <span class="tbl-status {{ $stClass }}">
                                    <span class="tbl-status-dot"></span>
                                    {{ $atividade->status_atividade }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('admin.atividades.show', $atividade->id_atividade) }}" class="tbl-btn-ver">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.25C9.92893 8.25 8.25 9.92893 8.25 12C8.25 14.0711 9.92893 15.75 12 15.75C14.0711 15.75 15.75 14.0711 15.75 12C15.75 9.92893 14.0711 8.25 12 8.25ZM9.75 12C9.75 10.7574 10.7574 9.75 12 9.75C13.2426 9.75 14.25 10.7574 14.25 12C14.25 13.2426 13.2426 14.25 12 14.25C10.7574 14.25 9.75 13.2426 9.75 12Z" fill="#FFFFFF"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25C7.48587 3.25 4.44529 5.9542 2.68057 8.24686L2.64874 8.2882C2.24964 8.80653 1.88206 9.28392 1.63269 9.8484C1.36564 10.4529 1.25 11.1117 1.25 12C1.25 12.8883 1.36564 13.5471 1.63269 14.1516C1.88206 14.7161 2.24964 15.1935 2.64875 15.7118L2.68057 15.7531C4.44529 18.0458 7.48587 20.75 12 20.75C16.5141 20.75 19.5547 18.0458 21.3194 15.7531L21.3512 15.7118C21.7504 15.1935 22.1179 14.7161 22.3673 14.1516C22.6344 13.5471 22.75 12.8883 22.75 12C22.75 11.1117 22.6344 10.4529 22.3673 9.8484C22.1179 9.28391 21.7504 8.80652 21.3512 8.28818L21.3194 8.24686C19.5547 5.9542 16.5141 3.25 12 3.25ZM3.86922 9.1618C5.49864 7.04492 8.15036 4.75 12 4.75C15.8496 4.75 18.5014 7.04492 20.1308 9.1618C20.5694 9.73159 20.8263 10.0721 20.9952 10.4545C21.1532 10.812 21.25 11.2489 21.25 12C21.25 12.7511 21.1532 13.188 20.9952 13.5455C20.8263 13.9279 20.5694 14.2684 20.1308 14.8382C18.5014 16.9551 15.8496 19.25 12 19.25C8.15036 19.25 5.49864 16.9551 3.86922 14.8382C3.43064 14.2684 3.17374 13.9279 3.00476 13.5455C2.84684 13.188 2.75 12.7511 2.75 12C2.75 11.2489 2.84684 10.812 3.00476 10.4545C3.17374 10.0721 3.43063 9.73159 3.86922 9.1618Z" fill="#FFFFFF"/>
</svg>
                                    </a>
                                    <form action="{{ route('admin.atividades.destroy', $atividade->id_atividade) }}" method="POST" class="d-inline form-delete">
                                        @csrf @method('DELETE')
                                        <button type="button" class="tbl-btn-excluir"
                                            data-titulo="{{ $atividade->titulo_atividade }}"
                                            onclick="abrirModalExcluir(this)">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M10.3093 2.24996H13.6907C13.9071 2.24982 14.0956 2.2497 14.2736 2.27813C14.9769 2.39043 15.5855 2.82909 15.9145 3.46078C15.9978 3.62067 16.0573 3.79955 16.1256 4.00488L16.2372 4.33978C16.2561 4.39647 16.2615 4.41252 16.266 4.42516C16.4412 4.90927 16.8952 5.23653 17.4098 5.24958C17.4234 5.24992 17.4399 5.24998 17.5 5.24998H20.5C20.9142 5.24998 21.25 5.58576 21.25 5.99998C21.25 6.41419 20.9142 6.74998 20.5 6.74998H3.49991C3.08569 6.74998 2.74991 6.41419 2.74991 5.99998C2.74991 5.58576 3.08569 5.24998 3.49991 5.24998H6.49999C6.56004 5.24998 6.57661 5.24992 6.59014 5.24958C7.10479 5.23653 7.55881 4.90929 7.73393 4.42518C7.73854 4.41245 7.74383 4.39675 7.76282 4.33978L7.87443 4.0049C7.94272 3.79958 8.00223 3.62067 8.08549 3.46078C8.41444 2.82909 9.02304 2.39043 9.72634 2.27813C9.90436 2.2497 10.0929 2.24982 10.3093 2.24996ZM9.00806 5.24998C9.05957 5.14895 9.10521 5.04398 9.14448 4.93542C9.15641 4.90245 9.1681 4.86736 9.18313 4.82228L9.28293 4.52286C9.3741 4.24935 9.39509 4.19357 9.41592 4.15358C9.52557 3.94301 9.72843 3.7968 9.96287 3.75936C10.0074 3.75225 10.0669 3.74998 10.3553 3.74998H13.6447C13.933 3.74998 13.9926 3.75225 14.0371 3.75936C14.2716 3.7968 14.4744 3.94301 14.5841 4.15358C14.6049 4.19357 14.6259 4.24934 14.7171 4.52286L14.8168 4.8221L14.8555 4.93544C14.8948 5.04399 14.9404 5.14896 14.9919 5.24998H9.00806Z" fill="#FFFFFF"/>
<path d="M5.915 8.45009C5.88744 8.03679 5.53007 7.72409 5.11677 7.75164C4.70347 7.77919 4.39077 8.13657 4.41832 8.54987L4.88177 15.5016C4.96726 16.7843 5.03633 17.8205 5.1983 18.6336C5.3667 19.4789 5.65312 20.1849 6.24471 20.7384C6.83631 21.2919 7.55985 21.5307 8.41451 21.6425C9.23653 21.75 10.275 21.75 11.5605 21.75H12.4394C13.725 21.75 14.7635 21.75 15.5855 21.6425C16.4401 21.5307 17.1637 21.2919 17.7553 20.7384C18.3469 20.1849 18.6333 19.4789 18.8017 18.6336C18.9637 17.8205 19.0327 16.7844 19.1182 15.5016L19.5817 8.54987C19.6092 8.13657 19.2965 7.77919 18.8832 7.75164C18.4699 7.72409 18.1125 8.03679 18.085 8.45009L17.625 15.3492C17.5352 16.6971 17.4712 17.6349 17.3306 18.3405C17.1942 19.0249 17.0039 19.3872 16.7305 19.643C16.4571 19.8988 16.0829 20.0646 15.3909 20.1552C14.6775 20.2485 13.7375 20.25 12.3867 20.25H11.6133C10.2625 20.25 9.32246 20.2485 8.60906 20.1552C7.91706 20.0646 7.5429 19.8988 7.26949 19.643C6.99607 19.3872 6.80574 19.0249 6.66939 18.3405C6.52882 17.6349 6.4648 16.6971 6.37494 15.3492L5.915 8.45009Z" fill="#FFFFFF"/>
<path d="M9.42537 10.2537C9.83753 10.2125 10.2051 10.5132 10.2463 10.9253L10.7463 15.9253C10.7875 16.3375 10.4868 16.705 10.0746 16.7463C9.66247 16.7875 9.29494 16.4868 9.25372 16.0746L8.75372 11.0746C8.71251 10.6624 9.01321 10.2949 9.42537 10.2537Z" fill="#FFFFFF"/>
<path d="M14.5746 10.2537C14.9868 10.2949 15.2875 10.6624 15.2463 11.0746L14.7463 16.0746C14.7051 16.4868 14.3375 16.7875 13.9254 16.7463C13.5132 16.705 13.2125 16.3375 13.2537 15.9253L13.7537 10.9253C13.7949 10.5132 14.1625 10.2125 14.5746 10.2537Z" fill="#FFFFFF"/>
</svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="tbl-empty">
                                    <i class="fas fa-clipboard-list tbl-empty-icon"></i>
                                    <span class="tbl-empty-text">Nenhuma atividade cadastrada.</span>
                                    <a href="{{ route('admin.atividades.create') }}" class="tbl-empty-btn">
                                        <i class="fas fa-plus"></i> Cadastrar atividade
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

@include('admin.partials.modal-delete', ['delTitulo' => 'Excluir Atividade', 'delDescricao' => 'Você está prestes a excluir a atividade:'])

@endsection
