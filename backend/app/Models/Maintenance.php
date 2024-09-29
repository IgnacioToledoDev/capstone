<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
