<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reader;
use App\Events\BookLoaned;
use App\Events\BookReturned;
use App\Exceptions\BookNotAvailableException;
use App\Exceptions\ReaderNotActiveException;
use App\Exceptions\ReaderLimitExceededException;
use App\Exceptions\DuplicateActiveLoanException;

class LoanService
{
    public function loanBook(Book $book, Reader $reader): Loan
    {
        if ($book->available_copies <= 0) {
            throw new BookNotAvailableException();
        }

        if (!$reader->is_active) {
            throw new ReaderNotActiveException();
        }

        $activeLoansCount = Loan::where('reader_id', $reader->id)
            ->whereNull('returned_at')
            ->count();
        
        if ($activeLoansCount >= 5) {
            throw new ReaderLimitExceededException();
        }

        if (Loan::where('reader_id', $reader->id)
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists()) {
            throw new DuplicateActiveLoanException();
        }

        $loan = Loan::create([
            'book_id' => $book->id,
            'reader_id' => $reader->id,
            'loaned_at' => now(),
            'due_date' => now()->addDays(14),
        ]);

        $book->decrement('available_copies');

        event(new BookLoaned($loan));

        return $loan->fresh(['book', 'reader']);
    }

    public function returnBook(Loan $loan): Loan
    {
        if ($loan->returned_at) {
            return $loan;
        }

        $loan->update([
            'returned_at' => now(),
        ]);

        $loan->book->increment('available_copies');

        event(new BookReturned($loan));

        return $loan->fresh(['book', 'reader']);
    }
}