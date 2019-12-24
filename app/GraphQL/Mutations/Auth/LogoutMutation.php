<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use GraphQL;
use Exception;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use App\Exceptions\NotAuthorizedException;
use App\GraphQL\Traits\UserFormatter;
use App\GraphQL\Mutations\BaseMutation;
use App\GraphQL\Traits\AuthorizationTrait;

class LogoutMutation extends BaseMutation
{
    use UserFormatter, AuthorizationTrait;

    protected $attributes = [
        'name' => 'Logout',
        'description' => 'A mutation for logout'
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function resolve($root, array $args):? string 
    {
        try {
            auth()->logout();
            return ("Logout Successfully.");
        } catch (Exception $e) {
            throw new NotAuthorizedException('Test');
        }
    }
}
