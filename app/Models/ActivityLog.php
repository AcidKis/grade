<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'action',
        'user_id',
        'loan_id',
        'book_id',
        'reader_id',
        'details',
    ];

    protected $casts = [
        'details' => 'json',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}