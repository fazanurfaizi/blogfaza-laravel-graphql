<?php

namespace App\services\Handlers\Users;

use Exception;
use Illuminate\Support\Collection;
use App\Contracts\CommandInterface;
use App\Contracts\HandlerInterface;
use App\Exceptions\HandlerException;
use App\Repositories\UserRepository;

class GetUserWhere implements HandlerInterface {

    protected $repository;

    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    public function handle(CommandInterface $command): Collection {
        try {
            return $this->repository->with($command->getWith())->findWhere($command->getWhere(), $command->getColumns());
        } catch (Exception $e) {
            throw new HandlerException($e->getMessage());            
        }
    }

}