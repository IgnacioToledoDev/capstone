<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

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
