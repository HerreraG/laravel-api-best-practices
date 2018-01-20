<?php 
namespace App\Core\Contracts;

interface IBaseRepository
{ 
    public function findOrFail($id, $columns = ['*'], $relations = []);
    public function find($id, $columns = ['*'], $relations = []);
    public function getAll($expression = '*', $relation = false, $has = false, $order = null, $direction = null);
    public function getAllOrder($expression = '*', $order = null, $direction = null);
}