<?php

namespace Tests\Unit\Chatbot;

use App\Services\ChatbotIntentService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ChatbotIntentTest extends TestCase
{
    #[DataProvider('privateStudentMessages')]
    public function test_student_data_variants_are_classified(string $message): void
    {
        $this->assertSame(
            'private_student_data',
            app(ChatbotIntentService::class)->detect($message)
        );
    }

    public static function privateStudentMessages(): array
    {
        return [
            ['me dê informações do aluno Caio'],
            ['informações do aluno Caio'],
            ['dados do Caio'],
            ['informações de Caio Ferreira'],
        ];
    }
    #[DataProvider('teacherPerformanceMessages')]
    public function test_teacher_performance_variants_are_classified(string $message): void
    {
        $this->assertSame(
            'teacher_performance',
            app(ChatbotIntentService::class)->detect($message)
        );
    }

    public static function teacherPerformanceMessages(): array
    {
        return [
            ['Qual é a média do aluno Caio Ferreira?'],
            ['qual a média do Caio'],
            ['qual o desempenho do Caio'],
            ['como está o desempenho do Caio'],
        ];
    }

    #[DataProvider('privateStudentNaturalMessages')]
    public function test_private_student_natural_variants_are_classified(string $message): void
    {
        $this->assertSame(
            'private_student_data',
            app(ChatbotIntentService::class)->detect($message)
        );
    }

    public static function privateStudentNaturalMessages(): array
    {
        return [
            ['Me dê informações sobre o aluno Caio Ferreira.'],
            ['me dê informações da aluna Caio'],
        ];
    }

    #[DataProvider('teacherStudentClassesMessages')]
    public function test_teacher_student_class_variants_are_classified(string $message): void
    {
        $this->assertSame('teacher_student_classes', app(ChatbotIntentService::class)->detect($message));
    }

    public static function teacherStudentClassesMessages(): array
    {
        return [
            ['Quais são as aulas do Caio Ferreira?'],
            ['Quais aulas o Caio Ferreira tem?'],
            ['Quais são as próximas aulas do Caio Ferreira?'],
            ['Me mostre as aulas do Caio Ferreira.'],
            ['Quando são as aulas do Caio Ferreira?'],
        ];
    }

    #[DataProvider('teacherStudentListMessages')]
    public function test_teacher_student_list_variants_are_classified(string $message): void
    {
        $this->assertSame('teacher_students', app(ChatbotIntentService::class)->detect($message));
    }

    public static function teacherStudentListMessages(): array
    {
        return [
            ['Quais são os alunos ativos?'],
            ['Quais alunos estão matriculados?'],
        ];
    }

    #[DataProvider('teacherActivityReportMessages')]
    public function test_teacher_activity_report_variants_are_classified(string $message): void
    {
        $this->assertSame('teacher_activity_report', app(ChatbotIntentService::class)->detect($message));
    }

    public static function teacherActivityReportMessages(): array
    {
        return [
            ['Analise o desempenho dos alunos nas ultimas atividades'],
            ['Faca um relatorio das atividades recentes'],
            ['Como foi o desempenho da turma nas atividades?'],
            ['Quais foram os erros dos alunos nas ultimas atividades?'],
        ];
    }

    #[DataProvider('studentActivityMessages')]
    public function test_student_activity_variants_are_classified(string $message, string $intent): void
    {
        $this->assertSame($intent, app(ChatbotIntentService::class)->detect($message));
    }

    public static function studentActivityMessages(): array
    {
        return [
            ['Quantas atividades eu respondi?', 'activities_completed'],
            ['Quais questoes eu errei?', 'student_question_performance'],
            ['Analiza meu desempenho nas atividades', 'student_activity_performance'],
            ['Quantas aulas ja fiz?', 'student_classes_count'],
            ['Quantas aulas participei?', 'student_classes_count'],
            ['Quais aulas eu ja tive?', 'past_classes'],
            ['Quais foram meus erros na Atividade de Ingles?', 'student_activity_correction'],
            ['Mostre a correção da Atividade de Ingles', 'student_activity_correction'],
            ['Qual foi minha nota na Atividade de Ingles?', 'student_activity_grade'],
        ];
    }
}