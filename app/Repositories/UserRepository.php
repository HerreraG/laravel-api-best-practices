<?php

namespace App\Repositories;

use App\Entities\User;
use App\Core\BaseRepository;
use App\Contracts\Repositories\IUserRepository;

class UserRepository  extends BaseRepository  implements IUserRepository {

    protected $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }
}