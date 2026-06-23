<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reagendamento;

class AulaReagendada extends Notification
{
    use Queueable;

    protected $reagendamento;

    public function __construct(Reagendamento $reagendamento)
    {
        $this->reagendamento = $reagendamento;
    }

    public function via(object $notifiable): array
    {
        return []; // sem canais — salvamos manualmente na tbl_notificacoes
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
