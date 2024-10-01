<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeService extends Model
{
    use HasFactory;

    const INSPECTION_AND_DIAGNOSTIC = 1;
    const PREVENTIVE_MAINTENANCE = 2;
    const CORRECTIVE_MAINTENANCE = 3;

    protected $fillable = [
        'name',
        'description',
    ];
}
