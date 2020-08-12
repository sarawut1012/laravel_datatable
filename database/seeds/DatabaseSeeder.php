<?php

use Illuminate\Database\Seeder;
// Import DB and Faker services
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $gender = $faker->randomElement(['male', 'female']);

        foreach (range(1,1000) as $index) {
            DB::table('students')->insert([
                'name' => $faker->name($gender),
                'email' => $faker->unique()->email,
                'username' => $faker->username,
                'phone' => $faker->phoneNumber,
                'dob' => $faker->date($format = 'Y-m-d', $max = 'now')
            ]);
        }
    }
}
