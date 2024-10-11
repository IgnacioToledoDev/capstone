<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property string $status
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar query()
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatusCar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StatusCar extends Model
{
    use HasFactory;

    const STATUS_INACTIVE = 1;
    const STATUS_STARTED= 2;
    const STATUS_PROGRESS = 3;
    const STATUS_FINISHED = 4;

    protected $fillable = [
        'status',
        'description'
    ];

    public function Maintenance(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'status_id');
    }
}
