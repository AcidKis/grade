<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'reader_id' => $this->reader_id,
            'loaned_at' => $this->loaned_at->format('Y-m-d H:i:s'),
            'due_date' => $this->due_date->format('Y-m-d H:i:s'),
            'returned_at' => $this->returned_at?->format('Y-m-d H:i:s'),
            'is_active' => is_null($this->returned_at),
            'is_overdue' => is_null($this->returned_at) && $this->due_date < now(),
            'book' => new BookResource($this->whenLoaded('book')),
            'reader' => new ReaderResource($this->whenLoaded('reader')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}