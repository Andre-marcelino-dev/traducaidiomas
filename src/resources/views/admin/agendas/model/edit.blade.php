@extends('admin.layout.admin')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="container-fluid agenda-page">

    <style>
        .agenda-page {
            padding-bottom: 2rem;
        }

        .agenda-page-header {
            background: linear-gradient(135deg, #0f172a, #163d8f);
            border-radius: 24px;
            padding: 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
            margin-bottom: 1.5rem;
        }

        .agenda-page-kicker {
            display: inline-block;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #bfdbfe;
            margin-bottom: .35rem;
        }

        .agenda-page-header h1 {
            font-size: 1.7rem;
            font-weight: 800;
            margin: 0 0 .35rem;
        }

        .agenda-page-header p {
            margin: 0;
            max-width: 680px;
            font-size: .92rem;
            color: rgba(255, 255, 255, .78);
        }

        .agenda-back-btn {
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff;
            text-decoration: none;
            padding: .75rem 1rem;
            border-radius: 14px;
            font-size: .85rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            transition: .2s ease;
            white-space: nowrap;
        }

        .agenda-back-btn:hover {
            background: rgba(255, 255, 255, .22);
            color: #fff;
            transform: translateY(-1px);
        }

        .agenda-form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
        }

        .agenda-form-card-header {
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e5e7eb;
            padding: 1.4rem 1.6rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .agenda-form-card-header h5 {
            margin: 0 0 .25rem;
            color: #0f172a;
            font-weight: 800;
        }

        .agenda-form-card-header p {
            margin: 0;
            color: #64748b;
            font-size: .85rem;
        }

        .agenda-form-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, #163d8f, #2563eb);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(37, 99, 235, .25);
            flex: 0 0 auto;
        }

        .agenda-form-card-body {
            padding: 1.6rem;
        }


        .agenda-section {
            background: #fff;
            border: 1px solid #eef2f7;
            border-radius: 20px;
            padding: 1.2rem;

        }

        .agenda-section-title {
            display: flex;
            align-items: center;
            gap: .55rem;
            color: #0f172a;
            font-size: .92rem;
            font-weight: 900;
            margin-bottom: 1rem;
        }

        .agenda-section-title i {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            background: #eef3ff;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
        }

        .form-label {
            color: #334155;
            font-size: .82rem;
            font-weight: 800;
            margin-bottom: .45rem;
        }

        .form-control,
        .form-select,
        select.form-control {
            border: 1px solid #dbe4f0;
            border-radius: 14px;
            padding: .78rem .95rem;
            font-size: .9rem;
            color: #0f172a;
            background-color: #fff;
            transition: .2s ease;
        }

        .form-control:focus,
        .form-select:focus,
        select.form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 .22rem rgba(37, 99, 235, .12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 115px;
        }

        .agenda-dt-picker {
            border: 1px solid #dbe4f0;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
            width: 100%;
        }

        .agenda-dt-picker.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 .22rem rgba(239, 68, 68, .12);
        }

        .agenda-dt-head,
        .agenda-dt-result {
            align-items: center;
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.95rem 1rem;
        }

        .agenda-dt-head {
            background: linear-gradient(135deg, #0f172a, #163d8f);
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }

        .agenda-dt-month {
            color: #fff;
            font-size: .95rem;
            font-weight: 900;
        }

        .agenda-dt-nav {
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 12px;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            font-size: 1.2rem;
            line-height: 1;
            transition: .2s ease;
        }

        .agenda-dt-nav:hover {
            background: rgba(255, 255, 255, .22);
        }

        .agenda-dt-body {
            padding: 1rem;
        }

        .agenda-dt-weekdays,
        .agenda-dt-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }

        .agenda-dt-weekdays {
            margin-bottom: .5rem;
        }

        .agenda-dt-weekdays span {
            color: #94a3b8;
            font-size: .68rem;
            font-weight: 900;
            padding: .25rem 0;
            text-align: center;
            text-transform: uppercase;
        }

        .agenda-dt-days {
            gap: .35rem;
            grid-template-rows: repeat(6, 42px);
        }

        .agenda-dt-day,
        .agenda-dt-empty {
            min-height: 42px;
            height: 42px;
            border-radius: 14px;
        }

        .agenda-dt-day {
            align-items: center;
            background: #fff;
            border: 1px solid transparent;
            color: #0f172a;
            cursor: pointer;
            display: flex;
            font-size: .82rem;
            font-weight: 800;
            justify-content: center;
            width: 100%;
            transition: .2s ease;
        }

        .agenda-dt-day:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        .agenda-dt-day.is-today {
            border-color: #2563eb;
            color: #2563eb;
        }

        .agenda-dt-day.is-selected {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border-color: #1d4ed8;
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, .22);
        }

        .agenda-dt-divider {
            height: 1px;
            background: #eef2f7;
        }

        .agenda-time-area {
            padding: 1rem;
        }

        .agenda-time-title {
            color: #475569;
            font-size: .78rem;
            font-weight: 900;
            margin-bottom: .75rem;
        }

        .agenda-time-grid {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            max-height: 160px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .agenda-time-btn {
            background: #fff;
            border: 1px solid #dbe4f0;
            border-radius: 999px;
            color: #0f172a;
            cursor: pointer;
            font-size: .78rem;
            font-weight: 900;
            padding: .45rem .8rem;
            transition: .2s ease;
        }

        .agenda-time-btn:hover:not(:disabled) {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .agenda-time-btn.is-selected {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, .18);
        }

        .agenda-time-btn:disabled {
            background: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .agenda-dt-result {
            border-top: 1px solid #eef2f7;
            background: #f8fafc;
        }

        .agenda-dt-result-text {
            color: #0f172a;
            font-size: .82rem;
            font-weight: 900;
        }

        .agenda-dt-result-text.is-empty {
            color: #94a3b8;
            font-weight: 600;
        }

        .agenda-dt-clear {
            background: #fff;
            border: 1px solid #dbe4f0;
            border-radius: 12px;
            color: #64748b;
            display: none;
            font-size: 1rem;
            line-height: 1;
            padding: .25rem .55rem;
            transition: .2s ease;
        }

        .agenda-dt-clear:hover {
            background: #fee2e2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .agenda-status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .65rem;
        }

        .agenda-status-option {
            border: 1px solid #dbe4f0;
            border-radius: 16px;
            padding: .85rem;
            cursor: pointer;
            background: #fff;
            transition: .2s ease;
            display: flex;
            align-items: center;
            gap: .65rem;
        }

        .agenda-status-option:hover {
            border-color: #bfdbfe;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
            transform: translateY(-1px);
        }

        .agenda-status-option input {
            margin: 0;
        }

        .agenda-status-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            flex: 0 0 auto;
        }

        .status-pendente {
            background: #eef3ff;
            color: #f59e0b;
        }

        .status-confirmado {
            background: #eef3ff;
            color: #22c55e;
        }

        .status-cancelado {
            background: #eef3ff;
            color: #ef4444;
        }

        .status-reagendado {
            background: #eef3ff;
            color: #6366f1;
        }

        .agenda-status-title {
            display: block;
            font-size: .82rem;
            font-weight: 900;
            color: #0f172a;
        }

        .agenda-status-desc {
            display: block;
            font-size: .7rem;
            color: #64748b;
            margin-top: .1rem;
        }

        .agenda-actions {
            position: sticky;
            bottom: 0;
            background: #fff;
            z-index: 10;
            border-top: 1px solid #eef2f7;
            margin-top: 1.5rem;
            padding-top: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .7rem;
        }

        .agenda-btn {
            border: none;
            border-radius: 14px;
            padding: .8rem 1.1rem;
            font-size: .86rem;
            font-weight: 900;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            transition: .2s ease;
        }

        .agenda-btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            box-shadow: 0 10px 22px rgba(37, 99, 235, .22);
        }

        .agenda-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(37, 99, 235, .28);
        }

        .agenda-btn-secondary {
            background: #f1f5f9;
            color: #334155;
        }

        .agenda-btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .agenda-page-header {
                align-items: flex-start;
                flex-direction: column;
                padding: 1.5rem;
            }

            .agenda-form-card {
                max-height: none;
            }

            .agenda-form-card-body {
                padding: 1rem;
                overflow-y: visible;
            }

            .agenda-form-card-header {
                padding: 1.1rem;
            }

            .agenda-status-grid {
                grid-template-columns: 1fr;
            }

            .agenda-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .agenda-btn {
                width: 100%;
            }
        }
    </style>

    @php
    $dataAgenda = old(
    'data_evento_agenda',
    $agenda->data_evento_agenda
    ? \Carbon\Carbon::parse($agenda->data_evento_agenda)->format('Y-m-d')
    : ''
    );

    $horaInicio = old(
    'hora_inicio_agenda',
    $agenda->hora_inicio_agenda
    ? \Carbon\Carbon::parse($agenda->hora_inicio_agenda)->format('H:i')
    : ''
    );

    $horaFim = old(
    'hora_fim_agenda',
    $agenda->hora_fim_agenda
    ? \Carbon\Carbon::parse($agenda->hora_fim_agenda)->format('H:i')
    : ''
    );

    $statusSelecionado = old('status_agenda', $agenda->status_agenda ?? 'pendente');
    @endphp

    <div class="agenda-page-header">
        <div>
            <span class="agenda-page-kicker">Administração</span>
            <h1>Editar Agendamento</h1>
            <p>Atualize aluno, professor, data, horário, status e informações do agendamento.</p>
        </div>

        <a href="{{ route('admin.agendas.index') }}" class="agenda-back-btn">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <div class="agenda-form-card">
        <div class="agenda-form-card-header">
            <div>
                <h5>Dados do Agendamento</h5>
                <p>Edite as informações abaixo e salve as alterações.</p>
            </div>

            <div class="agenda-form-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7 1.75C7.41421 1.75 7.75 2.08579 7.75 2.5V3.26272C8.41203 3.24999 9.1414 3.24999 9.94358 3.25H14.0564C14.8586 3.24999 15.588 3.24999 16.25 3.26272V2.5C16.25 2.08579 16.5858 1.75 17 1.75C17.4142 1.75 17.75 2.08579 17.75 2.5V3.32709C18.0099 3.34691 18.2561 3.37182 18.489 3.40313C19.6614 3.56076 20.6104 3.89288 21.3588 4.64124C22.1071 5.38961 22.4392 6.33855 22.5969 7.51098C22.6472 7.88567 22.681 8.29459 22.7037 8.74007C22.7337 8.82106 22.75 8.90861 22.75 9C22.75 9.06932 22.7406 9.13644 22.723 9.20016C22.75 10.0021 22.75 10.9128 22.75 11.9436V14C22.75 14.4142 22.4142 14.75 22 14.75C21.5858 14.75 21.25 14.4142 21.25 14V12C21.25 11.146 21.2497 10.4027 21.2369 9.75H2.76309C2.75032 10.4027 2.75 11.146 2.75 12V14C2.75 15.9068 2.75159 17.2615 2.88976 18.2892C3.02502 19.2952 3.27869 19.8749 3.7019 20.2981C4.12511 20.7213 4.70476 20.975 5.71085 21.1102C6.73851 21.2484 8.09318 21.25 10 21.25H14C14.4142 21.25 14.75 21.5858 14.75 22C14.75 22.4142 14.4142 22.75 14 22.75H9.94359C8.10583 22.75 6.65019 22.75 5.51098 22.5969C4.33856 22.4392 3.38961 22.1071 2.64124 21.3588C1.89288 20.6104 1.56076 19.6614 1.40314 18.489C1.24997 17.3498 1.24998 15.8942 1.25 14.0564V11.9436C1.24999 10.9127 1.24998 10.0021 1.27701 9.20017C1.25941 9.13645 1.25 9.06932 1.25 9C1.25 8.90862 1.26634 8.82105 1.29627 8.74006C1.31895 8.29458 1.35276 7.88566 1.40314 7.51098C1.56076 6.33856 1.89288 5.38961 2.64124 4.64124C3.38961 3.89288 4.33856 3.56076 5.51098 3.40313C5.7439 3.37182 5.99006 3.34691 6.25 3.32709V2.5C6.25 2.08579 6.58579 1.75 7 1.75ZM2.83168 8.25H21.1683C21.1523 8.06061 21.1331 7.88123 21.1102 7.71085C20.975 6.70476 20.7213 6.12511 20.2981 5.7019C19.8749 5.27869 19.2952 5.02502 18.2892 4.88976C17.2615 4.75159 15.9068 4.75 14 4.75H10C8.09318 4.75 6.73851 4.75159 5.71085 4.88976C4.70476 5.02502 4.12511 5.27869 3.7019 5.7019C3.27869 6.12511 3.02502 6.70476 2.88976 7.71085C2.86685 7.88123 2.8477 8.06061 2.83168 8.25ZM18 15.75C16.7574 15.75 15.75 16.7574 15.75 18C15.75 19.2426 16.7574 20.25 18 20.25C19.2426 20.25 20.25 19.2426 20.25 18C20.25 16.7574 19.2426 15.75 18 15.75ZM14.25 18C14.25 15.9289 15.9289 14.25 18 14.25C20.0711 14.25 21.75 15.9289 21.75 18C21.75 18.7643 21.5213 19.4752 21.1287 20.068L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L20.068 21.1287C19.4752 21.5213 18.7643 21.75 18 21.75C15.9289 21.75 14.25 20.0711 14.25 18Z" fill="#FFFFFF" />
                </svg>
            </div>
        </div>

        <div class="agenda-form-card-body">

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <strong>Verifique os campos abaixo:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('admin.agendas.update', $agenda->id_agenda) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="agenda-section">
                            <div class="agenda-section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C9.37666 1.25 7.25001 3.37665 7.25001 6C7.25001 8.62335 9.37666 10.75 12 10.75C14.6234 10.75 16.75 8.62335 16.75 6C16.75 3.37665 14.6234 1.25 12 1.25ZM8.75001 6C8.75001 4.20507 10.2051 2.75 12 2.75C13.7949 2.75 15.25 4.20507 15.25 6C15.25 7.79493 13.7949 9.25 12 9.25C10.2051 9.25 8.75001 7.79493 8.75001 6Z" fill="#163d8f" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 12.25C9.68646 12.25 7.55494 12.7759 5.97546 13.6643C4.4195 14.5396 3.25001 15.8661 3.25001 17.5L3.24995 17.602C3.24882 18.7638 3.2474 20.222 4.52642 21.2635C5.15589 21.7761 6.03649 22.1406 7.22622 22.3815C8.41927 22.6229 9.97424 22.75 12 22.75C14.0258 22.75 15.5808 22.6229 16.7738 22.3815C17.9635 22.1406 18.8441 21.7761 19.4736 21.2635C20.7526 20.222 20.7512 18.7638 20.7501 17.602L20.75 17.5C20.75 15.8661 19.5805 14.5396 18.0246 13.6643C16.4451 12.7759 14.3136 12.25 12 12.25ZM4.75001 17.5C4.75001 16.6487 5.37139 15.7251 6.71085 14.9717C8.02681 14.2315 9.89529 13.75 12 13.75C14.1047 13.75 15.9732 14.2315 17.2892 14.9717C18.6286 15.7251 19.25 16.6487 19.25 17.5C19.25 18.8078 19.2097 19.544 18.5264 20.1004C18.1559 20.4022 17.5365 20.6967 16.4762 20.9113C15.4193 21.1252 13.9742 21.25 12 21.25C10.0258 21.25 8.58075 21.1252 7.5238 20.9113C6.46354 20.6967 5.84413 20.4022 5.4736 20.1004C4.79033 19.544 4.75001 18.8078 4.75001 17.5Z" fill="#163d8f" />
                                </svg>
                                Participantes
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Aluno <span class="text-danger">*</span>
                                    </label>

                                    <select name="id_aluno" class="form-select @error('id_aluno') is-invalid @enderror" required>
                                        <option value="">Selecione o aluno</option>

                                        @foreach($alunos as $aluno)
                                        <option value="{{ $aluno->id_aluno }}"
                                            {{ old('id_aluno', $agenda->id_aluno) == $aluno->id_aluno ? 'selected' : '' }}>
                                            {{ $aluno->nome_aluno }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('id_aluno')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Professor <span class="text-danger">*</span>
                                    </label>

                                    <select name="id_professor" class="form-select @error('id_professor') is-invalid @enderror" required>
                                        <option value="">Selecione o professor</option>

                                        @foreach($professores as $professor)
                                        <option value="{{ $professor->id_professor }}"
                                            {{ old('id_professor', $agenda->id_professor) == $professor->id_professor ? 'selected' : '' }}>
                                            {{ $professor->nome_professor }}
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('id_professor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">
                                    Título <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="titulo_agenda"
                                    value="{{ old('titulo_agenda', $agenda->titulo_agenda) }}"
                                    class="form-control @error('titulo_agenda') is-invalid @enderror"
                                    placeholder="Ex: Aula de Italiano - Conversação"
                                    required>

                                @error('titulo_agenda')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Descrição</label>

                                <textarea name="descricao_agenda"
                                    rows="3"
                                    class="form-control @error('descricao_agenda') is-invalid @enderror"
                                    placeholder="Descrição da aula, conteúdo ou observações...">{{ old('descricao_agenda', $agenda->descricao_agenda) }}</textarea>

                                @error('descricao_agenda')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Link da Aula</label>

                                <input type="url"
                                    name="link_aula_agenda"
                                    value="{{ old('link_aula_agenda', $agenda->link_aula_agenda ?? '') }}"
                                    class="form-control @error('link_aula_agenda') is-invalid @enderror"
                                    placeholder="https://meet.google.com/...">

                                @error('link_aula_agenda')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="agenda-section mt-4">
                            <div class="agenda-section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M13.5303 6.46967L12.5303 5.46967C12.2374 5.17678 11.7626 5.17678 11.4697 5.46967L10.4697 6.46967C10.1768 6.76256 10.1768 7.23744 10.4697 7.53033C10.6807 7.74135 10.9862 7.80034 11.25 7.70729V12.7415C11.1787 12.7115 11.1055 12.6847 11.0306 12.6613L9.62716 12.2227C9.10531 12.0596 8.75 11.5763 8.75 11.0296V10.5816C9.34124 10.3007 9.75 9.6981 9.75 9C9.75 8.0335 8.9665 7.25 8 7.25C7.0335 7.25 6.25 8.0335 6.25 9C6.25 9.6981 6.65876 10.3007 7.25 10.5816V11.0296C7.25 12.2324 8.03169 13.2957 9.17974 13.6544L10.5832 14.093C10.9799 14.217 11.25 14.5844 11.25 15V15.4184C10.6588 15.6993 10.25 16.3019 10.25 17C10.25 17.9665 11.0335 18.75 12 18.75C12.9665 18.75 13.75 17.9665 13.75 17C13.75 16.3605 13.4069 15.8011 12.8948 15.4958C13.0124 15.3081 13.1948 15.1624 13.4168 15.093L14.8203 14.6544C15.9683 14.2957 16.75 13.2324 16.75 12.0296V11.7084C16.9502 11.6695 17.1831 11.5847 17.3839 11.3839C17.6197 11.1481 17.6955 10.8679 17.725 10.6486C17.7502 10.4614 17.7501 10.2396 17.75 10.0345V9.96555C17.7501 9.76045 17.7502 9.53864 17.725 9.35143C17.6955 9.1321 17.6197 8.85192 17.3839 8.61612C17.1481 8.38032 16.8679 8.30448 16.6486 8.27499C16.4614 8.24982 16.2396 8.24991 16.0344 8.24999H15.9656C15.7604 8.24991 15.5386 8.24982 15.3514 8.27499C15.1321 8.30448 14.8519 8.38032 14.6161 8.61612C14.3803 8.85192 14.3045 9.1321 14.275 9.35143C14.2498 9.53864 14.2499 9.76042 14.25 9.96553V10.0344C14.2499 10.2396 14.2498 10.4614 14.275 10.6486C14.3045 10.8679 14.3803 11.1481 14.6161 11.3839C14.8169 11.5847 15.0498 11.6695 15.25 11.7084V12.0296C15.25 12.5763 14.8947 13.0596 14.3728 13.2227L12.9694 13.6613C12.8945 13.6847 12.8213 13.7115 12.75 13.7415V7.70729C13.0138 7.80034 13.3193 7.74135 13.5303 7.53033C13.8232 7.23744 13.8232 6.76256 13.5303 6.46967ZM16.2488 10.2488C16.1794 10.25 16.0991 10.25 16 10.25C15.9009 10.25 15.8206 10.25 15.7512 10.2488C15.75 10.1794 15.75 10.0991 15.75 10C15.75 9.90092 15.75 9.82061 15.7512 9.75115C15.8206 9.75003 15.9009 9.75 16 9.75C16.0991 9.75 16.1794 9.75003 16.2488 9.75115C16.25 9.82061 16.25 9.90092 16.25 10C16.25 10.0991 16.25 10.1794 16.2488 10.2488ZM12 16.75C11.8619 16.75 11.75 16.8619 11.75 17C11.75 17.1381 11.8619 17.25 12 17.25C12.1381 17.25 12.25 17.1381 12.25 17C12.25 16.8619 12.1381 16.75 12 16.75ZM8.25 9C8.25 9.13807 8.13807 9.25 8 9.25C7.86193 9.25 7.75 9.13807 7.75 9C7.75 8.86193 7.86193 8.75 8 8.75C8.13807 8.75 8.25 8.86193 8.25 9Z" fill="#163d8f"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75C17.1086 2.75 21.25 6.89137 21.25 12C21.25 17.1086 17.1086 21.25 12 21.25C6.89137 21.25 2.75 17.1086 2.75 12Z" fill="#163d8f"/>
</svg>
                                Status do Agendamento
                            </div>

                            <div class="agenda-status-grid">
                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="pendente" {{ $statusSelecionado == 'pendente' ? 'checked' : '' }}>

                                    <span class="agenda-status-icon status-pendente">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9376 1.25H12.0624C14.0761 1.24998 15.6654 1.24997 16.8229 1.40259C17.9607 1.5526 18.983 1.88319 19.4685 2.82586C19.5384 2.96167 19.5975 3.10281 19.645 3.24795C19.9763 4.25892 19.4861 5.21387 18.7879 6.11769C18.0778 7.03684 16.9536 8.15011 15.5303 9.55957L13.0659 12L15.5303 14.4404C16.9536 15.8499 18.0778 16.9632 18.7879 17.8823C19.4861 18.7861 19.9763 19.7411 19.645 20.752C19.5975 20.8972 19.5384 21.0383 19.4685 21.1741C18.983 22.1168 17.9607 22.4474 16.8229 22.5974C15.6654 22.75 14.0761 22.75 12.0624 22.75H11.9376C9.92384 22.75 8.33457 22.75 7.177 22.5974C6.03925 22.4474 5.01697 22.1168 4.53146 21.1741C4.46151 21.0383 4.40247 20.8972 4.35491 20.752C4.02365 19.7411 4.51387 18.7861 5.21208 17.8823C5.92214 16.9632 7.04636 15.8499 8.46969 14.4404L10.9341 12L8.46969 9.55957C7.04637 8.15011 5.92214 7.03684 5.21208 6.11769C4.51387 5.21387 4.02365 4.25893 4.35491 3.24795C4.40247 3.10281 4.46151 2.96167 4.53146 2.82586C5.01697 1.88319 6.03925 1.5526 7.177 1.40259C8.33457 1.24997 9.92382 1.24998 11.9376 1.25ZM12 10.9445L14.4299 8.53815C15.9079 7.0746 16.9593 6.03105 17.6008 5.20068C18.2616 4.34534 18.2974 3.95253 18.2196 3.71502C18.1968 3.64553 18.1685 3.57787 18.135 3.51267C18.0183 3.28608 17.7062 3.03203 16.6269 2.88972C15.5793 2.7516 14.0897 2.75 12 2.75C9.91028 2.75 8.42065 2.7516 7.37308 2.88972C6.29377 3.03203 5.98169 3.28608 5.86499 3.51267C5.83141 3.57787 5.80311 3.64553 5.78034 3.71502C5.70252 3.95253 5.73838 4.34534 6.39913 5.20068C7.04061 6.03105 8.09208 7.0746 9.57001 8.53815L12 10.9445ZM12 13.0555L9.57001 15.4618C8.09208 16.9254 7.04061 17.969 6.39913 18.7993C5.73838 19.6547 5.70252 20.0475 5.78034 20.285C5.80311 20.3545 5.83141 20.4221 5.86499 20.4873C5.98169 20.7139 6.29377 20.968 7.37308 21.1103C8.42065 21.2484 9.91028 21.25 12 21.25C14.0897 21.25 15.5793 21.2484 16.6269 21.1103C17.7062 20.968 18.0183 20.7139 18.135 20.4873C18.1685 20.4221 18.1968 20.3545 18.2196 20.285C18.2974 20.0475 18.2616 19.6547 17.6008 18.7993C16.9593 17.969 15.9079 16.9254 14.4299 15.4618L12 13.0555ZM9.24997 5.5C9.24997 5.08579 9.58576 4.75 9.99997 4.75H14C14.4142 4.75 14.75 5.08579 14.75 5.5C14.75 5.91421 14.4142 6.25 14 6.25H9.99997C9.58576 6.25 9.24997 5.91421 9.24997 5.5ZM9.24997 18.5C9.24997 18.0858 9.58576 17.75 9.99997 17.75H14C14.4142 17.75 14.75 18.0858 14.75 18.5C14.75 18.9142 14.4142 19.25 14 19.25H9.99997C9.58576 19.25 9.24997 18.9142 9.24997 18.5Z" fill="#163d8f" />
                                        </svg>
                                    </span>

                                    <span>
                                        <span class="agenda-status-title">Pendente</span>
                                        <span class="agenda-status-desc">Aguardando confirmação</span>
                                    </span>
                                </label>

                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="confirmado" {{ $statusSelecionado == 'confirmado' ? 'checked' : '' }}>

                                    <span class="agenda-status-icon status-confirmado">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5936 2.31883C11.483 1.89372 12.517 1.89372 13.4064 2.31883C13.7928 2.50351 14.1468 2.80551 14.6371 3.22367C14.6625 3.24538 14.6884 3.2674 14.7146 3.28973C14.9526 3.49262 15.0276 3.555 15.1035 3.60585C15.2965 3.73519 15.5132 3.82495 15.7411 3.86995C15.8307 3.88764 15.9278 3.89654 16.2396 3.92143C16.2739 3.92417 16.3078 3.92685 16.3411 3.92949C16.9834 3.98046 17.4473 4.01727 17.8511 4.15991C18.7807 4.48822 19.5118 5.21935 19.8401 6.14885C19.9827 6.55267 20.0195 7.01656 20.0705 7.6589C20.0732 7.69224 20.0758 7.72607 20.0786 7.76039C20.1035 8.0722 20.1124 8.16933 20.1301 8.25894C20.175 8.48684 20.2648 8.70355 20.3941 8.89652C20.445 8.97239 20.5074 9.04737 20.7103 9.28545C20.7326 9.31166 20.7546 9.33748 20.7763 9.36293C21.1945 9.85316 21.4965 10.2072 21.6812 10.5936C22.1063 11.483 22.1063 12.517 21.6812 13.4064C21.4965 13.7928 21.1945 14.1468 20.7763 14.6371C20.7546 14.6625 20.7326 14.6883 20.7103 14.7146C20.5074 14.9526 20.445 15.0276 20.3941 15.1035C20.2648 15.2965 20.175 15.5132 20.1301 15.7411C20.1124 15.8307 20.1035 15.9278 20.0786 16.2396C20.0758 16.2739 20.0732 16.3078 20.0705 16.3411C20.0195 16.9834 19.9827 17.4473 19.8401 17.8511C19.5118 18.7807 18.7807 19.5118 17.8511 19.8401C17.4473 19.9827 16.9834 20.0195 16.3411 20.0705C16.3078 20.0732 16.2739 20.0758 16.2396 20.0786C15.9278 20.1035 15.8307 20.1124 15.7411 20.1301C15.5132 20.175 15.2965 20.2648 15.1035 20.3941C15.0276 20.445 14.9526 20.5074 14.7146 20.7103C14.6883 20.7326 14.6625 20.7546 14.6371 20.7763C14.1468 21.1945 13.7928 21.4965 13.4064 21.6812C12.517 22.1063 11.483 22.1063 10.5936 21.6812C10.2072 21.4965 9.85315 21.1945 9.3629 20.7763C9.33746 20.7546 9.31165 20.7326 9.28545 20.7103C9.04736 20.5074 8.97239 20.445 8.89652 20.3941C8.70355 20.2648 8.48684 20.175 8.25894 20.1301C8.16933 20.1124 8.0722 20.1035 7.76039 20.0786C7.72607 20.0758 7.69225 20.0732 7.6589 20.0705C7.01656 20.0195 6.55267 19.9827 6.14885 19.8401C5.21935 19.5118 4.48822 18.7807 4.15991 17.8511C4.01727 17.4473 3.98046 16.9834 3.92949 16.3411C3.92685 16.3078 3.92417 16.2739 3.92143 16.2396C3.89654 15.9278 3.88764 15.8307 3.86995 15.7411C3.82495 15.5132 3.73519 15.2965 3.60585 15.1035C3.555 15.0276 3.49262 14.9526 3.28973 14.7146C3.2674 14.6884 3.24538 14.6625 3.22368 14.6371C2.80551 14.1469 2.50351 13.7928 2.31883 13.4064C1.89372 12.517 1.89372 11.483 2.31883 10.5936C2.50351 10.2072 2.80551 9.85315 3.22367 9.36291C3.24537 9.33747 3.26739 9.31165 3.28973 9.28545C3.49262 9.04736 3.555 8.97239 3.60585 8.89652C3.73519 8.70355 3.82495 8.48684 3.86995 8.25894C3.88764 8.16933 3.89654 8.0722 3.92143 7.76039C3.92417 7.72607 3.92685 7.69225 3.92949 7.6589C3.98046 7.01657 4.01727 6.55267 4.15991 6.14885C4.48822 5.21935 5.21935 4.48822 6.14885 4.15991C6.55267 4.01727 7.01657 3.98046 7.6589 3.92949C7.69225 3.92685 7.72607 3.92417 7.76039 3.92143C8.0722 3.89654 8.16933 3.88764 8.25894 3.86995C8.48684 3.82495 8.70355 3.73519 8.89652 3.60585C8.97239 3.555 9.04736 3.49262 9.28545 3.28973C9.31165 3.26739 9.33746 3.24538 9.36291 3.22367C9.85315 2.80551 10.2072 2.50351 10.5936 2.31883ZM12.7573 3.6769C12.2784 3.44799 11.7216 3.44799 11.2427 3.6769C11.0576 3.76539 10.8624 3.92352 10.2618 4.43537C10.2519 4.44378 10.2422 4.45207 10.2326 4.46026C10.0354 4.6283 9.89156 4.75097 9.73456 4.8562C9.37619 5.09639 8.97373 5.2631 8.55048 5.34666C8.36505 5.38327 8.17657 5.39827 7.91837 5.41883C7.90579 5.41983 7.89304 5.42084 7.88012 5.42187C7.09348 5.48465 6.84366 5.51084 6.65016 5.57919C6.14966 5.75597 5.75597 6.14966 5.57919 6.65016C5.51084 6.84366 5.48465 7.09348 5.42187 7.88012C5.42084 7.89304 5.41983 7.90579 5.41883 7.91837C5.39827 8.17657 5.38327 8.36505 5.34666 8.55048C5.2631 8.97373 5.09639 9.37619 4.8562 9.73456C4.75097 9.89156 4.6283 10.0354 4.46025 10.2326C4.45207 10.2422 4.44377 10.2519 4.43537 10.2618C3.92352 10.8624 3.76539 11.0576 3.6769 11.2427C3.44799 11.7216 3.44799 12.2784 3.6769 12.7573C3.76539 12.9424 3.92352 13.1376 4.43537 13.7382C4.44378 13.7481 4.45207 13.7578 4.46026 13.7674C4.6283 13.9646 4.75097 14.1084 4.8562 14.2654C5.09639 14.6238 5.2631 15.0263 5.34666 15.4495C5.38327 15.6349 5.39827 15.8234 5.41883 16.0816C5.41983 16.0942 5.42084 16.107 5.42187 16.1199C5.48465 16.9065 5.51084 17.1563 5.57919 17.3498C5.75597 17.8503 6.14966 18.244 6.65016 18.4208C6.84366 18.4892 7.09348 18.5154 7.88012 18.5781L7.91836 18.5812C8.17658 18.6017 8.36506 18.6167 8.55048 18.6533C8.97373 18.7369 9.37619 18.9036 9.73456 19.1438C9.89156 19.249 10.0354 19.3717 10.2326 19.5397L10.2618 19.5646C10.8624 20.0765 11.0576 20.2346 11.2427 20.3231C11.7216 20.552 12.2784 20.552 12.7573 20.3231C12.9424 20.2346 13.1376 20.0765 13.7382 19.5646L13.7675 19.5397C13.9646 19.3717 14.1084 19.249 14.2654 19.1438C14.6238 18.9036 15.0263 18.7369 15.4495 18.6533C15.6349 18.6167 15.8234 18.6017 16.0816 18.5812L16.1199 18.5781C16.9065 18.5154 17.1563 18.4892 17.3498 18.4208C17.8503 18.244 18.244 17.8503 18.4208 17.3498C18.4892 17.1563 18.5154 16.9065 18.5781 16.1199L18.5812 16.0816C18.6017 15.8234 18.6167 15.6349 18.6533 15.4495C18.7369 15.0263 18.9036 14.6238 19.1438 14.2654C19.249 14.1084 19.3717 13.9646 19.5397 13.7675L19.5646 13.7382C20.0765 13.1376 20.2346 12.9424 20.3231 12.7573C20.552 12.2784 20.552 11.7216 20.3231 11.2427C20.2346 11.0576 20.0765 10.8624 19.5646 10.2618L19.5397 10.2325C19.3717 10.0354 19.249 9.89155 19.1438 9.73456C18.9036 9.37619 18.7369 8.97373 18.6533 8.55048C18.6167 8.36505 18.6017 8.17658 18.5812 7.91836L18.5781 7.88012C18.5154 7.09348 18.4892 6.84366 18.4208 6.65016C18.244 6.14966 17.8503 5.75597 17.3498 5.57919C17.1563 5.51084 16.9065 5.48465 16.1199 5.42187C16.107 5.42084 16.0942 5.41983 16.0816 5.41883C15.8234 5.39827 15.6349 5.38327 15.4495 5.34666C15.0263 5.2631 14.6238 5.09639 14.2654 4.8562C14.1084 4.75097 13.9646 4.6283 13.7674 4.46025C13.7578 4.45207 13.7481 4.44377 13.7382 4.43537C13.1376 3.92352 12.9424 3.76539 12.7573 3.6769ZM16.0443 8.95913C16.3383 9.25304 16.3383 9.72957 16.0443 10.0235L11.027 15.0409C10.733 15.3348 10.2565 15.3348 9.96261 15.0409L7.95565 13.0339C7.66174 12.74 7.66174 12.2635 7.95565 11.9696C8.24957 11.6757 8.72609 11.6757 9.02 11.9696L10.4948 13.4443L14.98 8.95913C15.2739 8.66522 15.7504 8.66522 16.0443 8.95913Z" fill="#163d8f" />
                                        </svg>
                                    </span>

                                    <span>
                                        <span class="agenda-status-title">Confirmado</span>
                                        <span class="agenda-status-desc">Aula confirmada</span>
                                    </span>
                                </label>

                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="cancelado" {{ $statusSelecionado == 'cancelado' ? 'checked' : '' }}>

                                    <span class="agenda-status-icon status-cancelado">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10.0303 8.96967C9.73741 8.67678 9.26253 8.67678 8.96964 8.96967C8.67675 9.26256 8.67675 9.73744 8.96964 10.0303L10.9393 12L8.96966 13.9697C8.67677 14.2626 8.67677 14.7374 8.96966 15.0303C9.26255 15.3232 9.73743 15.3232 10.0303 15.0303L12 13.0607L13.9696 15.0303C14.2625 15.3232 14.7374 15.3232 15.0303 15.0303C15.3232 14.7374 15.3232 14.2625 15.0303 13.9697L13.0606 12L15.0303 10.0303C15.3232 9.73746 15.3232 9.26258 15.0303 8.96969C14.7374 8.6768 14.2625 8.6768 13.9696 8.96969L12 10.9394L10.0303 8.96967Z" fill="#163d8f" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0574 1.25H11.9426C9.63424 1.24999 7.82519 1.24998 6.41371 1.43975C4.96897 1.63399 3.82895 2.03933 2.93414 2.93414C2.03933 3.82895 1.63399 4.96897 1.43975 6.41371C1.24998 7.82519 1.24999 9.63422 1.25 11.9426V12.0574C1.24999 14.3658 1.24998 16.1748 1.43975 17.5863C1.63399 19.031 2.03933 20.1711 2.93414 21.0659C3.82895 21.9607 4.96897 22.366 6.41371 22.5603C7.82519 22.75 9.63423 22.75 11.9426 22.75H12.0574C14.3658 22.75 16.1748 22.75 17.5863 22.5603C19.031 22.366 20.1711 21.9607 21.0659 21.0659C21.9607 20.1711 22.366 19.031 22.5603 17.5863C22.75 16.1748 22.75 14.3658 22.75 12.0574V11.9426C22.75 9.63423 22.75 7.82519 22.5603 6.41371C22.366 4.96897 21.9607 3.82895 21.0659 2.93414C20.1711 2.03933 19.031 1.63399 17.5863 1.43975C16.1748 1.24998 14.3658 1.24999 12.0574 1.25ZM3.9948 3.9948C4.56445 3.42514 5.33517 3.09825 6.61358 2.92637C7.91356 2.75159 9.62177 2.75 12 2.75C14.3782 2.75 16.0864 2.75159 17.3864 2.92637C18.6648 3.09825 19.4355 3.42514 20.0052 3.9948C20.5749 4.56445 20.9018 5.33517 21.0736 6.61358C21.2484 7.91356 21.25 9.62177 21.25 12C21.25 14.3782 21.2484 16.0864 21.0736 17.3864C20.9018 18.6648 20.5749 19.4355 20.0052 20.0052C19.4355 20.5749 18.6648 20.9018 17.3864 21.0736C16.0864 21.2484 14.3782 21.25 12 21.25C9.62177 21.25 7.91356 21.2484 6.61358 21.0736C5.33517 20.9018 4.56445 20.5749 3.9948 20.0052C3.42514 19.4355 3.09825 18.6648 2.92637 17.3864C2.75159 16.0864 2.75 14.3782 2.75 12C2.75 9.62177 2.75159 7.91356 2.92637 6.61358C3.09825 5.33517 3.42514 4.56445 3.9948 3.9948Z" fill="#163d8f" />
                                        </svg>
                                    </span>

                                    <span>
                                        <span class="agenda-status-title">Cancelado</span>
                                        <span class="agenda-status-desc">Aula cancelada</span>
                                    </span>
                                </label>

                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="reagendado" {{ $statusSelecionado == 'reagendado' ? 'checked' : '' }}>

                                    <span class="agenda-status-icon status-reagendado">
                                        <svg stroke="#163d8f" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 2V5" stroke="#163d8f" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16 2V5" stroke="#163d8f" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M7 13H15" stroke="#163d8f" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M7 17H12" stroke="#163d8f" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M16 3.5C19.33 3.68 21 4.95 21 9.65V15.83C21 19.95 20 22.01 15 22.01H9C4 22.01 3 19.95 3 15.83V9.65C3 4.95 4.67 3.69 8 3.5H16Z" stroke="#163d8f" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>

                                    <span>
                                        <span class="agenda-status-title">Reagendado</span>
                                        <span class="agenda-status-desc">Horário alterado</span>
                                    </span>
                                </label>
                            </div>

                            @error('status_agenda')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="agenda-section">
                            <div class="agenda-section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25H12.0574C14.3658 1.24999 16.1748 1.24998 17.5863 1.43975C19.031 1.63399 20.1711 2.03933 21.0659 2.93414C21.9607 3.82895 22.366 4.96897 22.5603 6.41371C22.75 7.82519 22.75 9.63423 22.75 11.9426V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12C21.25 9.62178 21.2484 7.91356 21.0736 6.61358C20.9018 5.33517 20.5749 4.56445 20.0052 3.9948C19.4355 3.42514 18.6648 3.09825 17.3864 2.92637C16.0864 2.75159 14.3782 2.75 12 2.75C9.62178 2.75 7.91356 2.75159 6.61358 2.92637ZM12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V11.6893L15.0303 13.9697C15.3232 14.2626 15.3232 14.7374 15.0303 15.0303C14.7374 15.3232 14.2626 15.3232 13.9697 15.0303L11.8358 12.8964C11.5468 12.6074 11.4022 12.4629 11.3261 12.2791C11.25 12.0954 11.25 11.891 11.25 11.4822V8C11.25 7.58579 11.5858 7.25 12 7.25Z" fill="#163d8f" />
                                </svg>
                                Data e horário
                            </div>

                            <div class="agenda-dt-picker
                                {{ $errors->has('data_evento_agenda') || $errors->has('hora_inicio_agenda') || $errors->has('hora_fim_agenda') ? 'is-invalid' : '' }}"
                                id="agenda_dt_edit">

                                <div class="agenda-dt-head">
                                    <button type="button" class="agenda-dt-nav" data-prev aria-label="Mês anterior">&#8249;</button>
                                    <span class="agenda-dt-month" data-month-label></span>
                                    <button type="button" class="agenda-dt-nav" data-next aria-label="Próximo mês">&#8250;</button>
                                </div>

                                <div class="agenda-dt-body">
                                    <div class="agenda-dt-weekdays">
                                        <span>Dom</span>
                                        <span>Seg</span>
                                        <span>Ter</span>
                                        <span>Qua</span>
                                        <span>Qui</span>
                                        <span>Sex</span>
                                        <span>Sáb</span>
                                    </div>

                                    <div class="agenda-dt-days" data-days></div>
                                </div>

                                <div class="agenda-dt-divider"></div>

                                <div class="agenda-time-area">
                                    <div class="agenda-time-title">
                                        Horário de início
                                    </div>

                                    <div class="agenda-time-grid" data-start-times></div>
                                </div>

                                <div class="agenda-dt-divider"></div>

                                <div class="agenda-time-area">
                                    <div class="agenda-time-title">
                                        Horário de fim
                                    </div>

                                    <div class="agenda-time-grid" data-end-times></div>
                                </div>

                                <div class="agenda-dt-result">
                                    <span class="agenda-dt-result-text is-empty" data-result>
                                        Nenhuma data selecionada
                                    </span>

                                    <button type="button" class="agenda-dt-clear" data-clear aria-label="Limpar seleção">&times;</button>
                                </div>
                            </div>

                            <input type="hidden" name="data_evento_agenda" id="data_evento_agenda" value="{{ $dataAgenda }}">
                            <input type="hidden" name="hora_inicio_agenda" id="hora_inicio_agenda" value="{{ $horaInicio }}">
                            <input type="hidden" name="hora_fim_agenda" id="hora_fim_agenda" value="{{ $horaFim }}">

                            @error('data_evento_agenda')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @error('hora_inicio_agenda')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @error('hora_fim_agenda')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <small class="text-muted d-block mt-2">
                                Atualize no calendário a data, o horário de início e o horário de fim.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="agenda-actions">
                    <a href="{{ route('admin.agendas.index') }}" class="agenda-btn agenda-btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="agenda-btn agenda-btn-primary">
                        <i class="fas fa-save"></i>
                        Salvar Alterações
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataInput = document.getElementById('data_evento_agenda');
        const inicioInput = document.getElementById('hora_inicio_agenda');
        const fimInput = document.getElementById('hora_fim_agenda');
        const picker = document.getElementById('agenda_dt_edit');

        if (!picker || !dataInput || !inicioInput || !fimInput) {
            return;
        }

        const form = picker.closest('form');
        const monthLabel = picker.querySelector('[data-month-label]');
        const daysGrid = picker.querySelector('[data-days]');
        const startTimesGrid = picker.querySelector('[data-start-times]');
        const endTimesGrid = picker.querySelector('[data-end-times]');
        const result = picker.querySelector('[data-result]');
        const clearButton = picker.querySelector('[data-clear]');
        const prevButton = picker.querySelector('[data-prev]');
        const nextButton = picker.querySelector('[data-next]');

        const months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril',
            'Maio', 'Junho', 'Julho', 'Agosto',
            'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        const times = [];

        for (let hour = 7; hour <= 20; hour += 1) {
            ['00', '30'].forEach(function(minute) {
                if (hour === 20 && minute === '30') {
                    return;
                }

                times.push(String(hour).padStart(2, '0') + ':' + minute);
            });
        }

        let selectedDate = dataInput.value ? new Date(dataInput.value + 'T00:00:00') : null;
        let selectedStart = inicioInput.value ? inicioInput.value.slice(0, 5) : null;
        let selectedEnd = fimInput.value ? fimInput.value.slice(0, 5) : null;

        let current = selectedDate ?
            new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1) :
            new Date();

        current = new Date(current.getFullYear(), current.getMonth(), 1);

        const today = new Date();
        const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        function pad(value) {
            return String(value).padStart(2, '0');
        }

        function isSameDate(first, second) {
            return first && second &&
                first.getFullYear() === second.getFullYear() &&
                first.getMonth() === second.getMonth() &&
                first.getDate() === second.getDate();
        }

        function formatDate(date) {
            return pad(date.getDate()) + '/' + pad(date.getMonth() + 1) + '/' + date.getFullYear();
        }

        function formatDateDatabase(date) {
            return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
        }

        function timeToMinutes(time) {
            if (!time) {
                return null;
            }

            const parts = time.split(':').map(Number);

            return parts[0] * 60 + parts[1];
        }

        function getNextTime(time) {
            const index = times.indexOf(time);

            if (index >= 0 && times[index + 1]) {
                return times[index + 1];
            }

            return null;
        }

        function syncFields() {
            dataInput.value = selectedDate ? formatDateDatabase(selectedDate) : '';
            inicioInput.value = selectedStart ? selectedStart + ':00' : '';
            fimInput.value = selectedEnd ? selectedEnd + ':00' : '';

            if (selectedDate && selectedStart && selectedEnd) {
                result.textContent = weekdays[selectedDate.getDay()] + ', ' + formatDate(selectedDate) + ' — ' + selectedStart + ' às ' + selectedEnd;
                result.classList.remove('is-empty');
                clearButton.style.display = 'inline-flex';
                picker.classList.remove('is-invalid');
                return;
            }

            if (selectedDate && selectedStart && !selectedEnd) {
                result.textContent = formatDate(selectedDate) + ' — selecione o horário de fim';
                result.classList.remove('is-empty');
                clearButton.style.display = 'inline-flex';
                return;
            }

            if (selectedDate) {
                result.textContent = formatDate(selectedDate) + ' — selecione os horários';
                result.classList.remove('is-empty');
                clearButton.style.display = 'inline-flex';
                return;
            }

            result.textContent = 'Nenhuma data selecionada';
            result.classList.add('is-empty');
            clearButton.style.display = 'none';
        }

        function renderStartTimes() {
            startTimesGrid.innerHTML = '';

            if (!selectedDate) {
                startTimesGrid.innerHTML = '<span class="text-muted small">Selecione uma data primeiro.</span>';
                return;
            }

            times.forEach(function(time) {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'agenda-time-btn' + (selectedStart === time ? ' is-selected' : '');
                button.textContent = time;

                button.addEventListener('click', function() {
                    selectedStart = time;

                    if (!selectedEnd || timeToMinutes(selectedEnd) <= timeToMinutes(selectedStart)) {
                        selectedEnd = getNextTime(selectedStart);
                    }

                    renderStartTimes();
                    renderEndTimes();
                    syncFields();
                });

                startTimesGrid.appendChild(button);
            });
        }

        function renderEndTimes() {
            endTimesGrid.innerHTML = '';

            if (!selectedDate) {
                endTimesGrid.innerHTML = '<span class="text-muted small">Selecione uma data primeiro.</span>';
                return;
            }

            if (!selectedStart) {
                endTimesGrid.innerHTML = '<span class="text-muted small">Selecione o horário de início.</span>';
                return;
            }

            times.forEach(function(time) {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'agenda-time-btn' + (selectedEnd === time ? ' is-selected' : '');
                button.textContent = time;

                const disabled = timeToMinutes(time) <= timeToMinutes(selectedStart);

                if (disabled) {
                    button.disabled = true;
                } else {
                    button.addEventListener('click', function() {
                        selectedEnd = time;

                        renderEndTimes();
                        syncFields();
                    });
                }

                endTimesGrid.appendChild(button);
            });
        }

        function renderCalendar() {
            monthLabel.textContent = months[current.getMonth()] + ' ' + current.getFullYear();
            daysGrid.innerHTML = '';

            const firstDay = new Date(current.getFullYear(), current.getMonth(), 1).getDay();
            const totalDays = new Date(current.getFullYear(), current.getMonth() + 1, 0).getDate();

            for (let index = 0; index < firstDay; index += 1) {
                const empty = document.createElement('div');
                empty.className = 'agenda-dt-empty';
                daysGrid.appendChild(empty);
            }

            for (let day = 1; day <= totalDays; day += 1) {
                const button = document.createElement('button');
                const date = new Date(current.getFullYear(), current.getMonth(), day);

                button.type = 'button';
                button.className = 'agenda-dt-day';
                button.textContent = day;

                if (isSameDate(date, todayDate)) {
                    button.classList.add('is-today');
                }

                if (isSameDate(date, selectedDate)) {
                    button.classList.add('is-selected');
                }

                button.addEventListener('click', function() {
                    selectedDate = date;

                    renderCalendar();
                    renderStartTimes();
                    renderEndTimes();
                    syncFields();
                });

                daysGrid.appendChild(button);
            }
        }

        prevButton.addEventListener('click', function() {
            current.setMonth(current.getMonth() - 1);
            renderCalendar();
        });

        nextButton.addEventListener('click', function() {
            current.setMonth(current.getMonth() + 1);
            renderCalendar();
        });

        clearButton.addEventListener('click', function() {
            selectedDate = null;
            selectedStart = null;
            selectedEnd = null;
            current = new Date(today.getFullYear(), today.getMonth(), 1);

            renderCalendar();
            renderStartTimes();
            renderEndTimes();
            syncFields();
        });

        form?.addEventListener('submit', function(event) {
            if (!dataInput.value || !inicioInput.value || !fimInput.value) {
                event.preventDefault();

                picker.classList.add('is-invalid');
                result.textContent = 'Selecione a data, o horário de início e o horário de fim antes de salvar.';
                result.classList.remove('is-empty');

                picker.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        renderCalendar();
        renderStartTimes();
        renderEndTimes();
        syncFields();
    });
</script>
@endpush
@endsection