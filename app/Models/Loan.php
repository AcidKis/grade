<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            if (Loan::where('reader_id', $loan->reader_id)
                ->where('book_id', $loan->book_id)
                ->whereNull('returned_at')
                ->exists()) {
                throw new \App\Exceptions\DuplicateActiveLoanException();
            }
        });
    }

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
