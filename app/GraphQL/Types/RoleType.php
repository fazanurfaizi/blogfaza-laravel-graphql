<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL;
use App\Models\Role;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Role',
        'description' => 'A type for user role',
        'model'=> Role::class
    ];

    public function fields(): array
    {
        return [
            'id' => [                
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the role'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the role'
            ]
        ];
    }
}
