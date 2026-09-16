<?php

namespace App\Services;

use Illuminate\Support\Str;

class ChatbotIntentService
{
    public function detect(string $message): ?string
    {
        $message = $this->normalize($message);

        if ($message === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | 1. EXCEÇÕES / FRASES QUE DEVEM IR PARA A IA
        |--------------------------------------------------------------------------
        */

        // Exemplo: "frequencia em ingles" é uma dúvida linguística,
        // não uma consulta de frequência do aluno/professor.
        if ($this->containsAny($message, [
            'frequencia em ingles',
            'como fala frequencia em ingles',
            'como se diz frequencia em ingles',
            'o que significa frequencia em ingles',
        ])) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | 2. INTENTS MUITO ESPECÍFICOS
        |--------------------------------------------------------------------------
        | Estes vêm primeiro para evitar conflitos com intents genéricos.
        |--------------------------------------------------------------------------
        */

        // Ranking de faltas
        if ($this->matchesAny($message, [
            '/\b(?:dados|informacoes|informacao) (?:do|da) aluno\b/',
            '/\b(?:mostre|mostrar|ver|consulte|consultar) (?:os )?dados (?:do|da) aluno\b/',
            '/\b(?:notas|curso|matricula|aulas?|horario|desempenho) (?:do|da) aluno\b/',
            '/\b(?:dados|informacoes|informacao) (?:do|da|de) [a-z]+(?: [a-z]+)?\b/',
            '/\bdados de [a-z]+(?: [a-z]+)+\b/',
            '/\baluno \d+\b/',
            '/\bprofessor \d+\b/',
            '/\bid\s*\d+\b/',
        ])) {
            return 'private_student_data';
        }

        if ($this->matchesAny($message, [
            '/\b(?:quem sou eu|qual e o meu nome|qual meu nome|meu nome)\b/',
        ])) {
            return 'student_profile';
        }

        if ($this->matchesAny($message, [
            '/\bqual(?: o)? aluno(?: que)? tem mais faltas?\b/',
            '/\bqual(?: o)? aluno(?: que)? mais falta\b/',
            '/\bquem tem mais faltas?\b/',
            '/\bquem mais faltou\b/',
            '/\bquem faltou mais\b/',
            '/\bqual aluno faltou mais\b/',
            '/\bqual aluno falta mais\b/',
            '/\baluno com mais faltas?\b/',
            '/\balunos com mais faltas?\b/',
            '/\balunos com menor frequencia\b/',
            '/\bqual aluno tem mais ausencias?\b/',
        ])) {
            return 'teacher_ranking';
        }

        if ($this->matchesAny($message, [
            '/\bmeu melhor aluno\b/',
            '/\bquem e meu melhor aluno\b/',
            '/\bqual aluno tem melhor desempenho\b/',
            '/\bquem tem melhor desempenho\b/',
            '/\bqual aluno esta com a melhor nota\b/',
            '/\bquem teve o melhor desempenho\b/',
            '/\b(?:qual|como|mostre|quero saber) (?:o |a )?(?:desempenho|nota|media) (?:do|da) [a-z]+\b/',
            '/\bcomo esta (?:o )?(?:desempenho|nota|media) (?:do|da) [a-z]+\b/',
            '/\bcomo (?:o )?[a-z]+ esta indo\b/',
        ])) {
            return 'teacher_performance';
        }

        if ($this->matchesAny($message, [
            '/\balunos? precisam de mais atencao\b/',
            '/\bdesempenho dos meus alunos\b/',
            '/\bdesempenho da minha turma\b/',
            '/\bresumo do desempenho\b/',
            '/\balunos? com melhor desempenho\b/',
            '/\balunos? estao com melhor desempenho\b/',
            '/\balunos? ainda nao fizeram a atividade\b/',
        ])) {
            return 'teacher_performance';
        }

        if ($this->matchesAny($message, [
            '/\bquantos alunos? tenho\b/',
            '/\bquantos alunos? eu tenho\b/',
            '/\btotal de alunos?\b/',
        ])) {
            return 'teacher_students_count';
        }

        if ($this->matchesAny($message, [
            '/\balunos? da minha proxima aula\b/',
            '/\bquem e o aluno da minha proxima aula\b/',
            '/\bquem sao os alunos da minha proxima aula\b/',
        ])) {
            return 'teacher_next_class_students';
        }

        if ($this->matchesAny($message, [
            '/\bconteudos? .*errando\b/',
            '/\bconteudos? .*duvidas?\b/',
            '/\bonde meus alunos? mais erram\b/',
        ])) {
            return 'teacher_content_performance';
        }

        if ($this->matchesAny($message, [
            '/\bquestoes? .*errad[oa]s?\b/',
            '/\bquestoes? .*acertad[oa]s?\b/',
            '/\bquestoes? .*erraram\b/',
            '/\bquestoes? .*acertaram\b/',
            '/\bquestao .*mais erros?\b/',
            '/\bquestao .*mais errad[ao]s?\b/',
            '/\bqual foi a questao que .*erraram\b/',
            '/\bmostre a questao que .*erraram\b/',
            '/\bperguntas? .*err(?:ou|aram|adas?)\b/',
            '/\bperguntas? .*acert(?:ou|aram|adas?)\b/',
            '/\bmostre .*questoes? (?:erradas?|certas?)\b/',
            '/\bquais foram os erros dos meus alunos\b/',
        ])) {
            return 'teacher_question_performance';
        }

        // Alunos do professor
        if ($this->matchesAny($message, [
            '/\balunos? (?:que )?tenho hoje\b/',
            '/\bquais alunos? tenho hoje\b/',
        ])) {
            return 'teacher_students_today';
        }

        if ($this->matchesAny($message, [
            '/\bquais alunos?\b/',
            '/\bmeus alunos?\b/',
            '/\bquais sao meus alunos?\b/',
            '/\bquais alunos? tenho\b/',
            '/\bquais alunos? tenho nas proximas aulas?\b/',
            '/\bquais alunos? estao nas minhas proximas aulas?\b/',
            '/\balunos? das proximas aulas?\b/',
            '/\balunos? da proxima aula\b/',
            '/\balunos? da aula\b/',
            '/\balunos? que tenho hoje\b/',
            '/\balunos? matriculados\b/',
        ])) {
            return 'teacher_students';
        }

        // Frequência dos alunos do professor
        if ($this->matchesAny($message, [
            '/\bfrequencia da turma\b/',
            '/\bfrequencia dos alunos?\b/',
            '/\bfrequencia do aluno\b/',
            '/\bfaltas dos alunos?\b/',
            '/\bpresenca dos alunos?\b/',
            '/\bpercentual de presenca dos alunos?\b/',
        ])) {
            return 'teacher_frequency';
        }

        if ($this->matchesAny($message, [
            '/\bconteudos? .*errando\b/',
            '/\bconteudos? .*duvidas?\b/',
            '/\bonde meus alunos? mais erram\b/',
        ])) {
            return 'teacher_content_performance';
        }

        if ($this->matchesAny($message, [
            '/\bquestoes? .*errad[oa]s?\b/',
            '/\bquestoes? .*acertad[oa]s?\b/',
            '/\bquestoes? .*erraram\b/',
            '/\bquestoes? .*acertaram\b/',
            '/\bquestao .*mais erros?\b/',
            '/\bquestao .*mais errad[ao]s?\b/',
            '/\bqual foi a questao que .*erraram\b/',
            '/\bmostre a questao que .*erraram\b/',
            '/\bperguntas? .*err(?:ou|aram|adas?)\b/',
            '/\bperguntas? .*acert(?:ou|aram|adas?)\b/',
            '/\bmostre .*questoes? (?:erradas?|certas?)\b/',
            '/\bquais foram os erros dos meus alunos\b/',
        ])) {
            return 'teacher_question_performance';
        }

        if ($this->matchesAny($message, [
            '/\bdesempenho dos meus alunos\b/',
            '/\bdesempenho da minha turma\b/',
            '/\bresumo do desempenho\b/',
            '/\balunos? precisam de mais atencao\b/',
            '/\balunos? com melhor desempenho\b/',
            '/\balunos? ainda nao fizeram a atividade\b/',
        ])) {
            return 'teacher_performance';
        }

        // Alteração/recuperação de senha
        if ($this->matchesAny($message, [
            '/\balterar senha\b/',
            '/\bmudar senha\b/',
            '/\btrocar senha\b/',
            '/\bquero alterar minha senha\b/',
            '/\bquero mudar minha senha\b/',
            '/\bquero trocar minha senha\b/',
            '/\bpreciso alterar minha senha\b/',
            '/\bpreciso mudar minha senha\b/',
            '/\bpreciso trocar minha senha\b/',
            '/\besqueci minha senha\b/',
            '/\bnao lembro minha senha\b/',
            '/\bnao lembro a senha\b/',
        ])) {
            return 'password';
        }

        /*
        |--------------------------------------------------------------------------
        | 3. INTENTS DE ALUNO / PROFESSOR
        |--------------------------------------------------------------------------
        */

        // Frequência própria
        if ($this->matchesAny($message, [
            '/\bminha frequencia\b/',
            '/\bminhas faltas?\b/',
            '/\bminha presenca\b/',
            '/\bmeu percentual de presenca\b/',
            '/\bquantas faltas? eu tenho\b/',
            '/\bquantas aulas? perdi\b/',
            '/\bquantas vezes estive presente\b/',
        ])) {
            return 'frequency';
        }

        // Notas
        if ($this->matchesAny($message, [
            '/\bnotas dos meus alunos?\b/',
            '/\bnotas da turma\b/',
            '/\bmedia da turma\b/',
            '/\bmaior nota\b/',
            '/\bmenor nota\b/',
            '/\bnotas baixas\b/',
            '/\bminhas notas?\b/',
            '/\bminha nota\b/',
            '/\bminha media\b/',
        ])) {
            return 'notes';
        }

        // Desempenho
        if ($this->matchesAny($message, [
            '/\bmeu desempenho\b/',
            '/\bcomo estou indo\b/',
            '/\bnotas e frequencia\b/',
            '/\bdesempenho academico\b/',
        ])) {
            return 'performance';
        }

        /*
        |--------------------------------------------------------------------------
        | 4. AULAS
        |--------------------------------------------------------------------------
        */

        // Aulas específicas do usuário
        if ($this->matchesAny($message, [
            '/\bquantas? aulas? (tenho|eu tenho)\b/',
            '/\bqual e o total de aulas?\b/',
            '/\btenho quantas aulas?\b/',
        ])) {
            return 'teacher_classes_count';
        }

        if ($this->matchesAny($message, [
            '/\bqual e a data de hoje\b/',
            '/\bque dia e hoje\b/',
            '/\bdata atual\b/',
        ])) {
            return 'current_date';
        }

        if ($this->matchesAny($message, [
            '/\bminhas aulas?\b/',
            '/\bminhas proximas aulas?\b/',
            '/\bquais sao as minhas proximas aulas?\b/',
            '/\bque aulas? eu tenho\b/',
            '/\bminha aula\b/',
            '/\bconsultar aulas?\b/',
            '/\bver aulas?\b/',
            '/\bonde vejo aulas?\b/',
            '/\bmostrar? aulas?\b/',
            '/\bmostra aulas?\b/',
            '/\bproxima aula\b/',
            '/\baulas? hoje\b/',
            '/\baulas? amanha\b/',
            '/\baulas? depois de amanha\b/',
            '/\baulas? .* hoje\b/',
            '/\baulas? .* amanha\b/',
            '/\baulas esta semana\b/',
            '/\baulas? .* esta semana\b/',
            '/\baulas? .* dessa semana\b/',
            '/\baulas? .* essa semana\b/',
            '/\baulas? ontem\b/',
            '/\baulas? proxima semana\b/',
            '/\bqual e minha proxima aula\b/',
            '/\bqual foi minha ultima aula\b/',
            '/\bque horas comeca minha proxima aula\b/',
            '/\btenho alguma aula depois das? \d{1,2}h\b/',
            '/\bquais sao todas as minhas aulas?\b/',
        ])) {
            return 'my_classes';
        }

        // Horários / agenda
        if ($this->matchesAny($message, [
            '/\bmeus horarios?\b/',
            '/\bminhas horarios?\b/',
            '/\bhorarios?\b/',
            '/\bminha agenda\b/',
            '/\bminha agenda de aulas?\b/',
            '/\bquando tenho aula\b/',
            '/\bquando sera minha aula\b/',
            '/\bque horas tenho aula\b/',
            '/\bqual meu horario\b/',
            '/\bqual o meu horario\b/',
        ])) {
            return 'schedule';
        }

        // Histórico de aulas
        if ($this->matchesAny($message, [
            '/\baulas que ja fiz\b/',
            '/\baulas realizadas\b/',
            '/\bhistorico de aulas?\b/',
            '/\baulas anteriores\b/',
            '/\baulas passadas\b/',
        ])) {
            return 'past_classes';
        }

        /*
        |--------------------------------------------------------------------------
        | 5. ATIVIDADES
        |--------------------------------------------------------------------------
        */

        // Pendentes deve vir antes de "activities"
        if ($this->matchesAny($message, [
            '/\batividades pendentes\b/',
            '/\batividades que ainda preciso fazer\b/',
            '/\batividades ainda preciso fazer\b/',
            '/\batividades disponiveis\b/',
            '/\batividades nao feitas\b/',
        ])) {
            return 'activities_pending';
        }

        // Concluídas
        if ($this->matchesAny($message, [
            '/\batividades que ja respondi\b/',
            '/\batividades respondidas\b/',
            '/\batividades concluidas\b/',
            '/\batividades que fiz\b/',
            '/\batividades realizadas\b/',
        ])) {
            return 'activities_completed';
        }

        // Atividades genéricas
        if ($this->matchesAny($message, [
            '/\bminhas atividades\b/',
            '/\bquais atividades tenho\b/',
            '/\batividades tenho\b/',
            '/\bacesso atividades\b/',
            '/\bver atividades\b/',
        ])) {
            return 'activities';
        }

        /*
        |--------------------------------------------------------------------------
        | 6. MATERIAIS
        |--------------------------------------------------------------------------
        */

        if ($this->matchesAny($message, [
            '/\bmeus materiais\b/',
            '/\bonde encontro materiais\b/',
            '/\bacesso materiais\b/',
            '/\bmaterial de estudo\b/',
            '/\bmateriais de estudo\b/',
        ])) {
            return 'materials';
        }

        /*
        |--------------------------------------------------------------------------
        | 7. CURSO / MATRÍCULA
        |--------------------------------------------------------------------------
        */

        if ($this->matchesAny($message, [
            '/\bmeu curso\b/',
            '/\bqual meu curso\b/',
            '/\bqual curso estou matriculad[oa]\b/',
            '/\bem qual curso estou\b/',
            '/\bqual curso eu faco\b/',
            '/\bquais cursos estou fazendo\b/',
            '/\bquais cursos estou matriculad[oa]\b/',
            '/\bminha matricula e de qual curso\b/',
            '/\bmostre meu curso\b/',
            '/\bqual curso estou fazendo\b/',
            '/\bcurso que estou fazendo\b/',
        ])) {
            return 'course';
        }

        if ($this->matchesAny($message, [
            '/\bminha matricula\b/',
            '/\bstatus da minha matricula\b/',
            '/\bminha matricula esta (?:ativa|cancelada|regular)\b/',
            '/\bcomo esta minha matricula\b/',
            '/\bqual e o status da minha matricula\b/',
            '/\bminhas matriculas\b/',
            '/\bsituacao da matricula\b/',
            '/\bsituacao da minha matricula\b/',
            '/\bquando comecei\b/',
            '/\bquando comecei meu curso\b/',
        ])) {
            return 'enrollment';
        }

        /*
        |--------------------------------------------------------------------------
        | 8. PROFESSOR / CONTATO
        |--------------------------------------------------------------------------
        */

        if ($this->matchesAny($message, [
            '/\bfalar com professor\b/',
            '/\bfalar com a professora\b/',
            '/\bcontato professor\b/',
            '/\bcontato com professor\b/',
            '/\bchamar professor\b/',
            '/\bquero falar com professor\b/',
        ])) {
            return 'teacher';
        }

        /*
        |--------------------------------------------------------------------------
        | 9. PREÇOS
        |--------------------------------------------------------------------------
        */

        if ($this->matchesAny($message, [
            '/\bpreco\b/',
            '/\bprecos\b/',
            '/\bvalor\b/',
            '/\bvalores\b/',
            '/\bquanto custa\b/',
            '/\bquanto sai\b/',
            '/\bquantos reais\b/',
            '/\bqual o valor\b/',
            '/\bqual valor\b/',
        ])) {
            return 'price';
        }

        /*
        |--------------------------------------------------------------------------
        | 10. SAUDAÇÕES
        |--------------------------------------------------------------------------
        */

        if ($this->matchesAny($message, [
            '/^oi\b/',
            '/^ola\b/',
            '/^bom dia\b/',
            '/^boa tarde\b/',
            '/^boa noite\b/',
            '/\btudo bem\b/',
        ])) {
            return 'greeting';
        }

        /*
        |--------------------------------------------------------------------------
        | Nenhum intent local encontrado
        |--------------------------------------------------------------------------
        */

        return null;
    }

    /**
     * Normaliza a mensagem antes da análise.
     */
    private function normalize(string $message): string
    {
        return Str::of($message)
            ->lower()
            ->ascii()
            ->replace('desenpenho', 'desempenho')
            ->replaceMatches('/[^a-z0-9\s]/', ' ')
            ->squish()
            ->value();
    }

    /**
     * Verifica se alguma expressão regular corresponde à mensagem.
     */
    private function matchesAny(string $message, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $message) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se alguma expressão literal está presente.
     */
    private function containsAny(string $message, array $values): bool
    {
        foreach ($values as $value) {
            if (Str::contains($message, $value)) {
                return true;
            }
        }

        return false;
    }
}
