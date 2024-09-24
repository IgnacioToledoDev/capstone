<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    const MIN_YEAR = 1970;
    protected $fillable = [
        'brand_id',
        'model',
        'year',
        'user_id',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carBrands(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }
}
