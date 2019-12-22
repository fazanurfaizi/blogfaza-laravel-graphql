<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use App\Models\User;
use App\GraphQL\Fields\AvatarField;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A type',
        'model' => User::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'the id of user',
            ], 
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the name of user'
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'the email of user',
                'resolve' => function($root, $args) {
                    return strtolower($root->email);
                }
            ],
            'posts' => [
                'type' => Type::listOf(GraphQL::type('Post')),
                'description' => 'the posts belonging to this user'
            ],
            'avatar' => AvatarField::class
        ];
    }
}
