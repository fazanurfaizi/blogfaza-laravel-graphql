<?php

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'Web Development',
            'Laravel',
            'Code Igniter',
            'Backend Development',
            'API',
            'School',
            'Framework'
        ];

        foreach ($tags as $value) {
            Tag::create([
                'name' => $value
            ]);
        }
    }
}
