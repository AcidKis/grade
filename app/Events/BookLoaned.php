<?php

namespace App\Events;

use App\Models\Loan;

class BookLoaned
{
    public $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }
}