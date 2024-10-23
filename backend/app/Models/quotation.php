<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'approve_date_client',
        'amount_services',
        'status',
        'is_approved_by_client'
    ];
}
