<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\LoanOverdueNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckOverdue extends Command
{
    protected $signature = 'loans:check-overdue 
                            {--notify : Отправить уведомления читателям}';

    protected $description = 'Проверка просроченных выдач книг';

    public function handle(): void
    {
        $query = Loan::with(['reader', 'book'])
            ->whereNull('returned_at')
            ->where('due_date', '<', now());

        $overdueLoans = $query->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Просроченных выдач не найдено.');
            return;
        }

        $this->displayOverdueLoans($overdueLoans);

        if ($this->option('notify')) {
            $this->sendNotifications($overdueLoans);
        }
    }

    private function displayOverdueLoans($loans): void
    {
        $headers = ['ID', 'Книга', 'Читатель', 'Дата выдачи', 'Дата возврата', 'Дней просрочки'];
        $rows = [];

        foreach ($loans as $loan) {
            $daysOverdue = $loan->due_date->diffInDays(now(), false);
            $rows[] = [
                $loan->id,
                $loan->book->title,
                $loan->reader->name,
                $loan->loaned_at->format('d.m.Y'),
                $loan->due_date->format('d.m.Y'),
                (int)$daysOverdue,
            ];
        }

        $this->table($headers, $rows);
    }

    private function sendNotifications($loans): void
    {
        foreach ($loans as $loan) {
            $loan->reader->notify(new LoanOverdueNotification($loan));
        }
    }
}