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
 * @property int $id
 * @property int|null $brand_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|CarModel whereBrandId($value)
 * @method static Builder|CarModel whereCreatedAt($value)
 * @method static Builder|CarModel whereId($value)
 * @method static Builder|CarModel whereName($value)
 * @method static Builder|CarModel whereUpdatedAt($value)
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
