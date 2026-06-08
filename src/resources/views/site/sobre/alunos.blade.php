<section aria-label="Lista de alunos">
    <div class="alunos">Alunos</div>

    <div class="card-container">

        @foreach($alunos as $aluno)
        <div class="card">
            <div class="card-header">
                <div class="school-logo" title="Instituição">🌍</div>

                <img
             src="{{ asset('traducaidiomas/alunos/' . $aluno->foto_aluno) }}"
                    alt="Foto de {{ $aluno->nome_aluno }}"
                    class="photo">

                <div class="student-info">
                    <h2>{{ $aluno->nome_aluno }}</h2>
                    <div class="student-id">Matricula: ALUNO-{{ ($aluno->id_matricula && $aluno->data_matricula) ? \Carbon\Carbon::parse($aluno->data_matricula)->format('Y') . str_pad($aluno->id_matricula, 4, '0', STR_PAD_LEFT) : 'Sem Matrícula' }}</div>
                </div>
            </div>

            <div class="card-body">
                <div class="course-info">
                    <div class="info-group">
                        <span class="label">Curso</span>
                        <span class="value">{{ $aluno->curso_aluno }}</span>
                    </div>
                    <div class="info-group">
                        <span class="label">Nível</span>
                        <span class="value">{{ $aluno->nivel_aluno }}</span>
                    </div>
                    <div class="info-group">
                        <span class="label">Status</span>
                        <span class="value">{{ $aluno->status_aluno ?? 'EM CURSO' }}</span>
                    </div>
                </div>

         

                <div class="validity">
                    Email: {{ $aluno->email_aluno }}
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>