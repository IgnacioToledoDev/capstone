<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
