<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use GraphQL;
use Closure;
use App\Models\User;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use App\Services\Commands\Users\UpdateUser;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Traits\AuthorizationTrait;

class UpdateUserPasswordMutation extends BaseMutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'UpdateUserPassword',
        'description' => 'A mutation for update user password'
    ];

    public function type(): Type
    {
        return GraphQL::type('Token');
    }

    public function args(): array
    {
        return [
            'email' => [
                'name' => 'email',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required',                    
                ]
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string()),
                'rules' => [
                    'required'
                ]
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields):? Object
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();
        $user = User::where('email', '=', $args['email'])->first();
        if(!$user){
            return new Error("User not Found!");
        }

        return $this->dispatch(new UpdateUser($user->id, [
            'password' => $args['password']
        ]));
    }
}
