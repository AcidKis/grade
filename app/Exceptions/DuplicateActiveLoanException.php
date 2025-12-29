<?php

namespace App\Exceptions;

use Exception;

class DuplicateActiveLoanException extends Exception
{
    public function __construct($message = 'У читателя уже есть активная выдача этой книги')
    {
        parent::__construct($message);
    }
}