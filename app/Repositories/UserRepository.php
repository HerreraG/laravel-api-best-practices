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

    public function create(User $user) {
        return $user->save();
    }

    public function update(User $user) {
        return $user->save();
    }

    public function delete(User $user) {
        return $user->delete();
    }

    public function addProfile(User $user, int $profileId) {
        $user->profiles()->attach($profileId);
    }

    public function removeProfile(User $user, int $profileId) {
        $user->profiles()->detach($profileId);
    }
}