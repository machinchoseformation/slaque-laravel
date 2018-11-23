<?php

use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create("fr_FR");
        $allGroups = \App\Group::all();
        $allUsers = \App\User::all();

        foreach($allGroups as $g){
            $num = mt_rand(2,50);
            for($i=0; $i<$num; $i++) {
                $creator = $faker->randomElement($allUsers);
                //augmenter la valeur ici pour activer les messages de type image
                $isLink = $faker->boolean(0);
                $isLinkToImage = false;
                $linkInfo = null;

                if ($isLink){
                    $dir = __DIR__ . '/../../public/img/groups';
                    $img = $faker->image($dir, 400, 600, 'cats', false);

                    $isLinkToImage = true;
                    $linkInfo = ['local_name' => $img];
                }

                DB::table('messages')->insert([
                    "content" => $faker->realText(mt_rand(20, 300)),
                    "edited" => $faker->boolean(3),
                    "deleted" => $faker->boolean(3),
                    "is_link" => $isLink,
                    "is_link_to_image" => $isLinkToImage,
                    "link_info" => json_encode($linkInfo),
                    "creator_id" => $creator->id,
                    "group_id" => $g->id,
                    "created_at" => $faker->dateTimeBetween($g->created_at),
                ]);
            }
        }
    }
}
