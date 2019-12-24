<?php

namespace App\Services\Commands\Users;

use App\Contracts\CommandInterface;

class UpdateUser implements CommandInterface {

    protected $user_id;
    protected $data;
    
    public function __construct(int $user_id, array $data) {
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $this->data = $data;
        $this->user_id = $user_id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getData(): array {
        return $this->data;
    }

}