<?php

namespace App\Events;

use App\Models\Loan;

class BookReturned
{
    public $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }
}