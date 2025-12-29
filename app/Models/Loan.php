<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'book_id',
        'reader_id',
        'loaned_at',
        'due_date',
        'returned_at',
    ];
    protected $casts = [
        'loaned_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function reader(): BelongsTo
    {
        return $this->belongsTo(Reader::class);
    }
}
