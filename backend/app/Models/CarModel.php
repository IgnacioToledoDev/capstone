<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property-read CarBrand|null $brand
 * @method static Builder|CarModel newModelQuery()
 * @method static Builder|CarModel newQuery()
 * @method static Builder|CarModel query()
 * @mixi Eloquent
 * @mixin Eloquent
 */
class CarModel extends Model
{
    use HasFactory;

    protected $table = 'car_model';

    protected $fillable = [
        'name',
        'brand_id'
    ];

    public function brand(): belongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }
}
