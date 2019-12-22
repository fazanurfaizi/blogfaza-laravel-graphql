<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use App\Models\Category;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CategoryType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Category',
        'description' => 'A type for category query',
        'model' => Category::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of category'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the name of category',        
            ],
            'posts' => [
                'type' => Type::listOf(GraphQL::type('Post')),
                'description' => 'the posts belonging to this category'
            ]
        ];
    }
}
