<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypeService whereUpdatedAt($value)
 * @mixin \Eloquent
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
