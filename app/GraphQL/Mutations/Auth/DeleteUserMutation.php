<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use GraphQL\Type\Definition\Type;
use App\Services\Commands\Users\DeleteUser;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Traits\AuthorizationTrait;

class DeleteUserMutation extends BaseMutation
{

    use AuthorizationTrait;

    protected $attributes = [
        'name' => 'DeleteUser',
        'description' => 'A mutation for delete an user'
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::int()),
                'rules' => [
                    'required'
                ]
            ]
        ];
    }

    public function resolve($root, array $args):? string
    {
        return $this->dispatch(new DeleteUser($args['id']));
    }
}
