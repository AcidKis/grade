<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reader;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function popularBooks(Request $request)
    {
        $books = Book::withCount('loans')
        ->orderBy('loans_count', 'desc')
        ->limit(10)
        ->get();

        return response()->json($books);
    }

    public function activeReaders(Request $request)
    {
        $readers = Reader::withCount('loans')
        ->orderBy('loans_count', 'desc')
        ->limit(10)
        ->get();

        return response()->json($readers);
    }

    public function overdue()
    {
        $overdue = Loan::whereNull('returned_at')
            ->where('due_date', '<', now())
            ->with(['book:id,title', 'reader:id,name'])
            ->orderBy('due_date')
            ->get();

        return response()->json($overdue);
    }
}