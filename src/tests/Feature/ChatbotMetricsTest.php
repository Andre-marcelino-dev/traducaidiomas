<?php

namespace Tests\Feature\Chatbot;

use App\Services\ChatbotResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatbotMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_frequency_does_not_expose_teacher_data_to_visitor(): void
    {
        $response = app(ChatbotResponseService::class)
            ->respond('frequência dos alunos', 'visitante');

        $this->assertSame('teacher_frequency', $response['intent']);
        $this->assertStringContainsString('professores autenticados', $response['message']);
    }

    public function test_frequency_does_not_expose_teacher_data_to_student(): void
    {
        $response = app(ChatbotResponseService::class)
            ->respond('frequência dos alunos', 'aluno');

        $this->assertSame('teacher_frequency', $response['intent']);
        $this->assertStringContainsString('professores autenticados', $response['message']);
    }

    public function test_student_performance_is_restricted_to_authenticated_student(): void
    {
        $response = app(ChatbotResponseService::class)
            ->respond('qual meu desempenho', 'visitante');

        $this->assertSame('performance', $response['intent']);
        $this->assertStringContainsString('alunos autenticados', $response['message']);
    }

    public function test_teacher_performance_is_restricted_to_authenticated_teacher(): void
    {
        $response = app(ChatbotResponseService::class)
            ->respond('qual o desempenho dos meus alunos', 'visitante');

        $this->assertSame('teacher_performance', $response['intent']);
        $this->assertStringContainsString('professores autenticados', $response['message']);
    }
}
