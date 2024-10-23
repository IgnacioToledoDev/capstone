<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int|null $quotation_id
 * @property int $total_services
 * @property int|null $service_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Quotation|null $quotation
 * @method static Builder|QuotationDetails newModelQuery()
 * @method static Builder|QuotationDetails newQuery()
 * @method static Builder|QuotationDetails query()
 * @method static Builder|QuotationDetails whereCreatedAt($value)
 * @method static Builder|QuotationDetails whereQuotationId($value)
 * @method static Builder|QuotationDetails whereServiceId($value)
 * @method static Builder|QuotationDetails whereTotalServices($value)
 * @method static Builder|QuotationDetails whereUpdatedAt($value)
 * @mixin Eloquent
 */
class QuotationDetails extends Model
{
    use HasFactory;

    protected $fillable = [
      'quotation_id',
      'total_services',
      'service_id'
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

}
