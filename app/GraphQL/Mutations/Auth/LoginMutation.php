<?php

namespace App\GraphQL\Mutations\Auth;

use GraphQL\Type\Definition\Type;
use App\Services\Commands\Users\LogIn;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Mutations\BaseMutation;

class LoginMutation extends BaseMutation
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'login'
    ];

    /**
     * @return Type
     */
    public function type(): Type
    {
        return GraphQL::type('Token');
    }

    /**
     * @return array
     */
    public function args(): array
    {
        return [
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'email'],
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, array $args):? Object
    {
        $credentials = [
            'email' => $args['email'],
            'password' => $args['password']
        ];

        return $this->dispatch(new LogIn($credentials));
    }
}
