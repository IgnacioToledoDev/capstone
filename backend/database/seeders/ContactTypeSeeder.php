<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class ContactTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contact_type')->insert([
            [
                'name' => 'Correo electronico',
            ],
            [
                'name' => 'Aplicacion',
            ],
            [
                'name' => 'SMS'
            ]
        ]);
    }
}
