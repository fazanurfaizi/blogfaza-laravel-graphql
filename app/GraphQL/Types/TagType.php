<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use App\Models\Tag;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class TagType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Tag',
        'description' => 'A type for tag query',
        'model' => Tag::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the id of tag'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the name of tag'
            ]
        ];
    }
}
