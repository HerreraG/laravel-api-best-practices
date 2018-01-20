<?php

namespace App\Services\Logic;

use App\Entities\User;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Core\BaseAppService;
use App\Contracts\Services\Logic\IUserAppService;
use App\Contracts\Repositories\IUserRepository;

class UserAppService extends BaseAppService implements IUserAppService {

    protected $entityRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->entityRepository = $userRepository;
    }

    public function validator(array $data, $id = 0)
    {
        $rules = [
            'name'     => 'required|string',
            'password' => 'required|min:6',
        ];

        if($id !== 0) {
            return Validator::make($data, $rules);
        } else {
            $rules['email'] = 'email|max:255|unique:users,email';
            return Validator::make($data, $rules);
        }

    }

    public function save(array $data) {
        if(isset($data['id']) && $data['id'] != 0) {
            return $this->update($data);
        } else {
            return $this->create($data);
        }
    }

    public function create(array $data) {
        $v = $this->validator($data);
        if ($v->fails()) {
            $this->setErrors($v->errors());
            return;
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);

        DB::transaction(function () use ($user, $data)  {
            $this->entityRepository->create($user);
            $this->addProfilesCollection($user, $data['profiles']);
        });

        return $user;
    }

    public function update(array $data) {
        $v = $this->validator($data, $data['id']);
        if ($v->fails()) {
            $this->setErrors($v->errors());
            return;
        }

        $user  = $this->entityRepository->find($data['id']);
        $user->name = $data['name'];
        $user->password = bcrypt($data['password']);

        foreach($user->profiles as $profile) {
            if(! in_array($profile->id, $data['profiles'])) {
                $this->entityRepository->removeProfile($user, $profile->id);
            } else {
                $key = array_search($profile->id, $data['profiles']);
                unset($data['profiles'][$key]);
            }
        }

        DB::transaction(function () use ($user, $data)  {
            $this->entityRepository->update($user);
            if(count($data['profiles'])) {
                $this->addProfilesCollection($user, $data['profiles']);
            }
        });

        return $user;
    }

    public function delete(int $userId)
    {
        $user = $this->entityRepository->find($userId);

        if(! $user) {
            $this->setErrors(['error' => 'user_not_found']);
            return;
        }

        if($this->entityRepository->delete($user)) {
            return true;
        } else {
            $this->setErrors(['error' => 'can_delete_user']);
        }
    }

    public function addProfilesCollection(User $user, array $profiles) {
        foreach ($profiles as $profileId) {
            $this->entityRepository->addProfile($user, $profileId);
        }
    }
}