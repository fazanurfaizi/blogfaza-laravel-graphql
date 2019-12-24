<?php

namespace App\Services\Handlers\Users;

use Exception;
use App\Contracts\CommandInterface;
use App\Contracts\HandlerInterface;
use App\Exceptions\HandlerException;
use App\Repositories\UserRepository;
use App\GraphQL\Traits\UserFormatter;

class UpdateUser implements HandlerInterface {

    use UserFormatter;

    protected $repository;

    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function handle(CommandInterface $command):? Object {
        try {
            $user = $this->repository->update(
                $command->getData(),
                $command->getUserId()
            );
            $token = auth()->login($user);

            return $this->parseUserAndTokenData($token, $user);
        } catch(Exception $e){
            throw new HandlerException($e->getMessage());
        }
    }

}