<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LoanOverdueNotification extends Notification
{
    public $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $daysOverdue = now()->diffInDays($this->loan->due_date);

        return (new MailMessage)
            ->subject('Напоминание о просроченной книге')
            ->line('У вас есть просроченная выдача книги:')
            ->line(sprintf('**Книга:** %s', $this->loan->book->title))
            ->line(sprintf('**Дата выдачи:** %s', $this->loan->loaned_at->format('d.m.Y')))
            ->line(sprintf('**Дата возврата:** %s', $this->loan->due_date->format('d.m.Y')))
            ->line(sprintf('**Просрочено дней:** %d', $daysOverdue));
    }
}