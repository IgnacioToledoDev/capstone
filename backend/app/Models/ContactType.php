<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static Builder|ContactType newModelQuery()
 * @method static Builder|ContactType newQuery()
 * @method static Builder|ContactType query()
 * @mixin Eloquent
 */
class ContactType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
