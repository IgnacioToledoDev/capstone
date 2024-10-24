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
 * @property-read Car|null $car
 * @property-read User|null $owner
 * @method static Builder|OwnerHistorical newModelQuery()
 * @method static Builder|OwnerHistorical newQuery()
 * @method static Builder|OwnerHistorical query()
 * @mixin Eloquent
 */
class OwnerHistorical extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date_owner',
        'end_date_owner',
        'owner_id',
        'car_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
