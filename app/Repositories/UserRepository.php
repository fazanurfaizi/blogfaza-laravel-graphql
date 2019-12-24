<?php

namespace App\Repositories;

use App\Models\user;

/**
 * class UserRepository.
 *
 * @package namespace App\Repositories;
 */
class UserRepository extends BaseRepository
{
    public function model(): string {
        return User::class;
    }
}
