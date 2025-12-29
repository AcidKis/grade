<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Resources\LoanCollection;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reader;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoanController extends Controller
{

    public function index(Request $request)
    {
        $query = Loan::with(['book.authors', 'reader']);

        if ($request->has('active')) {
            $query->whereNull('returned_at');
        }

        if ($request->has('overdue')) {
            $query->whereNull('returned_at')
                ->where('due_date', '<', now());
        }

        $loans = $query->paginate(15);

        return new LoanCollection($loans);
    }

    public function store(StoreLoanRequest $request, LoanService $loanService): JsonResponse
    {
        $book = Book::findOrFail($request->book_id);
        $reader = Reader::findOrFail($request->reader_id);

        $loan = $loanService->loanBook($book, $reader);

        return response()->json([
            'data' => new LoanResource($loan->load(['book', 'reader'])),
            'message' => 'Книга успешно выдана'
        ], Response::HTTP_CREATED);
    }

    public function return(Loan $loan, LoanService $loanService): JsonResponse
    {
        $loan = $loanService->returnBook($loan);

        return response()->json([
            'data' => new LoanResource($loan->load(['book', 'reader'])),
            'message' => 'Книга успешно возвращена'
        ]);
    }

    public function readerLoans(Reader $reader)
    {
        $loans = $reader->loans()
            ->orderBy('loaned_at', 'desc')
            ->paginate(10);

        return new LoanCollection($loans);
    }
}