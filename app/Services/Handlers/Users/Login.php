<?php

namespace App\Services\Handlers\Users;

use Exception;
use App\Models\User;
use App\Contracts\CommandInterface;
use App\Contracts\HandlerInterface;
use App\Exceptions\HandlerException;
use App\Exceptions\NotAuthorizedException;
use App\GraphQL\Traits\UserFormatter;

class Login implements HandlerInterface
{
    use UserFormatter;

    public function handle(CommandInterface $command):? Object
    {
        try {
            $token = auth()->attempt($command->getData());

            if (! $token) {
                throw new NotAuthorizedException();
            }
            
            $user = auth()->user();

            if (!$token) {
                throw new NotAuthorizedException();
            }

            return $this->parseUserAndTokenData($token, $user);
        } catch (Exception $error) {
            throw new HandlerException($error->getMessage());
        }
    }
}
