<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reader extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'membership_date',
        'is_active',
    ];

    protected $casts = [
        'membership_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
