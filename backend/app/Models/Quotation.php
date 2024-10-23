<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'approve_date_client',
        'amount_services',
        'status',
        'is_approved_by_client'
    ];
}
