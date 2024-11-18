<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $status
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|StatusCar newModelQuery()
 * @method static Builder|StatusCar newQuery()
 * @method static Builder|StatusCar query()
 * @method static Builder|StatusCar whereCreatedAt($value)
 * @method static Builder|StatusCar whereDescription($value)
 * @method static Builder|StatusCar whereId($value)
 * @method static Builder|StatusCar whereStatus($value)
 * @method static Builder|StatusCar whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection<int, Maintenance> $Maintenance
 * @property-read int|null $maintenance_count
 * @mixin \Eloquent
 */
class StatusCar extends Model
{
    use HasFactory;

    const STATUS_INACTIVE = 1;
    const STATUS_STARTED= 2;
    const STATUS_PROGRESS = 3;
    const STATUS_FINISHED = 4;
    const STATUS_READY = 5;

    protected $fillable = [
        'id',
        'status',
        'description'
    ];

    public function Maintenance(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'status_id');
    }
}
