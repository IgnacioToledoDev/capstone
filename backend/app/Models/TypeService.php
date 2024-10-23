<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TypeService newModelQuery()
 * @method static Builder|TypeService newQuery()
 * @method static Builder|TypeService query()
 * @method static Builder|TypeService whereCreatedAt($value)
 * @method static Builder|TypeService whereDescription($value)
 * @method static Builder|TypeService whereId($value)
 * @method static Builder|TypeService whereName($value)
 * @method static Builder|TypeService whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TypeService extends Model
{
    use HasFactory;

    const INSPECTION_AND_DIAGNOSTIC = 1;
    const PREVENTIVE_MAINTENANCE = 2;
    const CORRECTIVE_MAINTENANCE = 3;

    protected $fillable = [
        'name',
        'description',
    ];
}
