<?php

namespace App\Services\Commands\Users;

use App\Contracts\CommandInterface;

class CreateUser implements CommandInterface
{

    protected $data;

    public function __construct(array $data)
    {
        // if (!isset($data['image'])) {
        //     $data['image'] = config('app.default_user_image_url');
        // }
        $data['password']  = bcrypt($data['password']);
        $this->data        = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
