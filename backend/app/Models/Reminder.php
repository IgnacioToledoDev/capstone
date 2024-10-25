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
 * @property-read ContactType|null $contactType
 * @method static Builder|Reminder newModelQuery()
 * @method static Builder|Reminder newQuery()
 * @method static Builder|Reminder query()
 * @mixin Eloquent
 */
class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_sending',
        'contact_type_id'
    ];

    public function contactType(): BelongsTo
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id');
    }

}
