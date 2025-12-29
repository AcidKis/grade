<?php

namespace App\Listeners;

use App\Events\BookLoaned;
use App\Events\BookReturned;
use App\Models\ActivityLog;

class LogLoanActivity
{
    public function handleBookLoan(BookLoaned $event): void
    {
        ActivityLog::create([
            'action' => 'loan',
            'user_id' => auth()->id() ?? null,
            'loan_id' => $event->loan->id,
            'book_id' => $event->loan->book_id,
            'reader_id' => $event->loan->reader_id,
            'details' => json_encode([
                'book_title' => $event->loan->book->title,
                'reader_name' => $event->loan->reader->name,
                'due_date' => $event->loan->due_date,
            ]),
        ]);
    }

    public function handleBookReturned(BookReturned $event): void
    {
        ActivityLog::create([
            'action' => 'return',
            'user_id' => auth()->id() ?? null,
            'loan_id' => $event->loan->id,
            'book_id' => $event->loan->book_id,
            'reader_id' => $event->loan->reader_id,
            'details' => json_encode([
                'book_title' => $event->loan->book->title,
                'reader_name' => $event->loan->reader->name,
                'returned_at' => $event->loan->returned_at,
            ]),
        ]);
    }
}