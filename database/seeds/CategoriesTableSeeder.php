<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'PHP',
            'Javascript',
            'HTML',
            'CSS'
        ];

        foreach ($categories as $value) {
            Category::create([
                'name' => $value
            ]);
        }
    }
}
