<?php

namespace App\Services\Commands\Users;

use App\Contracts\CommandInterface;

class Login implements CommandInterface {

    protected $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function getData(): array {
        return $this->data;
    }

}