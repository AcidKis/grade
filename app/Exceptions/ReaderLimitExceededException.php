<?php

namespace App\Exceptions;

use Exception;

class ReaderLimitExceededException extends Exception
{
    public function __construct($message = 'Превышен лимит активных выдач (максимум 5)')
    {
        parent::__construct($message);
    }
}