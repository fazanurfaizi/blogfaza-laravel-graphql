<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use GraphQL;
use GraphQL\Type\Definition\Type;
use App\Services\Commands\Users\CreateUser;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Traits\AuthorizationTrait;

class RegisterMutation extends BaseMutation
{
    protected $attributes = [
        'name' => 'Register',
        'description' => 'A mutation for register a new user'
    ];

    public function type(): Type
    {
        return GraphQL::type('Token');
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',    
                    'string'                
                ]
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',
                    'email',
                    'unique:users,email'
                ]
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',
                    'min:8',
                    'confirmed',
                    'string'
                ]
            ],
            'password_confirmation' => [
                'name' => 'password_confirmation',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',
                    'min:8',
                    'string'
                ]
            ]
        ];
    }

    public function resolve($root, array $args):? Object
    {
        return $this->dispatch(new CreateUser($args));
    }
}
