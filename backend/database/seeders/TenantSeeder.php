<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrator = User::where(['id' => 1])->first();

        DB::table('tenants')->insert([
            'name' => 'SUPER_TENANT',
            'administrator' => $administrator->id,
            'address' => 'Las Cumbres 02138 Of. Depto. 404',
            'number_of_employees' => 1,
            'phone_number' => '9123456789',
            'email' => 'supertenant@autominder.cl',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tenants')->insert([
            'name' => 'TENANT_APP_REGISTRATION',
            'administrator' => $administrator->id,
            'address' => 'Las Cumbres 02138 Of. Depto. 404',
            'number_of_employees' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
