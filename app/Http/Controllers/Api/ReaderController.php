<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReaderRequest;
use App\Http\Requests\UpdateReaderRequest;
use App\Http\Resources\ReaderResource;
use App\Models\Reader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReaderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $readers = Reader::paginate(10);
        
        $readerResources = ReaderResource::collection($readers);
        
        return response()->json([
            'data' => $readerResources,
            'meta' => [
                'current_page' => $readers->currentPage(),
                'per_page' => $readers->perPage(),
                'total' => $readers->total(),
            ]
        ]);
    }

    public function store(StoreReaderRequest $request): JsonResponse
    {
        $reader = Reader::create($request->validated());
        
        return response()->json([
            'data' => new ReaderResource($reader),
            'message' => 'Читатель успешно зарегистрирован'
        ], Response::HTTP_CREATED);
    }

    public function show(Reader $reader): JsonResponse
    {
        $reader->load('loans.book');
        
        return response()->json([
            'data' => new ReaderResource($reader)
        ]);
    }

    public function update(UpdateReaderRequest $request, Reader $reader): JsonResponse
    {
        $reader->update($request->validated());
        
        return response()->json([
            'data' => new ReaderResource($reader),
            'message' => 'Данные читателя обновлены'
        ]);
    }

    public function destroy(Reader $reader): JsonResponse
    {
        if ($reader->loans()->whereNull('returned_at')->exists()) {
            return response()->json([
                'message' => 'Невозможно деактивировать читателя с активными выдачами'
            ], Response::HTTP_CONFLICT);
        }
        
        $reader->update(['is_active' => false]);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}