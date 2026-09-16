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
}
