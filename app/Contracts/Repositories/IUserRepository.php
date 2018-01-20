<?php 
namespace App\Contracts\Repositories;
use App\Core\Contracts\IBaseRepository;
Use App\Entities\User;

interface IUserRepository extends IBaseRepository
{
    public function create(User $user);
    public function update(User $user);
    public function delete(User $userId);
    public function addProfile(User $user, int $profileId);
    public function removeProfile(User $user, int $profileId);
}