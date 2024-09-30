<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
