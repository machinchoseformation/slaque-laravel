<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $usernames = ['yo', 'bla', 'pif', 'paf', 'pouf', 'toto', 'foo', 'bar'];

        foreach($usernames as $username) {
            DB::table('users')->insert([
                "name" => $username,
                "email"=> "$username@gmail.com",
                "password"=> bcrypt($username),
                "created_at" => $faker->dateTimeBetween('- 2 years')
            ]);
        }
        //@todo fix this shit
        for($i=0; $i<100; $i++){
            DB::table('groups')->insert(
                [
                    "name" => $faker->words(3, true),
                    "creator_id" => array_rand($usernames),
                    "is_one_on_one" => $faker->boolean()
                ]

            );
        }
    }
}
