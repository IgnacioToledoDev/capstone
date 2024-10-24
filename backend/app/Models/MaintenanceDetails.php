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
 * @property int|null $maintenance_id
 * @property int|null $quotation_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Maintenance|null $maintenance
 * @method static Builder|MaintenanceDetails newModelQuery()
 * @method static Builder|MaintenanceDetails newQuery()
 * @method static Builder|MaintenanceDetails query()
 * @method static Builder|MaintenanceDetails whereCreatedAt($value)
 * @method static Builder|MaintenanceDetails whereMaintenanceId($value)
 * @method static Builder|MaintenanceDetails whereQuotationId($value)
 * @method static Builder|MaintenanceDetails whereUpdatedAt($value)
 * @property-read Quotation|null $quotation
 * @mixin Eloquent
 */
class MaintenanceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'quotation_id',
        'service_id',
    ];

    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
