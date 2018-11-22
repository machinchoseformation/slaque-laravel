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

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('groups')->truncate();
        DB::table('group_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($usernames as $username) {
            DB::table('users')->insert([
                "name" => $username,
                "email" => "$username@gmail.com",
                "password" => bcrypt($username),
                "created_at" => $faker->dateTimeBetween('- 2 years')
            ]);
        }

        $allUsers = \App\User::all();

        for ($i = 0; $i < 10; $i++) {

            $creator = $faker->randomElement($allUsers);
            $isOneOnOne = $faker->boolean(70);
            $otherUser = null;
            if ($isOneOnOne) {
                $otherUser = \App\User::where('id', '!=', $creator->id)
                    ->inRandomOrder()->first();
            }

            /*
            $group = new \App\Group();
            $group->name = $faker->words(3, true);
            $group->creator_id = $creator->id;
            $group->is_one_on_one = $isOneOnOne;
            if ($isOneOnOne) {
                $group->other_user_id = $otherUser->id;
            }
            $group->created_at = $faker->dateTimeBetween($creator->created_at);
            $group->save();
            $group->participants()->attach($creator->id);

            if ($isOneOnOne) {
                $group->participants()->attach($otherUser->id);
            } else {

            }

            $group->save();
            */
        }
    }
}
