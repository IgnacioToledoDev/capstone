<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property-read Maintenance|null $maintenance
 * @property-read User|null $mechanic
 * @method static Builder|MechanicMaintenance newModelQuery()
 * @method static Builder|MechanicMaintenance newQuery()
 * @method static Builder|MechanicMaintenance query()
 * @mixin Eloquent
 */
class MechanicMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
      'mechanic_stake',
      'assign_date',
      'mechanic_id',
      'maintenance_id',
    ];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }
}
