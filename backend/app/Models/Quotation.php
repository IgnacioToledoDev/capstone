<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @method static Builder|Quotation newModelQuery()
 * @method static Builder|Quotation newQuery()
 * @method static Builder|Quotation query()
 * @mixin Eloquent
 */
class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'approve_date_client',
        'amount_services',
        'approved_by_client',
        'is_approved_by_client'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

}
