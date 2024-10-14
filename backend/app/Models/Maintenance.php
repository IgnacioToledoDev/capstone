<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status_id
 * @property int $service_id
 * @property int|null $actual_mileage
 * @property string|null $recommendation_action
 * @property int $pricing
 * @property int $car_id
 * @property int $mechanic_id
 * @property $start_maintenance
 * @property $end_maintenance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Maintenance newModelQuery()
 * @method static Builder|Maintenance newQuery()
 * @method static Builder|Maintenance query()
 * @method static Builder|Maintenance whereActualMileage($value)
 * @method static Builder|Maintenance whereCarId($value)
 * @method static Builder|Maintenance whereCreatedAt($value)
 * @method static Builder|Maintenance whereDescription($value)
 * @method static Builder|Maintenance whereId($value)
 * @method static Builder|Maintenance whereMechanicId($value)
 * @method static Builder|Maintenance whereName($value)
 * @method static Builder|Maintenance wherePricing($value)
 * @method static Builder|Maintenance whereRecommendationAction($value)
 * @method static Builder|Maintenance whereServiceId($value)
 * @method static Builder|Maintenance whereStatusId($value)
 * @method static Builder|Maintenance whereUpdatedAt($value)
 * @method static Builder|Maintenance whereStartMaintenance($value)
 * @method static Builder|Maintenance whereEndMaintenance($value)
 */
class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status_id',
        'service_id',
        'actual_mileage',
        'recommendation_action',
        'pricing',
        'car_id',
        'mechanic_id',
        'start_maintenance',
        'end_maintenance',
    ];

    protected $attributes = [
        'status_id' => 1
    ];

    public function statusCar(): BelongsTo
    {
        return $this->belongsTo(StatusCar::class, 'status_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}
