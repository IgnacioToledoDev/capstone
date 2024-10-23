<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'date_reservation',
        'is_approved_by_mechanic',
        'has_reminder',
        'reminder_id'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(car::class, 'car_id');
    }

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(reminder::class, 'reminder_id');
    }
}
