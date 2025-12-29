<?php

namespace App\Exceptions;

use Exception;

class ReaderNotActiveException extends Exception
{
    public function __construct($message = 'Читатель не активен')
    {
        parent::__construct($message);
    }
}