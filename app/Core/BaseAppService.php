<?php

namespace App\Core;

use App\Core\Contracts\IBaseAppService;

class BaseAppService implements IBaseAppService {

    protected $entityRepository;
    protected $errors;

    public function getAll($relations = [], $order = null, $direction = null) {
        return $this->entityRepository->with($relations)->orderBy('id', 'asc')->get();
    }

    public function getById($id, $relations = []) {
        return $this->entityRepository->with($relations)->find($id);
    }

    public function setErrors($errs) {
        $this->errors = $errs;
    }

    public function getErrors() {
        return $this->errors;
    }
}

