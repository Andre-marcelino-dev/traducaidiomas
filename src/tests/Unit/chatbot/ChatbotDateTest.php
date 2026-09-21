<?php

namespace Tests\Unit\Chatbot;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ChatbotDateTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_relative_dates_are_deterministic(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 9, 16, 10, 0, 0));

        $this->assertSame('2026-09-16', now()->toDateString());
        $this->assertSame('2026-09-17', now()->addDay()->toDateString());
        $this->assertSame('2026-09-18', now()->addDays(2)->toDateString());
        $this->assertSame('quarta-feira', now()->locale('pt_BR')->dayName);
    }
}