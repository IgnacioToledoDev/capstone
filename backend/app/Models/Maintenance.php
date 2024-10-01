<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 */
class Maintenance extends Model
{
    use HasFactory;

    /**
     * @var mixed|string
     */
    public mixed $name;
    /**
     * @var mixed|null
     */
    public mixed $description;
    /**
     * @var int|mixed
     */
    public mixed $status_id;
    public mixed $service_id;
    public mixed $actual_mileage;
    /**
     * @var int|mixed
     */
    public mixed $pricing;
    public mixed $car_id;
    public mixed $mechanic_id;
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
    ];
}
