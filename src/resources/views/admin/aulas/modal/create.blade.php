@extends('admin.layout.admin')

@section('content')
<div class="container-fluid aula-page">

    <style>
        .aula-page {
            padding-bottom: 2rem;
        }

        .aula-page-header {
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

        .aula-page-kicker {
            display: inline-block;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #bfdbfe;
            margin-bottom: .35rem;
        }

        .aula-page-header h1 {
            font-size: 1.7rem;
            font-weight: 800;
            margin: 0 0 .35rem;
        }

        .aula-page-header p {
            margin: 0;
            max-width: 680px;
            font-size: .92rem;
            color: rgba(255, 255, 255, .78);
        }

        .aula-back-btn {
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

        .aula-back-btn:hover {
            background: rgba(255, 255, 255, .22);
            color: #fff;
            transform: translateY(-1px);
        }

        .aula-form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
        }

        .aula-form-card-header {
            background: linear-gradient(135deg, #f8faff, #eef3ff);
            border-bottom: 1px solid #e5e7eb;
            padding: 1.4rem 1.6rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .aula-form-card-header h5 {
            margin: 0 0 .25rem;
            color: #0f172a;
            font-weight: 800;
        }

        .aula-form-card-header p {
            margin: 0;
            color: #64748b;
            font-size: .85rem;
        }

        .aula-form-icon {
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

        .aula-form-card-body {
            padding: 1.6rem;
        }

        .aula-section {
            background: #fff;
            border: 1px solid #eef2f7;
            border-radius: 20px;
            padding: 1.2rem;
            height: 100%;
        }

        .aula-section-title {
            display: flex;
            align-items: center;
            gap: .55rem;
            color: #0f172a;
            font-size: .92rem;
            font-weight: 900;
            margin-bottom: 1rem;
        }

        .aula-section-title i {
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

        .aula-dt-picker {
            border: 1px solid #dbe4f0;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
            width: 100%;
        }

        .aula-dt-picker.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 .22rem rgba(239, 68, 68, .12);
        }

        .aula-dt-head,
        .aula-dt-result {
            align-items: center;
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.95rem 1rem;
        }

        .aula-dt-head {
            background: linear-gradient(135deg, #0f172a, #163d8f);
            border-bottom: 1px solid rgba(255, 255, 255, .12);
        }

        .aula-dt-month {
            color: #fff;
            font-size: .95rem;
            font-weight: 900;
        }

        .aula-dt-nav {
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

        .aula-dt-nav:hover {
            background: rgba(255, 255, 255, .22);
        }

        .aula-dt-body {
            padding: 1rem;
        }

        .aula-dt-weekdays,
        .aula-dt-days {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }

        .aula-dt-weekdays {
            margin-bottom: .5rem;
        }

        .aula-dt-weekdays span {
            color: #94a3b8;
            font-size: .68rem;
            font-weight: 900;
            padding: .25rem 0;
            text-align: center;
            text-transform: uppercase;
        }

        .aula-dt-days {
            gap: .35rem;
            grid-template-rows: repeat(6, 42px);
        }

        .aula-dt-day,
        .aula-dt-empty {
            min-height: 42px;
            height: 42px;
            border-radius: 14px;
        }

        .aula-dt-day {
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

        .aula-dt-day:hover:not(:disabled) {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        .aula-dt-day.is-today {
            border-color: #2563eb;
            color: #2563eb;
        }

        .aula-dt-day.is-selected {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            border-color: #1d4ed8;
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, .22);
        }

        .aula-dt-day:disabled {
            background: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .aula-dt-divider {
            height: 1px;
            background: #eef2f7;
        }

        .aula-dt-times-label {
            color: #475569;
            font-size: .78rem;
            font-weight: 900;
            padding: 1rem 1rem 0;
        }

        .aula-dt-times {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            padding: .9rem 1rem 1rem;
        }

        .aula-dt-time {
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

        .aula-dt-time:hover:not(:disabled) {
            background: #eff6ff;
            border-color: #bfdbfe;
            color: #1d4ed8;
        }

        .aula-dt-time.is-selected {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, .18);
        }

        .aula-dt-time:disabled {
            background: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .aula-dt-result {
            border-top: 1px solid #eef2f7;
            background: #f8fafc;
        }

        .aula-dt-result-text {
            color: #0f172a;
            font-size: .82rem;
            font-weight: 900;
        }

        .aula-dt-result-text.is-empty {
            color: #94a3b8;
            font-weight: 600;
        }

        .aula-dt-clear {
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

        .aula-dt-clear:hover {
            background: #fee2e2;
            border-color: #fecaca;
            color: #dc2626;
        }

        .aula-students-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .75rem;
            flex-wrap: wrap;
        }

        .aula-students-counter {
            background: #eef3ff;
            color: #1d4ed8;
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .72rem;
            font-weight: 900;
        }

        .aula-students-box {
            border: 1px solid #dbe4f0;
            border-radius: 18px;
            padding: .9rem;
            max-height: 330px;
            overflow-y: auto;
            background: #f8fafc;
        }

        .aula-student-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #fff;
            padding: .75rem;
            height: 100%;
            cursor: pointer;
            transition: .2s ease;
        }

        .aula-student-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
            transform: translateY(-1px);
        }

        .aula-student-name {
            color: #0f172a;
            font-size: .86rem;
            font-weight: 900;
        }

        .aula-student-info {
            color: #64748b;
            font-size: .74rem;
            margin-top: .15rem;
        }

        .aula-actions {
            border-top: 1px solid #eef2f7;
            margin-top: 1.5rem;
            padding-top: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: .7rem;
        }

        .aula-btn {
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

        .aula-btn-primary {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #fff;
            box-shadow: 0 10px 22px rgba(34, 197, 94, .22);
        }

        .aula-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(34, 197, 94, .28);
        }

        .aula-btn-secondary {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            box-shadow: 0 10px 22px rgba(239, 68, 68, .12);
        }

        .aula-btn-secondary:hover {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(239, 68, 68, .22);
        }

        @media (max-width: 768px) {
            .aula-page-header {
                align-items: flex-start;
                flex-direction: column;
                padding: 1.5rem;
            }

            .aula-form-card-body {
                padding: 1rem;
            }

            .aula-form-card-header {
                padding: 1.1rem;
            }

            .aula-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .aula-btn {
                width: 100%;
            }
        }
    </style>

    <div class="aula-page-header">
        <div>
            <span class="aula-page-kicker">Administração</span>
            <h1>Nova Aula</h1>
            <p>Cadastre uma nova aula, selecione professor, curso, alunos participantes, data e horário.</p>
        </div>

        <a href="{{ route('admin.aulas.index') }}" class="aula-back-btn">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <div class="aula-form-card">
        <div class="aula-form-card-header">
            <div>
                <h5>Informações da Aula</h5>
                <p>Preencha os dados abaixo para agendar uma nova aula.</p>
            </div>

            <div class="aula-form-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7 1.75C7.41421 1.75 7.75 2.08579 7.75 2.5V3.26272C8.41203 3.24999 9.1414 3.24999 9.94358 3.25H14.0564C14.8586 3.24999 15.588 3.24999 16.25 3.26272V2.5C16.25 2.08579 16.5858 1.75 17 1.75C17.4142 1.75 17.75 2.08579 17.75 2.5V3.32709C18.0099 3.34691 18.2561 3.37182 18.489 3.40313C19.6614 3.56076 20.6104 3.89288 21.3588 4.64124C22.1071 5.38961 22.4392 6.33855 22.5969 7.51098C22.6472 7.88567 22.681 8.29459 22.7037 8.74007C22.7337 8.82106 22.75 8.90861 22.75 9C22.75 9.06932 22.7406 9.13644 22.723 9.20016C22.75 10.0021 22.75 10.9128 22.75 11.9436V14C22.75 14.4142 22.4142 14.75 22 14.75C21.5858 14.75 21.25 14.4142 21.25 14V12C21.25 11.146 21.2497 10.4027 21.2369 9.75H2.76309C2.75032 10.4027 2.75 11.146 2.75 12V14C2.75 15.9068 2.75159 17.2615 2.88976 18.2892C3.02502 19.2952 3.27869 19.8749 3.7019 20.2981C4.12511 20.7213 4.70476 20.975 5.71085 21.1102C6.73851 21.2484 8.09318 21.25 10 21.25H14C14.4142 21.25 14.75 21.5858 14.75 22C14.75 22.4142 14.4142 22.75 14 22.75H9.94359C8.10583 22.75 6.65019 22.75 5.51098 22.5969C4.33856 22.4392 3.38961 22.1071 2.64124 21.3588C1.89288 20.6104 1.56076 19.6614 1.40314 18.489C1.24997 17.3498 1.24998 15.8942 1.25 14.0564V11.9436C1.24999 10.9127 1.24998 10.0021 1.27701 9.20017C1.25941 9.13645 1.25 9.06932 1.25 9C1.25 8.90862 1.26634 8.82105 1.29627 8.74006C1.31895 8.29458 1.35276 7.88566 1.40314 7.51098C1.56076 6.33856 1.89288 5.38961 2.64124 4.64124C3.38961 3.89288 4.33856 3.56076 5.51098 3.40313C5.7439 3.37182 5.99006 3.34691 6.25 3.32709V2.5C6.25 2.08579 6.58579 1.75 7 1.75ZM2.83168 8.25H21.1683C21.1523 8.06061 21.1331 7.88123 21.1102 7.71085C20.975 6.70476 20.7213 6.12511 20.2981 5.7019C19.8749 5.27869 19.2952 5.02502 18.2892 4.88976C17.2615 4.75159 15.9068 4.75 14 4.75H10C8.09318 4.75 6.73851 4.75159 5.71085 4.88976C4.70476 5.02502 4.12511 5.27869 3.7019 5.7019C3.27869 6.12511 3.02502 6.70476 2.88976 7.71085C2.86685 7.88123 2.8477 8.06061 2.83168 8.25ZM18 15.75C16.7574 15.75 15.75 16.7574 15.75 18C15.75 19.2426 16.7574 20.25 18 20.25C19.2426 20.25 20.25 19.2426 20.25 18C20.25 16.7574 19.2426 15.75 18 15.75ZM14.25 18C14.25 15.9289 15.9289 14.25 18 14.25C20.0711 14.25 21.75 15.9289 21.75 18C21.75 18.7643 21.5213 19.4752 21.1287 20.068L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L20.068 21.1287C19.4752 21.5213 18.7643 21.75 18 21.75C15.9289 21.75 14.25 20.0711 14.25 18Z" fill="#FFFFFF" />
                </svg>
            </div>
        </div>

        <div class="aula-form-card-body">
            <form action="{{ route('admin.aulas.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="aula-section">
                            <div class="aula-section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C9.37666 1.25 7.25001 3.37665 7.25001 6C7.25001 8.62335 9.37666 10.75 12 10.75C14.6234 10.75 16.75 8.62335 16.75 6C16.75 3.37665 14.6234 1.25 12 1.25ZM8.75001 6C8.75001 4.20507 10.2051 2.75 12 2.75C13.7949 2.75 15.25 4.20507 15.25 6C15.25 7.79493 13.7949 9.25 12 9.25C10.2051 9.25 8.75001 7.79493 8.75001 6Z" fill="#163d8f" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 12.25C9.68646 12.25 7.55494 12.7759 5.97546 13.6643C4.4195 14.5396 3.25001 15.8661 3.25001 17.5L3.24995 17.602C3.24882 18.7638 3.2474 20.222 4.52642 21.2635C5.15589 21.7761 6.03649 22.1406 7.22622 22.3815C8.41927 22.6229 9.97424 22.75 12 22.75C14.0258 22.75 15.5808 22.6229 16.7738 22.3815C17.9635 22.1406 18.8441 21.7761 19.4736 21.2635C20.7526 20.222 20.7512 18.7638 20.7501 17.602L20.75 17.5C20.75 15.8661 19.5805 14.5396 18.0246 13.6643C16.4451 12.7759 14.3136 12.25 12 12.25ZM4.75001 17.5C4.75001 16.6487 5.37139 15.7251 6.71085 14.9717C8.02681 14.2315 9.89529 13.75 12 13.75C14.1047 13.75 15.9732 14.2315 17.2892 14.9717C18.6286 15.7251 19.25 16.6487 19.25 17.5C19.25 18.8078 19.2097 19.544 18.5264 20.1004C18.1559 20.4022 17.5365 20.6967 16.4762 20.9113C15.4193 21.1252 13.9742 21.25 12 21.25C10.0258 21.25 8.58075 21.1252 7.5238 20.9113C6.46354 20.6967 5.84413 20.4022 5.4736 20.1004C4.79033 19.544 4.75001 18.8078 4.75001 17.5Z" fill="#163d8f" />
                                </svg>
                                Dados principais
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Título da Aula</label>
                                <input type="text"
                                    name="titulo_aulas"
                                    class="form-control"
                                    value="{{ old('titulo_aulas') }}"
                                    placeholder="Ex: Aula de Italiano - Conversação"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descrição</label>
                                <textarea name="descricao_aulas"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Descreva a aula, conteúdo ou observações..."
                                    required>{{ old('descricao_aulas') }}</textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Professor</label>
                                    <select name="id_professor" class="form-select" required>
                                        <option value="">Selecione um professor</option>

                                        @foreach($professores as $professor)
                                        <option value="{{ $professor->id_professor }}"
                                            {{ old('id_professor') == $professor->id_professor ? 'selected' : '' }}>
                                            {{ $professor->nome_professor }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status_aulas" class="form-select" required>
                                        <option value="ATIVO" {{ old('status_aulas') == 'ATIVO' ? 'selected' : '' }}>ATIVO</option>
                                        <option value="INATIVO" {{ old('status_aulas') == 'INATIVO' ? 'selected' : '' }}>INATIVO</option>
                                        <option value="CANCELADO" {{ old('status_aulas') == 'CANCELADO' ? 'selected' : '' }}>CANCELADO</option>
                                        <option value="FINALIZADA" {{ old('status_aulas') == 'FINALIZADA' ? 'selected' : '' }}>FINALIZADA</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label">Curso</label>
                                    <select name="id_curso" id="id_curso" class="form-select" required>
                                        <option value="">Selecione um curso</option>

                                        @foreach($cursos as $curso)
                                        <option value="{{ $curso->id_curso }}"
                                            data-nome="{{ $curso->nome_curso }}"
                                            {{ old('id_curso') == $curso->id_curso ? 'selected' : '' }}>
                                            {{ $curso->nome_curso }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nome do Curso</label>
                                    <input type="text"
                                        id="cursos_aulas"
                                        name="cursos_aulas"
                                        class="form-control"
                                        value="{{ old('cursos_aulas') }}"
                                        placeholder="O nome será preenchido pelo curso"
                                        required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">Link Teams</label>
                                <input type="url"
                                    name="link_teams"
                                    class="form-control"
                                    value="{{ old('link_teams') }}"
                                    placeholder="https://teams.microsoft.com/...">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="aula-section">
                            <div class="aula-section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25H12.0574C14.3658 1.24999 16.1748 1.24998 17.5863 1.43975C19.031 1.63399 20.1711 2.03933 21.0659 2.93414C21.9607 3.82895 22.366 4.96897 22.5603 6.41371C22.75 7.82519 22.75 9.63423 22.75 11.9426V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12C21.25 9.62178 21.2484 7.91356 21.0736 6.61358C20.9018 5.33517 20.5749 4.56445 20.0052 3.9948C19.4355 3.42514 18.6648 3.09825 17.3864 2.92637C16.0864 2.75159 14.3782 2.75 12 2.75C9.62178 2.75 7.91356 2.75159 6.61358 2.92637ZM12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V11.6893L15.0303 13.9697C15.3232 14.2626 15.3232 14.7374 15.0303 15.0303C14.7374 15.3232 14.2626 15.3232 13.9697 15.0303L11.8358 12.8964C11.5468 12.6074 11.4022 12.4629 11.3261 12.2791C11.25 12.0954 11.25 11.891 11.25 11.4822V8C11.25 7.58579 11.5858 7.25 12 7.25Z" fill="#163d8f" />
                                </svg>
                                Data e horário
                            </div>

                            <div class="aula-dt-picker" id="agenda_aula_create">
                                <div class="aula-dt-head">
                                    <button type="button" class="aula-dt-nav" data-prev aria-label="Mês anterior">&#8249;</button>
                                    <span class="aula-dt-month" data-month-label></span>
                                    <button type="button" class="aula-dt-nav" data-next aria-label="Próximo mês">&#8250;</button>
                                </div>

                                <div class="aula-dt-body">
                                    <div class="aula-dt-weekdays">
                                        <span>Dom</span>
                                        <span>Seg</span>
                                        <span>Ter</span>
                                        <span>Qua</span>
                                        <span>Qui</span>
                                        <span>Sex</span>
                                        <span>Sab</span>
                                    </div>

                                    <div class="aula-dt-days" data-days></div>
                                </div>

                                <div class="aula-dt-divider"></div>

                                <div class="aula-dt-times-label" data-times-label>
                                    Selecione uma data para ver os horários
                                </div>

                                <div class="aula-dt-times" data-times></div>

                                <div class="aula-dt-result">
                                    <span class="aula-dt-result-text is-empty" data-result>Nenhuma data selecionada</span>
                                    <button type="button" class="aula-dt-clear" data-clear aria-label="Limpar seleção">&times;</button>
                                </div>
                            </div>

                            <input type="hidden" name="data_aulas" id="data_aulas_create" value="{{ old('data_aulas') }}">
                            <input type="hidden" name="hora_aulas" id="hora_aulas_create" value="{{ old('hora_aulas') }}">

                            @error('data_aulas')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @error('hora_aulas')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <small class="text-muted d-block mt-2">
                                Selecione no calendário a data e o horário da aula.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="aula-section mt-4">
                    <div class="aula-students-toolbar">
                        <div class="aula-section-title mb-0">
                           <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M9.46455 2.82057C11.0873 2.05981 12.913 2.05981 14.5358 2.82057L21.227 5.95756C22.2793 6.4509 22.7502 7.52222 22.7502 8.50002C22.7502 9.47782 22.2792 10.5491 21.227 11.0425L19.75 11.7349V16.6254C19.75 17.8785 19.1217 19.0883 17.978 19.7217C17.2263 20.138 16.2384 20.6391 15.1988 21.038C14.1715 21.4321 13.0339 21.75 12 21.75C10.9661 21.75 9.82851 21.4321 8.80117 21.038C7.76159 20.6391 6.77371 20.138 6.02199 19.7217C4.87826 19.0883 4.25 17.8785 4.25 16.6254V11.5C4.25 11.4586 4.25336 11.4179 4.25982 11.3783C4.2363 11.2343 4.25448 11.0818 4.32112 10.9397C4.49695 10.5647 4.94352 10.4032 5.31856 10.579L10.1014 12.8213C11.3207 13.3929 12.6798 13.3929 13.8991 12.8213L20.5902 9.68433C20.9934 9.49531 21.2502 9.04443 21.2502 8.50002C21.2502 7.95562 20.9934 7.50473 20.5902 7.31571L13.899 4.17873C12.6797 3.60709 11.3206 3.60709 10.1013 4.17872L3.41017 7.31567C3.03512 7.4915 2.58855 7.33 2.41272 6.95496C2.2369 6.57992 2.39839 6.13335 2.77343 5.95752L9.46455 2.82057ZM5.75 12.4379V16.6254C5.75 17.3884 6.12875 18.0661 6.74869 18.4095C7.46533 18.8063 8.38679 19.2724 9.33845 19.6375C10.3023 20.0073 11.2379 20.25 12 20.25C12.7621 20.25 13.6977 20.0073 14.6616 19.6375C15.6132 19.2724 16.5347 18.8063 17.2513 18.4095C17.8713 18.0661 18.25 17.3884 18.25 16.6254V12.4381L14.5358 14.1794C12.9131 14.9402 11.0874 14.9402 9.46464 14.1794L5.75 12.4379Z" fill="#2563eb"/>
<path d="M6.68936 7.70456C6.85253 8.08528 6.67616 8.52619 6.29544 8.68936L5.21977 9.15036C4.617 9.40869 4.21985 9.58 3.928 9.74418C3.65202 9.89944 3.53244 10.0154 3.45645 10.1307C3.38046 10.2459 3.32095 10.4015 3.28696 10.7164C3.25101 11.0493 3.25 11.4818 3.25 12.1376V15C3.25 15.4142 2.91422 15.75 2.5 15.75C2.08579 15.75 1.75 15.4142 1.75 15V12.0987C1.74998 11.4923 1.74996 10.9782 1.79563 10.5553C1.84421 10.1053 1.94986 9.69064 2.20419 9.30494C2.45851 8.91925 2.79805 8.65879 3.19254 8.43686C3.56327 8.2283 4.03569 8.02585 4.59315 7.78696L5.70456 7.31064C6.08529 7.14747 6.5262 7.32384 6.68936 7.70456Z" fill="#2563eb"/>
</svg> 
                            Alunos participantes
                        </div>

                        <span class="aula-students-counter" id="contadorAlunosCreate">
                            0 selecionados
                        </span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <input type="text"
                                class="form-control"
                                id="buscarAlunoCreate"
                                placeholder="Pesquisar aluno pelo nome, curso ou nível...">
                        </div>

                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-primary w-100 h-100" id="selecionarVisiveisCreate">
                                <i class="fas fa-check-double me-1"></i>
                                Selecionar visíveis
                            </button>
                        </div>
                    </div>

                    <div class="aula-students-box">
                        <div class="row g-2" id="lista-alunos-create">
                            @foreach($alunos as $aluno)
                            <div class="col-md-6 col-xl-4 aluno-item"
                                data-nome="{{ $aluno->nome_aluno ?? '' }}"
                                data-curso="{{ $aluno->curso_aluno ?? '' }}"
                                data-nivel="{{ $aluno->nivel_aluno ?? '' }}">

                                <label class="aula-student-card d-flex align-items-start gap-2">
                                    <input type="checkbox"
                                        name="alunos[]"
                                        value="{{ $aluno->id_aluno }}"
                                        class="form-check-input mt-1 aluno-checkbox"
                                        {{ in_array($aluno->id_aluno, old('alunos', [])) ? 'checked' : '' }}>

                                    <span>
                                        <strong class="d-block aula-student-name">{{ $aluno->nome_aluno }}</strong>
                                        <small class="aula-student-info">
                                            {{ $aluno->curso_aluno }} • {{ $aluno->nivel_aluno }}
                                        </small>
                                    </span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    @error('alunos')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="aula-actions">
                    <a href="{{ route('admin.aulas.index') }}" class="aula-btn aula-btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>

                    <button type="submit" class="aula-btn aula-btn-primary">
                        <i class="fas fa-save"></i>
                        Salvar Aula
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cursoSelect = document.getElementById('id_curso');
        const cursoInput = document.getElementById('cursos_aulas');
        const alunos = document.querySelectorAll('#lista-alunos-create .aluno-item');
        const selecionarVisiveis = document.getElementById('selecionarVisiveisCreate');
        const buscarAluno = document.getElementById('buscarAlunoCreate');
        const contadorAlunos = document.getElementById('contadorAlunosCreate');

        function normalizar(texto) {
            return String(texto || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim();
        }

        function atualizarContador() {
            const selecionados = document.querySelectorAll('#lista-alunos-create input[type="checkbox"]:checked').length;
            contadorAlunos.textContent = selecionados + (selecionados === 1 ? ' selecionado' : ' selecionados');
        }

        function filtrarAlunos() {
            const option = cursoSelect.options[cursoSelect.selectedIndex];
            const nomeCurso = normalizar(option?.dataset.nome || cursoInput.value || '');
            const termo = normalizar(buscarAluno?.value || '');

            if (option?.dataset.nome && !cursoInput.value) {
                cursoInput.value = option.dataset.nome;
            }

            alunos.forEach(function(item) {
                const cursoAluno = normalizar(item.dataset.curso);
                const nomeAluno = normalizar(item.dataset.nome);
                const nivelAluno = normalizar(item.dataset.nivel);

                const combinaCurso = !nomeCurso || cursoAluno === nomeCurso;
                const combinaBusca = !termo ||
                    nomeAluno.includes(termo) ||
                    cursoAluno.includes(termo) ||
                    nivelAluno.includes(termo);

                item.style.display = combinaCurso && combinaBusca ? '' : 'none';
            });
        }

        cursoSelect?.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];

            if (option?.dataset.nome) {
                cursoInput.value = option.dataset.nome;
            }

            filtrarAlunos();
        });

        cursoInput?.addEventListener('input', filtrarAlunos);
        buscarAluno?.addEventListener('input', filtrarAlunos);

        selecionarVisiveis?.addEventListener('click', function() {
            document.querySelectorAll('#lista-alunos-create .aluno-item').forEach(function(item) {
                if (item.style.display !== 'none') {
                    const checkbox = item.querySelector('input[type="checkbox"]');

                    if (checkbox) {
                        checkbox.checked = true;
                    }
                }
            });

            atualizarContador();
        });

        document.querySelectorAll('#lista-alunos-create input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', atualizarContador);
        });

        filtrarAlunos();
        atualizarContador();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataInput = document.getElementById('data_aulas_create');
        const horaInput = document.getElementById('hora_aulas_create');
        const picker = document.getElementById('agenda_aula_create');

        if (!picker || !dataInput || !horaInput) {
            return;
        }

        const form = picker.closest('form');
        const monthLabel = picker.querySelector('[data-month-label]');
        const daysGrid = picker.querySelector('[data-days]');
        const timesLabel = picker.querySelector('[data-times-label]');
        const timesGrid = picker.querySelector('[data-times]');
        const result = picker.querySelector('[data-result]');
        const clearButton = picker.querySelector('[data-clear]');
        const prevButton = picker.querySelector('[data-prev]');
        const nextButton = picker.querySelector('[data-next]');

        const months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril',
            'Maio', 'Junho', 'Julho', 'Agosto',
            'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        const weekdays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'];
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
        let selectedTime = horaInput.value ? horaInput.value.slice(0, 5) : null;
        let current = selectedDate ?
            new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1) :
            new Date(now.getFullYear(), now.getMonth(), 1);

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

        function syncFields() {
            if (selectedDate && selectedTime) {
                dataInput.value = formatDateDatabase(selectedDate);
                horaInput.value = selectedTime + ':00';

                result.textContent = weekdays[selectedDate.getDay()] + ', ' + formatDate(selectedDate) + ' às ' + selectedTime;
                result.classList.remove('is-empty');

                clearButton.style.display = 'inline-flex';
                picker.classList.remove('is-invalid');
                return;
            }

            dataInput.value = selectedDate ? formatDateDatabase(selectedDate) : '';
            horaInput.value = '';

            result.textContent = selectedDate ?
                formatDate(selectedDate) + ' — selecione o horário' :
                'Nenhuma data selecionada';

            result.classList.toggle('is-empty', !selectedDate);
            clearButton.style.display = selectedDate ? 'inline-flex' : 'none';
        }

        function isPastDay(date) {
            const compare = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const today = new Date(minDateTime.getFullYear(), minDateTime.getMonth(), minDateTime.getDate());

            return compare < today;
        }

        function renderTimes() {
            timesGrid.innerHTML = '';

            if (!selectedDate) {
                timesLabel.textContent = 'Selecione uma data para ver os horários';
                return;
            }

            timesLabel.textContent = 'Horários disponíveis — ' + weekdays[selectedDate.getDay()] + ', ' + formatDate(selectedDate);

            times.forEach(function(time) {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'aula-dt-time' + (selectedTime === time ? ' is-selected' : '');
                button.textContent = time;

                const [hour, minute] = time.split(':').map(Number);

                const optionDate = new Date(
                    selectedDate.getFullYear(),
                    selectedDate.getMonth(),
                    selectedDate.getDate(),
                    hour,
                    minute,
                    0,
                    0
                );

                const disabled = optionDate < minDateTime;

                if (disabled) {
                    button.disabled = true;
                } else {
                    button.addEventListener('click', function() {
                        selectedTime = time;

                        renderTimes();
                        syncFields();
                    });
                }

                timesGrid.appendChild(button);
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
                empty.className = 'aula-dt-empty';
                daysGrid.appendChild(empty);
            }

            for (let day = 1; day <= totalDays; day += 1) {
                const button = document.createElement('button');
                const date = new Date(current.getFullYear(), current.getMonth(), day);

                button.type = 'button';
                button.className = 'aula-dt-day';
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

                        if (selectedTime) {
                            const [hour, minute] = selectedTime.split(':').map(Number);

                            const candidate = new Date(
                                date.getFullYear(),
                                date.getMonth(),
                                date.getDate(),
                                hour,
                                minute,
                                0,
                                0
                            );

                            if (candidate < minDateTime) {
                                selectedTime = null;
                            }
                        }

                        renderCalendar();
                        renderTimes();
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
            selectedTime = null;
            current = new Date(now.getFullYear(), now.getMonth(), 1);

            renderCalendar();
            renderTimes();
            syncFields();
        });

        form?.addEventListener('submit', function(event) {
            if (!dataInput.value || !horaInput.value) {
                event.preventDefault();

                picker.classList.add('is-invalid');
                result.textContent = 'Selecione a data e o horário antes de salvar.';
                result.classList.remove('is-empty');

                picker.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        renderCalendar();
        renderTimes();
        syncFields();
    });
</script>
@endpush
@endsection