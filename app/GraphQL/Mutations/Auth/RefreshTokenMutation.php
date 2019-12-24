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

class RefreshTokenMutation extends BaseMutation
{
    use UserFormatter;

    protected $attributes = [
        'name' => 'RefreshToken',
        'description' => 'A mutation for refresh an user token'
    ];

    public function type(): Type
    {
        return GraphQL::type('Token');
    }

    public function resolve($root, $args):? Object 
    {
        try{
            $token = auth()->refresh();
            auth()->setToken($token);

            $user = auth()->user();

            return $this->parseUserAndTokenData($token, $user);
        } catch(Exception $e){
            throw new NotAuthorizedException('Unathorized');
        }
    }
}
