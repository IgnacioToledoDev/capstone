<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property string|null $patent
 * @property string $model
 * @property int $year
 * @property int $user_id
 * @property int $owner_id
 * @property int $mechanic_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CarBrand $carBrands
 * @property-read \App\Models\User $user
 * @method static Builder|Car newModelQuery()
 * @method static Builder|Car newQuery()
 * @method static Builder|Car query()
 * @method static Builder|Car whereBrandId($value)
 * @method static Builder|Car whereCreatedAt($value)
 * @method static Builder|Car whereId($value)
 * @method static Builder|Car wherePatent($value)
 * @method static Builder|Car whereUpdatedAt($value)
 * @method static Builder|Car whereUserId($value)
 * @method static Builder|Car whereYear($value)
 * @property int|null $model_id
 * @method static Builder|Car whereMechanicId($value)
 * @method static Builder|Car whereModelId($value)
 * @method static Builder|Car whereOwnerId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\CarModel|null $carModels
 */
	class Car extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static find(mixed $brand_id)
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarBrand whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Car> $cars
 * @property-read int|null $cars_count
 */
	class CarBrand extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read CarBrand|null $brand
 * @method static Builder|CarModel newModelQuery()
 * @method static Builder|CarModel newQuery()
 * @method static Builder|CarModel query()
 * @mixi Eloquent
 * @mixin Eloquent
 * @property int $id
 * @property int|null $brand_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarModel whereUpdatedAt($value)
 */
	class CarModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static Builder|ContactType newModelQuery()
 * @method static Builder|ContactType newQuery()
 * @method static Builder|ContactType query()
 * @mixin Eloquent
 */
	class ContactType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $status_id
 * @property int $service_id
 * @property int|null $actual_mileage
 * @property string|null $recommendation_action
 * @property int $pricing
 * @property int $car_id
 * @property int $mechanic_id
 * @property $start_maintenance
 * @property $end_maintenance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Maintenance newModelQuery()
 * @method static Builder|Maintenance newQuery()
 * @method static Builder|Maintenance query()
 * @method static Builder|Maintenance whereActualMileage($value)
 * @method static Builder|Maintenance whereCarId($value)
 * @method static Builder|Maintenance whereCreatedAt($value)
 * @method static Builder|Maintenance whereDescription($value)
 * @method static Builder|Maintenance whereId($value)
 * @method static Builder|Maintenance whereMechanicId($value)
 * @method static Builder|Maintenance whereName($value)
 * @method static Builder|Maintenance wherePricing($value)
 * @method static Builder|Maintenance whereRecommendationAction($value)
 * @method static Builder|Maintenance whereServiceId($value)
 * @method static Builder|Maintenance whereStatusId($value)
 * @method static Builder|Maintenance whereUpdatedAt($value)
 * @method static Builder|Maintenance whereStartMaintenance($value)
 * @method static Builder|Maintenance whereEndMaintenance($value)
 * @property-read \App\Models\Car $car
 * @property-read \App\Models\User $mechanic
 * @property-read \App\Models\Service $service
 * @property-read \App\Models\StatusCar $statusCar
 * @mixin \Eloquent
 * @property string|null $date_made
 * @method static \Illuminate\Database\Eloquent\Builder|Maintenance whereDateMade($value)
 */
	class Maintenance extends \Eloquent {}
}

namespace App\Models{
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
	class MaintenanceDetails extends \Eloquent {}
}

namespace App\Models{
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
	class MechanicMaintenance extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read Car|null $car
 * @property-read User|null $owner
 * @method static Builder|OwnerHistorical newModelQuery()
 * @method static Builder|OwnerHistorical newQuery()
 * @method static Builder|OwnerHistorical query()
 * @mixin Eloquent
 */
	class OwnerHistorical extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static Builder|Quotation newModelQuery()
 * @method static Builder|Quotation newQuery()
 * @method static Builder|Quotation query()
 * @mixin Eloquent
 * @property-read \App\Models\Car|null $car
 */
	class Quotation extends \Eloquent {}
}

namespace App\Models{
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
 * @property int $is_approved_by_client
 * @method static \Illuminate\Database\Eloquent\Builder|QuotationDetails whereIsApprovedByClient($value)
 */
	class QuotationDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read ContactType|null $contactType
 * @method static Builder|Reminder newModelQuery()
 * @method static Builder|Reminder newQuery()
 * @method static Builder|Reminder query()
 * @mixin Eloquent
 */
	class Reminder extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read Car|null $car
 * @property-read Reminder|null $reminder
 * @method static Builder|Reservation newModelQuery()
 * @method static Builder|Reservation newQuery()
 * @method static Builder|Reservation query()
 * @mixin Eloquent
 */
	class Reservation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Service newModelQuery()
 * @method static Builder|Service newQuery()
 * @method static Builder|Service query()
 * @method static Builder|Service whereCreatedAt($value)
 * @method static Builder|Service whereDescription($value)
 * @method static Builder|Service whereId($value)
 * @method static Builder|Service whereName($value)
 * @method static Builder|Service whereTypeId($value)
 * @method static Builder|Service whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $price
 * @property-read \App\Models\TypeService $type
 * @method static Builder|Service wherePrice($value)
 * @mixin \Eloquent
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $status
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|StatusCar newModelQuery()
 * @method static Builder|StatusCar newQuery()
 * @method static Builder|StatusCar query()
 * @method static Builder|StatusCar whereCreatedAt($value)
 * @method static Builder|StatusCar whereDescription($value)
 * @method static Builder|StatusCar whereId($value)
 * @method static Builder|StatusCar whereStatus($value)
 * @method static Builder|StatusCar whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection<int, Maintenance> $Maintenance
 * @property-read int|null $maintenance_count
 * @mixin \Eloquent
 */
	class StatusCar extends \Eloquent {}
}

namespace App\Models{
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
 * @mixin Eloquent
 */
	class Tenant extends \Eloquent {}
}

namespace App\Models{
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
	class TypeService extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $rut
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $username
 * @property string|null $lastname
 * @property string|null $phone
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastname($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRut($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @mixin Eloquent
 * @property int|null $mechanic_score
 * @property-read string $full_name
 * @method static Builder|User whereMechanicScore($value)
 * @method static Builder|User wherePhone($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject {}
}

