<?php

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $allUsers = \App\User::all();

        for ($i = 0; $i < 100; $i++) {

            $creator = $faker->randomElement($allUsers);
            $isOneOnOne = $faker->boolean(70);
            $otherUser = null;
            if ($isOneOnOne) {
                $otherUser = \App\User::where('id', '!=', $creator->id)
                    ->inRandomOrder()->first();
            }

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
                //add many participants
                $participants = $faker->randomElements($allUsers, mt_rand(2,10));
                foreach($participants as $p){
                    if ($p->id === $group->creator_id){
                        continue;
                    }
                    $group->participants()->attach($p->id);
                }
            }

            $group->save();

        }
    }
}
