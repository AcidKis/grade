<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Book::query();
        
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        
        if ($request->has('isbn')) {
            $query->where('isbn', 'like', '%' . $request->isbn . '%');
        }
       
        if ($request->has('published_year')) {
            $query->where('published_year', $request->published_year);
        }
        
        if ($request->has('available')) {
            if ($request->boolean('available')) {
                $query->where('available_copies', '>', 0);
            } else {
                $query->where('available_copies', '<=', 0);
            }
        }
        
        if ($request->has('author_id')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('authors.id', $request->author_id);
            });
        }

        $sortBy = $request->get('sort_by', 'title');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $books = $query->with('authors')->paginate(10);
        
        $bookResources = BookResource::collection($books);
        
        return response()->json([
            'data' => $bookResources,
            'meta' => [
                'current_page' => $books->currentPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ]
        ]);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->except('author_ids'));
        
        $book->authors()->sync($request->author_ids);
        
        $book->load('authors');
        
        return response()->json([
            'data' => new BookResource($book),
            'message' => 'Книга успешно создана'
        ], Response::HTTP_CREATED);
    }

    public function show(Book $book): JsonResponse
    {
        $book->load('authors');
        
        return response()->json([
            'data' => new BookResource($book)
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->except('author_ids'));
        
        if ($request->has('author_ids')) {
            $book->authors()->sync($request->author_ids);
        }
        
        $book->load('authors');
        
        return response()->json([
            'data' => new BookResource($book),
            'message' => 'Данные книги обновлены'
        ]);
    }

    public function destroy(Book $book): JsonResponse
    {
        if ($book->loans()->whereNull('returned_at')->exists()) {
            return response()->json([
                'message' => 'Невозможно удалить книгу, которая сейчас выдана'
            ], Response::HTTP_CONFLICT);
        }
        
        $book->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}