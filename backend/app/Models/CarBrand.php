<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $brand_id)
 */
class CarBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];
}
