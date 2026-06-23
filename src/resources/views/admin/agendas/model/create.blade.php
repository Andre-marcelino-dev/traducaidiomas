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
            color: rgba(255,255,255,.78);
        }

        .agenda-back-btn {
            background: rgba(255,255,255,.14);
            border: 1px solid rgba(255,255,255,.2);
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
            background: rgba(255,255,255,.22);
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
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .agenda-dt-month {
            color: #fff;
            font-size: .95rem;
            font-weight: 900;
        }

        .agenda-dt-nav {
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 12px;
            background: rgba(255,255,255,.12);
            color: #fff;
            font-size: 1.2rem;
            line-height: 1;
            transition: .2s ease;
        }

        .agenda-dt-nav:hover {
            background: rgba(255,255,255,.22);
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

        .agenda-dt-day:hover:not(:disabled) {
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

        .agenda-dt-day:disabled {
            background: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
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
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            box-shadow: 0 10px 22px rgba(34, 197, 94, .22);
        }

        .agenda-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(34, 197, 94, .28);
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

            .agenda-form-card-body {
                padding: 1rem;
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

    <div class="agenda-page-header">
        <div>
            <span class="agenda-page-kicker">Administração</span>
            <h1>Novo Agendamento</h1>
            <p>Cadastre um novo agendamento, selecione aluno, professor, data, horário e status da aula.</p>
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
                <p>Preencha as informações abaixo para criar um novo evento na agenda.</p>
            </div>

            <div class="agenda-form-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M7 1.75C7.41421 1.75 7.75 2.08579 7.75 2.5V3.26272C8.41203 3.24999 9.1414 3.24999 9.94358 3.25H14.0564C14.8586 3.24999 15.588 3.24999 16.25 3.26272V2.5C16.25 2.08579 16.5858 1.75 17 1.75C17.4142 1.75 17.75 2.08579 17.75 2.5V3.32709C18.0099 3.34691 18.2561 3.37182 18.489 3.40313C19.6614 3.56076 20.6104 3.89288 21.3588 4.64124C22.1071 5.38961 22.4392 6.33855 22.5969 7.51098C22.6472 7.88567 22.681 8.29459 22.7037 8.74007C22.7337 8.82106 22.75 8.90861 22.75 9C22.75 9.06932 22.7406 9.13644 22.723 9.20016C22.75 10.0021 22.75 10.9128 22.75 11.9436V14C22.75 14.4142 22.4142 14.75 22 14.75C21.5858 14.75 21.25 14.4142 21.25 14V12C21.25 11.146 21.2497 10.4027 21.2369 9.75H2.76309C2.75032 10.4027 2.75 11.146 2.75 12V14C2.75 15.9068 2.75159 17.2615 2.88976 18.2892C3.02502 19.2952 3.27869 19.8749 3.7019 20.2981C4.12511 20.7213 4.70476 20.975 5.71085 21.1102C6.73851 21.2484 8.09318 21.25 10 21.25H14C14.4142 21.25 14.75 21.5858 14.75 22C14.75 22.4142 14.4142 22.75 14 22.75H9.94359C8.10583 22.75 6.65019 22.75 5.51098 22.5969C4.33856 22.4392 3.38961 22.1071 2.64124 21.3588C1.89288 20.6104 1.56076 19.6614 1.40314 18.489C1.24997 17.3498 1.24998 15.8942 1.25 14.0564V11.9436C1.24999 10.9127 1.24998 10.0021 1.27701 9.20017C1.25941 9.13645 1.25 9.06932 1.25 9C1.25 8.90862 1.26634 8.82105 1.29627 8.74006C1.31895 8.29458 1.35276 7.88566 1.40314 7.51098C1.56076 6.33856 1.89288 5.38961 2.64124 4.64124C3.38961 3.89288 4.33856 3.56076 5.51098 3.40313C5.7439 3.37182 5.99006 3.34691 6.25 3.32709V2.5C6.25 2.08579 6.58579 1.75 7 1.75ZM2.83168 8.25H21.1683C21.1523 8.06061 21.1331 7.88123 21.1102 7.71085C20.975 6.70476 20.7213 6.12511 20.2981 5.7019C19.8749 5.27869 19.2952 5.02502 18.2892 4.88976C17.2615 4.75159 15.9068 4.75 14 4.75H10C8.09318 4.75 6.73851 4.75159 5.71085 4.88976C4.70476 5.02502 4.12511 5.27869 3.7019 5.7019C3.27869 6.12511 3.02502 6.70476 2.88976 7.71085C2.86685 7.88123 2.8477 8.06061 2.83168 8.25ZM18 15.75C16.7574 15.75 15.75 16.7574 15.75 18C15.75 19.2426 16.7574 20.25 18 20.25C19.2426 20.25 20.25 19.2426 20.25 18C20.25 16.7574 19.2426 15.75 18 15.75ZM14.25 18C14.25 15.9289 15.9289 14.25 18 14.25C20.0711 14.25 21.75 15.9289 21.75 18C21.75 18.7643 21.5213 19.4752 21.1287 20.068L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L20.068 21.1287C19.4752 21.5213 18.7643 21.75 18 21.75C15.9289 21.75 14.25 20.0711 14.25 18Z" fill="#FFFFFF"/>
</svg>
            </div>
        </div>

        <div class="agenda-form-card-body">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <strong>Verifique os campos abaixo:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.agendas.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="agenda-section">
                            <div class="agenda-section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C9.37666 1.25 7.25001 3.37665 7.25001 6C7.25001 8.62335 9.37666 10.75 12 10.75C14.6234 10.75 16.75 8.62335 16.75 6C16.75 3.37665 14.6234 1.25 12 1.25ZM8.75001 6C8.75001 4.20507 10.2051 2.75 12 2.75C13.7949 2.75 15.25 4.20507 15.25 6C15.25 7.79493 13.7949 9.25 12 9.25C10.2051 9.25 8.75001 7.79493 8.75001 6Z" fill="#163d8f"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 12.25C9.68646 12.25 7.55494 12.7759 5.97546 13.6643C4.4195 14.5396 3.25001 15.8661 3.25001 17.5L3.24995 17.602C3.24882 18.7638 3.2474 20.222 4.52642 21.2635C5.15589 21.7761 6.03649 22.1406 7.22622 22.3815C8.41927 22.6229 9.97424 22.75 12 22.75C14.0258 22.75 15.5808 22.6229 16.7738 22.3815C17.9635 22.1406 18.8441 21.7761 19.4736 21.2635C20.7526 20.222 20.7512 18.7638 20.7501 17.602L20.75 17.5C20.75 15.8661 19.5805 14.5396 18.0246 13.6643C16.4451 12.7759 14.3136 12.25 12 12.25ZM4.75001 17.5C4.75001 16.6487 5.37139 15.7251 6.71085 14.9717C8.02681 14.2315 9.89529 13.75 12 13.75C14.1047 13.75 15.9732 14.2315 17.2892 14.9717C18.6286 15.7251 19.25 16.6487 19.25 17.5C19.25 18.8078 19.2097 19.544 18.5264 20.1004C18.1559 20.4022 17.5365 20.6967 16.4762 20.9113C15.4193 21.1252 13.9742 21.25 12 21.25C10.0258 21.25 8.58075 21.1252 7.5238 20.9113C6.46354 20.6967 5.84413 20.4022 5.4736 20.1004C4.79033 19.544 4.75001 18.8078 4.75001 17.5Z" fill="#163d8f"/>
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
                                            <option value="{{ $aluno->id_aluno }}" {{ old('id_aluno') == $aluno->id_aluno ? 'selected' : '' }}>
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
                                            <option value="{{ $professor->id_professor }}" {{ old('id_professor') == $professor->id_professor ? 'selected' : '' }}>
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
                                    value="{{ old('titulo_agenda') }}"
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
                                    placeholder="Descrição da aula, conteúdo ou observações...">{{ old('descricao_agenda') }}</textarea>

                                @error('descricao_agenda')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Link da Aula</label>

                                <input type="url"
                                    name="link_aula_agenda"
                                    value="{{ old('link_aula_agenda') }}"
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

                            @php
                                $statusSelecionado = old('status_agenda', 'pendente');
                            @endphp

                            <div class="agenda-status-grid">
                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="pendente" {{ $statusSelecionado == 'pendente' ? 'checked' : '' }}>
                                    <span>
                                        <span class="agenda-status-title">Pendente</span>
                                        <span class="agenda-status-desc">Aguardando confirmação</span>
                                    </span>
                                </label>

                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="confirmado" {{ $statusSelecionado == 'confirmado' ? 'checked' : '' }}>
                                    <span>
                                        <span class="agenda-status-title">Confirmado</span>
                                        <span class="agenda-status-desc">Aula confirmada</span>
                                    </span>
                                </label>

                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="cancelado" {{ $statusSelecionado == 'cancelado' ? 'checked' : '' }}>
                                    <span>
                                        <span class="agenda-status-title">Cancelado</span>
                                        <span class="agenda-status-desc">Aula cancelada</span>
                                    </span>
                                </label>

                                <label class="agenda-status-option">
                                    <input type="radio" name="status_agenda" value="reagendado" {{ $statusSelecionado == 'reagendado' ? 'checked' : '' }}>
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
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25H12.0574C14.3658 1.24999 16.1748 1.24998 17.5863 1.43975C19.031 1.63399 20.1711 2.03933 21.0659 2.93414C21.9607 3.82895 22.366 4.96897 22.5603 6.41371C22.75 7.82519 22.75 9.63423 22.75 11.9426V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12C21.25 9.62178 21.2484 7.91356 21.0736 6.61358C20.9018 5.33517 20.5749 4.56445 20.0052 3.9948C19.4355 3.42514 18.6648 3.09825 17.3864 2.92637C16.0864 2.75159 14.3782 2.75 12 2.75C9.62178 2.75 7.91356 2.75159 6.61358 2.92637ZM12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V11.6893L15.0303 13.9697C15.3232 14.2626 15.3232 14.7374 15.0303 15.0303C14.7374 15.3232 14.2626 15.3232 13.9697 15.0303L11.8358 12.8964C11.5468 12.6074 11.4022 12.4629 11.3261 12.2791C11.25 12.0954 11.25 11.891 11.25 11.4822V8C11.25 7.58579 11.5858 7.25 12 7.25Z" fill="#163d8f"/>
</svg>
                                Data e horário
                            </div>

                            <div class="agenda-dt-picker
                                {{ $errors->has('data_evento_agenda') || $errors->has('hora_inicio_agenda') || $errors->has('hora_fim_agenda') ? 'is-invalid' : '' }}"
                                id="agenda_dt_create">

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
                                    <div class="agenda-time-title" data-start-label>
                                        Horário de início
                                    </div>

                                    <div class="agenda-time-grid" data-start-times></div>
                                </div>

                                <div class="agenda-dt-divider"></div>

                                <div class="agenda-time-area">
                                    <div class="agenda-time-title" data-end-label>
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

                            <input type="hidden" name="data_evento_agenda" id="data_evento_agenda" value="{{ old('data_evento_agenda') }}">
                            <input type="hidden" name="hora_inicio_agenda" id="hora_inicio_agenda" value="{{ old('hora_inicio_agenda') }}">
                            <input type="hidden" name="hora_fim_agenda" id="hora_fim_agenda" value="{{ old('hora_fim_agenda') }}">

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
                                Selecione no calendário a data, o horário de início e o horário de fim.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="agenda-actions">
                    <a href="{{ route('admin.agendas.index') }}" class="agenda-btn agenda-btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>

                    <button type="submit" class="agenda-btn agenda-btn-primary">
                        <i class="fas fa-save"></i>
                        Salvar Agendamento
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
    const picker = document.getElementById('agenda_dt_create');

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

    const now = new Date();

    const minDateTime = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate(),
        now.getHours(),
        now.getMinutes(),
        0,
        0
    );

    let selectedDate = dataInput.value ? new Date(dataInput.value + 'T00:00:00') : null;
    let selectedStart = inicioInput.value ? inicioInput.value.slice(0, 5) : null;
    let selectedEnd = fimInput.value ? fimInput.value.slice(0, 5) : null;

    let current = selectedDate
        ? new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1)
        : new Date(now.getFullYear(), now.getMonth(), 1);

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

    function isPastDay(date) {
        const compare = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const today = new Date(minDateTime.getFullYear(), minDateTime.getMonth(), minDateTime.getDate());

        return compare < today;
    }

    function isPastDateTime(date, time) {
        if (!date || !time) {
            return false;
        }

        const [hour, minute] = time.split(':').map(Number);

        const candidate = new Date(
            date.getFullYear(),
            date.getMonth(),
            date.getDate(),
            hour,
            minute,
            0,
            0
        );

        return candidate < minDateTime;
    }

    function getNextTime(time) {
        const index = times.indexOf(time);

        if (index >= 0 && times[index + 1]) {
            return times[index + 1];
        }

        return null;
    }

    function syncFields() {
        if (selectedDate) {
            dataInput.value = formatDateDatabase(selectedDate);
        } else {
            dataInput.value = '';
        }

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

            if (isPastDateTime(selectedDate, time)) {
                button.disabled = true;
            } else {
                button.addEventListener('click', function() {
                    selectedStart = time;

                    if (!selectedEnd || timeToMinutes(selectedEnd) <= timeToMinutes(selectedStart)) {
                        selectedEnd = getNextTime(selectedStart);
                    }

                    renderStartTimes();
                    renderEndTimes();
                    syncFields();
                });
            }

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
        const today = new Date(minDateTime.getFullYear(), minDateTime.getMonth(), minDateTime.getDate());

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

            if (isSameDate(date, today)) {
                button.classList.add('is-today');
            }

            if (isSameDate(date, selectedDate)) {
                button.classList.add('is-selected');
            }

            if (isPastDay(date)) {
                button.disabled = true;
            } else {
                button.addEventListener('click', function() {
                    selectedDate = date;

                    if (selectedStart && isPastDateTime(selectedDate, selectedStart)) {
                        selectedStart = null;
                        selectedEnd = null;
                    }

                    renderCalendar();
                    renderStartTimes();
                    renderEndTimes();
                    syncFields();
                });
            }

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
        current = new Date(now.getFullYear(), now.getMonth(), 1);

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