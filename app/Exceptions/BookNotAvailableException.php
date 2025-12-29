<?php

namespace App\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{
    public function __construct($message = 'Книга не доступна для выдачи')
    {
        parent::__construct($message);
    }
}