<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;


class CountrymstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('countrymsts')->insert([
            'country_name' => 'India',
            'country_flag' => '',
            'country_status' => 1,
            'country_nationality' => $faker->paragraph(2)
        ]);
    }
}
