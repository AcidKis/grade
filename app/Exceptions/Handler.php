<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\BookNotAvailableException;
use App\Exceptions\ReaderNotActiveException;
use App\Exceptions\ReaderLimitExceededException;
use App\Exceptions\DuplicateActiveLoanException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (BookNotAvailableException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        });
        
        $this->renderable(function (ReaderNotActiveException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        });
        
        $this->renderable(function (ReaderLimitExceededException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        });
        
        $this->renderable(function (DuplicateActiveLoanException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        });
    }
}
