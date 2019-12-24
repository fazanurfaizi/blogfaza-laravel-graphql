<?php

namespace App\Services\Commands\Users;

use App\Contracts\CommandInterface;

class DeleteUser implements CommandInterface {

    protected $user_id;

    public function __construct(int $user_id) {
        $this->user_id = $user_id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

}