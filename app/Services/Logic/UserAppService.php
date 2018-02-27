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

    public function __construct(IUserRepository $userRepository) {
        $this->entityRepository = $userRepository;
    }

    public function validator(array $data, $id = 0) {
        $rules = [
            'name' => 'required|string',
            'password' => 'required|min:6',
        ];

        if ($id !== 0) {
            return Validator::make($data, $rules);
        } else {
            $rules['email'] = 'email|max:255|unique:users,email';
            return Validator::make($data, $rules);
        }

    }

    public function save(array $data) {
        if (isset($data['id']) && $data['id'] != 0) {
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

        $transactionResult = DB::transaction(function () use ($user, $data) {
            $user = $this->entityRepository->create($user->getAttributes());
            $this->entityRepository->sync($user->id, 'profiles', $data['profiles']);
            return $user;
        });

        return $transactionResult;
    }

    public function update(array $data) {
        $v = $this->validator($data, $data['id']);
        if ($v->fails()) {
            $this->setErrors($v->errors());
            return;
        }

        $user = new User();
        $user->name = $data['name'];
        $user->password = bcrypt($data['password']);

        $transactionResult = DB::transaction(function () use ($user, $data) {
            $user = $this->entityRepository->update($data['id'], $user->getAttributes());
            $this->entityRepository->sync($user->id, 'profiles', $data['profiles']);
            return $user;
        });

        return $transactionResult;
    }

    public function delete(int $userId) {
        $user = $this->entityRepository->find($userId);

        if (!$user) {
            $this->setErrors(['error' => 'user_not_found']);
            return;
        }

        if ($this->entityRepository->delete($userId)) {
            return true;
        } else {
            $this->setErrors(['error' => 'can_delete_user']);
        }
    }
}