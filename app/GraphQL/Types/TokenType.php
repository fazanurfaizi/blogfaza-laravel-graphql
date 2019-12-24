<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TokenType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Token',
        'description' => 'A type of Token'
    ];

    public function fields(): array
    {
        return [
            'token' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The access token of the user'
            ],
            'token_type' => [
                'type' => Type::string(),
                'description' => 'The token tipe'
            ],
            'expires_time' => [
                'type' => Type::int(),
                'description' => 'The expires time of the token'
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'User Data'
            ]
        ];
    }
}
