<?php

namespace App\Core\Contracts;

interface IBaseAppService {
    public function getAll($relations = [], $has = false, $order = null, $direction = null);
    public function getById($id, $relations = []);
    public function deleteById($id);    
    public function setErrors($errs);
    public function getErrors();
}