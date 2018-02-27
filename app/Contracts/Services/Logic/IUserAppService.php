<?php

namespace App\Contracts\Services\Logic;

use App\Core\Contracts\IBaseAppService;
use App\Entities\User;

interface IUserAppService extends IBaseAppService {
    public function save(array $data);

    public function create(array $data);

    public function update(array $data);

    public function delete(int $userId);
}
