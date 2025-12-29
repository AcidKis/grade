<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    public function index(Request $request): AuthorCollection
    {
        $authors = Author::paginate(5);
        
        return new AuthorCollection($authors);
    }

    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());
        
        return response()->json([
            'data' => new AuthorResource($author),
            'message' => 'Автор успешно создан'
        ], Response::HTTP_CREATED);
    }

    public function show(Author $author): JsonResponse
    {
        $author->load('books');
        
        return response()->json([
            'data' => new AuthorResource($author)
        ]);
    }

    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());
        
        return response()->json([
            'data' => new AuthorResource($author),
            'message' => 'Данные автора обновлены'
        ]);
    }

    public function destroy(Author $author): JsonResponse
    {
        if ($author->books()->count() > 0) {
            return response()->json([
                'message' => 'Невозможно удалить автора, у которого есть книги'
            ], Response::HTTP_CONFLICT);
        }
        
        $author->delete();
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}