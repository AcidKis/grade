<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReaderController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\StatisticsController;

use Illuminate\Contracts\Debug\ExceptionHandler;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('books', BookController::class);
    Route::apiResource('readers', ReaderController::class);
    
    Route::prefix('loans')->group(function () {
        Route::get('/', [LoanController::class, 'index']);
        Route::post('/', [LoanController::class, 'store']);
        Route::post('/{loan}/return', [LoanController::class, 'return']);
    });
    
    Route::get('/readers/{reader}/loans', [LoanController::class, 'readerLoans']);
    
    Route::prefix('statistics')->group(function () {
        Route::get('/popular-books', [StatisticsController::class, 'popularBooks']);
        Route::get('/active-readers', [StatisticsController::class, 'activeReaders']);
        Route::get('/overdue', [StatisticsController::class, 'overdue']);
    });
});