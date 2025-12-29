<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'isbn',
        'description',
        'published_year',
        'total_copies',
        'available_copies',
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
    
    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }
}
