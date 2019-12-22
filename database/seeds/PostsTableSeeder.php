<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $users = User::select('id')->get();
        $categories = Category::select('id')->get();
        $tags = Tag::all();        
        $images = FIle::allFiles(public_path('storage/images'));
        $title = $faker->sentence;      
        $slug = str_slug($title);
        
        foreach (range(0, 10) as $value) {
            Post::create([
                'title' => $title,
                'description' => $faker->sentence,
                'slug' => $slug,
                'body' => $faker->paragraph(10),
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'image' => $images[$value]->getFilename(),
                'views' => rand(1, 100)
            ])
            ->tags()
            ->sync($tags->random(rand(1, 3))->pluck('id')->toArray());
        }
    }
}
