<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int $administrator
 * @property string $address
 * @property int|null $number_of_employees
 * @property string|null $phone_number
 * @property string|null $email
 * @property string|null $services_offered
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereNumberOfEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereServicesOffered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tenant extends Model
{
    use HasFactory;

    const SIZE_SMALL = 1; // 1 - 5
    const SIZE_MEDIUM = 2; // 6 - 15
    const SIZE_LARGE = 3; // 15 - 25
    const SIZE_BIG = 4; // +25

    protected $fillable = [
        'name',
        'email',
        'administrator',
        'address',
        'number_of_employees',
        'phone_number',
        'email',
        'services_offered'
    ];
}
