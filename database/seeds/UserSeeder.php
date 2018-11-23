<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('fr_FR');
        $usernames = [];
        for($i=0; $i<50; $i++){
            $usernames[] = $faker->unique()->userName;
        }
        $usernames = ['yo', 'bla', 'pif', 'paf', 'pouf', 'toto', 'foo', 'bar'];
        $usernames = array_merge($usernames, range('a', 'z'));

        foreach ($usernames as $username) {
            DB::table('users')->insert([
                "name" => $username,
                "email" => "$username@gmail.com",
                "password" => bcrypt($username),
                "created_at" => $faker->dateTimeBetween('- 2 years')
            ]);
        }
    }
}
