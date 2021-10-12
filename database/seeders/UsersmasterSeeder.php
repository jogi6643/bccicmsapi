<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class UsersmasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        //foreach(range(1,2) as $index){
            DB::table('usersmsts')->insert([
                'user_first_name' => $faker->sentence(1),
                'user_last_name' => 'katiyar',
                'user_password' => 'katiyar',
                'user_title' => 'Mr.',
                'user_email_id' => '1',
                'user_dob' => '2017-06-15',
                'user_is_association' => 1,
                'user_group_id' => 12,
                'user_country_id' => '31',
                'user_address' => $faker->paragraph(2),
                'user_status' => 1,
                'user_created_by' => 20,
                'user_modified_by' => 20,
                'user_role_id' => 1,
                'user_is_online' => 1,
                'user_season_id' => $faker->paragraph(1),
                'association_id' => 421,
                'user_phone_number' => 1234567890,
                'device_id' => $faker->paragraph(1),
                'flag_id' => 41,
                'user_otp' => 123456,
                'user_gender' => 1,
                'user_photo_url' => $faker->paragraph(1)
            ]);
        //}
    }
}
