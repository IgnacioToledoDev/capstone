<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
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
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereActualMileage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereCarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereMechanicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance wherePricing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereRecommendationAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereUpdatedAt($value)
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
