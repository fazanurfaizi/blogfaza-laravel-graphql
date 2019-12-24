<?php

namespace App\GraphQL\Traits;

use stdClass;
use App\Models\User;

trait UserFormatter
{
    public function parseUserAndTokenData(string $token, User $user): Object {
        $tokenClass = new stdClass();
        $tokenClass->token = $token;
        $tokenClass->token_type = 'Bearer';
        $tokenClass->expires_time = 604800;
        $tokenClass->user  = $user;

        return $tokenClass;
    }
}
