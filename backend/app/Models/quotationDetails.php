<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class quotationDetails extends Model
{
    use HasFactory;

    protected $fillable = [
      'quotation_id',
      'total_services',
      'service_id'
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(quotation::class, 'quotation_id');
    }

}
