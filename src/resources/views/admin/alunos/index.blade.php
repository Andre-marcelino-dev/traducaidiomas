@extends('admin.layout.admin')

@section('content')


<style>
    .btn-add-aluno {
        width: 42px;
        height: 38px;
        min-width: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #10b981;
        text-decoration: none;
        box-shadow: 0 8px 18px rgba(16, 185, 129, .25);
        transition: .2s ease;
    }

    .btn-add-aluno:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    #pesquisarAlunoIndex {
        height: 38px;
        font-size: .875rem;
    }

    .input-group-text {
        border-color: #dbe3ef;
    }

    #pesquisarAlunoIndex:focus {
        box-shadow: none;
        border-color: #10b981;
    }
</style>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Alunos</h3>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dash') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Alunos</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if (session('success'))
        <div class="alert alert-success alert-styled alert-dismissible fade show mb-3">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- METRIC CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-blue shadow">
                    <div class="mc-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="mc-val">{{ $alunos->count() }}</div>
                    <p class="mc-lbl">Total de Alunos</p>
                    <div class="mc-trend">
                        <i class="fas fa-arrow-trend-up me-1"></i>cadastrados
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-green shadow">
                    <div class="mc-icon">
                        <i class="fas fa-circle-check"></i>
                    </div>
                    <div class="mc-val">{{ $alunos->where('status_aluno', 'EM CURSO')->count() }}</div>
                    <p class="mc-lbl">Em Curso</p>
                    <div class="mc-trend">
                        <i class="fas fa-book-open me-1"></i>estudando
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-amber shadow">
                    <div class="mc-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="mc-val">{{ $alunos->where('status_aluno', 'CONCLUIDO')->count() }}</div>
                    <p class="mc-lbl">Concluídos</p>
                    <div class="mc-trend">
                        <i class="fas fa-medal me-1"></i>curso finalizado
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3 fade-up">
                <div class="mc mc-rose shadow">
                    <div class="mc-icon">
                        <i class="fas fa-user-xmark"></i>
                    </div>
                    <div class="mc-val">{{ $alunos->where('status_aluno', 'INATIVO')->count() }}</div>
                    <p class="mc-lbl">Inativos</p>
                    <div class="mc-trend">
                        <i class="fas fa-clock me-1"></i>sem atividade
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="d-card-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <h6 class="mb-0">
                Lista de Alunos
            </h6>

            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 ms-lg-auto w-100" style="max-width: 680px;">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#64748b"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </span>

                    <input
                        type="text"
                        id="pesquisarAlunoIndex"
                        class="form-control"
                        placeholder="Pesquisar por nome, e-mail, telefone, curso, nível ou status..."
                        autocomplete="off">
                </div>

                <a href="{{ route('admin.alunos.create') }}" class="btn-add-aluno" title="Cadastrar aluno">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="22"
                        height="22"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="#ffffff"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table recent-table mb-0">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Telefone</th>
                        <th>Curso</th>
                        <th>Nível</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody id="listaAlunosTabela">
                    @forelse($alunos as $aluno)
                    <tr
                        class="aluno-row"
                        data-nome="{{ mb_strtolower($aluno->nome_aluno ?? '') }}"
                        data-email="{{ mb_strtolower($aluno->email_aluno ?? '') }}"
                        data-telefone="{{ mb_strtolower($aluno->telefone_aluno ?? '') }}"
                        data-curso="{{ mb_strtolower($aluno->curso_aluno ?? '') }}"
                        data-nivel="{{ mb_strtolower($aluno->nivel_aluno ?? '') }}"
                        data-status="{{ mb_strtolower($aluno->status_aluno ?? '') }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if ($aluno->foto_aluno)
                                <img
                                    src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                                    alt="{{ $aluno->nome_aluno }}"
                                    class="prof-avatar">
                                @else
                                <div class="prof-avatar-placeholder">
                                    {{ strtoupper(mb_substr($aluno->nome_aluno, 0, 2)) }}
                                </div>
                                @endif

                                <div>
                                    <div style="font-weight:600;font-size:.875rem;">
                                        {{ $aluno->nome_aluno }}
                                    </div>

                                    <div style="font-size:.72rem;color:#94a3b8;">
                                        {{ $aluno->email_aluno }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>{{ $aluno->telefone_aluno ?? '—' }}</td>

                        <td>
                            <span class="tbl-badge">
                                {{ $aluno->curso_aluno ?? '—' }}
                            </span>
                        </td>

                        <td>{{ $aluno->nivel_aluno ?? '—' }}</td>

                        <td>
                            @php
                            $statusClass = match($aluno->status_aluno) {
                            'EM CURSO' => 'tbl-status-emcurso',
                            'CONCLUIDO' => 'tbl-status-concluido',
                            'INATIVO' => 'tbl-status-inativo',
                            default => 'tbl-status-pendente',
                            };
                            @endphp

                            <form action="{{ route('admin.alunos.updateStatus', $aluno->id_aluno) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')

                                <div class="tbl-status {{ $statusClass }}" style="position:relative;">
                                    <span class="tbl-status-dot"></span>

                                    <select
                                        name="status_aluno"
                                        onchange="this.form.submit()"
                                        style="all:unset;cursor:pointer;font:inherit;color:inherit;background:transparent;letter-spacing:inherit;text-transform:inherit;">
                                        <option value="EM CURSO" {{ $aluno->status_aluno == 'EM CURSO' ? 'selected' : '' }}>
                                            EM CURSO
                                        </option>

                                        <option value="CONCLUIDO" {{ $aluno->status_aluno == 'CONCLUIDO' ? 'selected' : '' }}>
                                            CONCLUÍDO
                                        </option>

                                        <option value="INATIVO" {{ $aluno->status_aluno == 'INATIVO' ? 'selected' : '' }}>
                                            INATIVO
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </td>

                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a
                                    href="{{ route('admin.alunos.edit', $aluno->id_aluno) }}"
                                    class="tbl-btn-editar"
                                    title="Editar aluno">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25L13.5 1.25C13.9142 1.25 14.25 1.58579 14.25 2C14.25 2.41421 13.9142 2.75 13.5 2.75H12C9.62178 2.75 7.91356 2.75159 6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12V10.5C21.25 10.0858 21.5858 9.75 22 9.75C22.4142 9.75 22.75 10.0858 22.75 10.5V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM16.7705 2.27592C18.1384 0.908029 20.3562 0.908029 21.7241 2.27592C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27592ZM20.6634 3.33658C19.8813 2.55448 18.6133 2.55448 17.8312 3.33658L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33658ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z" fill="#FFFFFF" />
                                    </svg>
                                </a>

                                <form
                                    action="{{ route('admin.alunos.destroy', $aluno->id_aluno) }}"
                                    method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="button"
                                        class="tbl-btn-excluir"
                                        data-nome="{{ $aluno->nome_aluno }}"
                                        onclick="abrirModalExcluir(this)"
                                        title="Excluir aluno">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.3093 2.24996H13.6907C13.9071 2.24982 14.0956 2.2497 14.2736 2.27813C14.9769 2.39043 15.5855 2.82909 15.9145 3.46078C15.9978 3.62067 16.0573 3.79955 16.1256 4.00488L16.2372 4.33978C16.2561 4.39647 16.2615 4.41252 16.266 4.42516C16.4412 4.90927 16.8952 5.23653 17.4098 5.24958C17.4234 5.24992 17.4399 5.24998 17.5 5.24998H20.5C20.9142 5.24998 21.25 5.58576 21.25 5.99998C21.25 6.41419 20.9142 6.74998 20.5 6.74998H3.49991C3.08569 6.74998 2.74991 6.41419 2.74991 5.99998C2.74991 5.58576 3.08569 5.24998 3.49991 5.24998H6.49999C6.56004 5.24998 6.57661 5.24992 6.59014 5.24958C7.10479 5.23653 7.55881 4.90929 7.73393 4.42518C7.73854 4.41245 7.74383 4.39675 7.76282 4.33978L7.87443 4.0049C7.94272 3.79958 8.00223 3.62067 8.08549 3.46078C8.41444 2.82909 9.02304 2.39043 9.72634 2.27813C9.90436 2.2497 10.0929 2.24982 10.3093 2.24996ZM9.00806 5.24998C9.05957 5.14895 9.10521 5.04398 9.14448 4.93542C9.15641 4.90245 9.1681 4.86736 9.18313 4.82228L9.28293 4.52286C9.3741 4.24935 9.39509 4.19357 9.41592 4.15358C9.52557 3.94301 9.72843 3.7968 9.96287 3.75936C10.0074 3.75225 10.0669 3.74998 10.3553 3.74998H13.6447C13.933 3.74998 13.9926 3.75225 14.0371 3.75936C14.2716 3.7968 14.4744 3.94301 14.5841 4.15358C14.6049 4.19357 14.6259 4.24934 14.7171 4.52286L14.8168 4.8221L14.8555 4.93544C14.8948 5.04399 14.9404 5.14896 14.9919 5.24998H9.00806Z" fill="#FFFFFF" />
                                            <path d="M5.915 8.45009C5.88744 8.03679 5.53007 7.72409 5.11677 7.75164C4.70347 7.77919 4.39077 8.13657 4.41832 8.54987L4.88177 15.5016C4.96726 16.7843 5.03633 17.8205 5.1983 18.6336C5.3667 19.4789 5.65312 20.1849 6.24471 20.7384C6.83631 21.2919 7.55985 21.5307 8.41451 21.6425C9.23653 21.75 10.275 21.75 11.5605 21.75H12.4394C13.725 21.75 14.7635 21.75 15.5855 21.6425C16.4401 21.5307 17.1637 21.2919 17.7553 20.7384C18.3469 20.1849 18.6333 19.4789 18.8017 18.6336C18.9637 17.8205 19.0327 16.7844 19.1182 15.5016L19.5817 8.54987C19.6092 8.13657 19.2965 7.77919 18.8832 7.75164C18.4699 7.72409 18.1125 8.03679 18.085 8.45009L17.625 15.3492C17.5352 16.6971 17.4712 17.6349 17.3306 18.3405C17.1942 19.0249 17.0039 19.3872 16.7305 19.643C16.4571 19.8988 16.0829 20.0646 15.3909 20.1552C14.6775 20.2485 13.7375 20.25 12.3867 20.25H11.6133C10.2625 20.25 9.32246 20.2485 8.60906 20.1552C7.91706 20.0646 7.5429 19.8988 7.26949 19.643C6.99607 19.3872 6.80574 19.0249 6.66939 18.3405C6.52882 17.6349 6.4648 16.6971 6.37494 15.3492L5.915 8.45009Z" fill="#FFFFFF" />
                                            <path d="M9.42537 10.2537C9.83753 10.2125 10.2051 10.5132 10.2463 10.9253L10.7463 15.9253C10.7875 16.3375 10.4868 16.705 10.0746 16.7463C9.66247 16.7875 9.29494 16.4868 9.25372 16.0746L8.75372 11.0746C8.71251 10.6624 9.01321 10.2949 9.42537 10.2537Z" fill="#FFFFFF" />
                                            <path d="M14.5746 10.2537C14.9868 10.2949 15.2875 10.6624 15.2463 11.0746L14.7463 16.0746C14.7051 16.4868 14.3375 16.7875 13.9254 16.7463C13.5132 16.705 13.2125 16.3375 13.2537 15.9253L13.7537 10.9253C13.7949 10.5132 14.1625 10.2125 14.5746 10.2537Z" fill="#FFFFFF" />
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
                                <i class="fas fa-user-graduate tbl-empty-icon"></i>
                                <span class="tbl-empty-text">Nenhum aluno cadastrado ainda.</span>

                                <a href="{{ route('admin.alunos.create') }}" class="tbl-empty-btn">
                                    <i class="fas fa-plus"></i> Cadastrar Aluno
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="linhaNenhumAlunoEncontrado" class="d-none">
                        <td colspan="6">
                            <div class="tbl-empty">
                                <i class="fas fa-search tbl-empty-icon"></i>
                                <span class="tbl-empty-text">Nenhum aluno encontrado com esse filtro.</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

@include('admin.partials.modal-delete', [
'delTitulo' => 'Excluir Aluno',
'delDescricao' => 'Você está prestes a excluir o aluno:'
])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputPesquisa = document.getElementById('pesquisarAlunoIndex');
        const linhasAlunos = document.querySelectorAll('#listaAlunosTabela .aluno-row');
        const linhaVazia = document.getElementById('linhaNenhumAlunoEncontrado');

        function normalizarTexto(texto) {
            return String(texto || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        }

        if (!inputPesquisa) {
            return;
        }

        inputPesquisa.addEventListener('input', function() {
            const termo = normalizarTexto(inputPesquisa.value);
            let totalEncontrados = 0;

            linhasAlunos.forEach(function(linha) {
                const nome = normalizarTexto(linha.dataset.nome);
                const email = normalizarTexto(linha.dataset.email);
                const telefone = normalizarTexto(linha.dataset.telefone);
                const curso = normalizarTexto(linha.dataset.curso);
                const nivel = normalizarTexto(linha.dataset.nivel);
                const status = normalizarTexto(linha.dataset.status);

                const encontrou =
                    nome.includes(termo) ||
                    email.includes(termo) ||
                    telefone.includes(termo) ||
                    curso.includes(termo) ||
                    nivel.includes(termo) ||
                    status.includes(termo);

                linha.classList.toggle('d-none', !encontrou);

                if (encontrou) {
                    totalEncontrados++;
                }
            });

            if (linhaVazia) {
                linhaVazia.classList.toggle('d-none', totalEncontrados > 0);
            }
        });
    });
</script>

@endsection