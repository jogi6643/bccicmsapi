<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class UserrolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('userroles')->insert([
            'role_name' => 'Subper Admin',
            'role_status' => 1,
            'read' => $faker->paragraph(2),
            'write' => $faker->paragraph(2),
            'country_nationality' => $faker->paragraph(2)
        ]);
    }
}
