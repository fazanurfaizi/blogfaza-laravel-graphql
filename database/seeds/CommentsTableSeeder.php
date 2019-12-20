<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all(); 
        $posts = Post::all();

        $faker = Faker\Factory::create();

        foreach($posts as $post){
            for ($i = 0; $i < rand(1, 5); $i++) { 
                Comment::create([
                    'body' => $faker->sentence,
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id
                ]);
            }
        }
    }
}
