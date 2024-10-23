<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $brand_id
 * @property string|null $patent
 * @property string $model
 * @property int $year
 * @property int $user_id
 * @property int $owner_id
 * @property int $mechanic_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CarBrand $carBrands
 * @property-read \App\Models\User $user
 * @method static Builder|Car newModelQuery()
 * @method static Builder|Car newQuery()
 * @method static Builder|Car query()
 * @method static Builder|Car whereBrandId($value)
 * @method static Builder|Car whereCreatedAt($value)
 * @method static Builder|Car whereId($value)
 * @method static Builder|Car wherePatent($value)
 * @method static Builder|Car whereUpdatedAt($value)
 * @method static Builder|Car whereUserId($value)
 * @method static Builder|Car whereYear($value)
 * @property int|null $model_id
 * @method static Builder|Car whereMechanicId($value)
 * @method static Builder|Car whereModelId($value)
 * @method static Builder|Car whereOwnerId($value)
 * @mixin \Eloquent
 */
class Car extends Model
{
    use HasFactory;

    const MIN_YEAR = 1970;
    protected $fillable = [
        'patent',
        'brand_id',
        'model_id',
        'year',
        'owner_id',
        'mechanic_id',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function carBrands(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    public function carModels(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }
}
