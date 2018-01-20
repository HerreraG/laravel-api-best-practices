<?php

namespace App\Core;

use App\Core\Contracts\IBaseAppService;

class BaseAppService implements IBaseAppService {

    protected $entityRepository;
    protected $errors;

    public function getAll($relations = [], $has = false, $order = null, $direction = null) {
        return $this->entityRepository->getAll($expression = '*', $relations, $has, $order, $direction);
    }

    public function getById($id, $relations = []) {
        return $this->entityRepository->find($id, $columns = ['*'], $relations);
    }

    public function deleteById($id) {
        return $this->entityRepository->delete($id);
    }

    public function setErrors($errs)
    {
        $this->errors = $errs;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

