<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_sending',
        'contact_type_id'
    ];

    public function contactType(): BelongsTo
    {
        return $this->belongsTo(contactType::class, 'contact_type_id');
    }

}
