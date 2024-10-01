<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    const SIZE_SMALL = 1; // 1 - 5
    const SIZE_MEDIUM = 2; // 6 - 15
    const SIZE_LARGE = 3; // 15 - 25
    const SIZE_BIG = 4; // +25

    protected $fillable = [
        'name',
        'email',
        'administrator',
        'address',
        'number_of_employees',
        'phone_number',
        'email',
        'services_offered'
    ];
}
