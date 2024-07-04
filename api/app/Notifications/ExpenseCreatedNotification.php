<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseCreatedNotification extends Notification
{
    use Queueable;

    public $expense;

    public function __construct($expense)
    {
        $this->expense = $expense;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Despesa Cadastrada')
            ->line('Sua despesa foi cadastrada com sucesso.')
            ->line('Obrigado por usar nossa aplicação!')
        ;
    }
}
