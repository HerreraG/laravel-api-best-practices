<?php

namespace App\Core;
use App\Core\Contracts\IBaseRepository;
use App\Core\Traits\Database;
use App\Core\Traits\Exception;

class BaseRepository implements IBaseRepository {    
    
    use Database, Exception;

    protected $entity;
    protected $order = 'id';
    protected $direction = 'asc';

    /**
     * @var null|\Illuminate\Database\Eloquent\Builder
     */
    protected $builder = null;


    #region Methods Private
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function newQuery()
    {
        return $this->entity->newQuery();
    }

    /**
     * @param string $expression
     * @param array $bindings
     * @return \Illuminate\Database\Query\Builder
     */
    private function selectAll($expression = '*', array $bindings = [])
    {
        return $this->newQuery()->selectRaw($expression, $bindings);
    }
    #endregion
 
    #region Methos Protected
    /**
     * @param string $expression
     * @param array $bindings
     * @return \Illuminate\Database\Query\Builder
     */
    protected function all($expression = '*', array $bindings = [])
    {
        return $this->selectAll($expression, $bindings);
    }
    
    /**
     * @param $att
     * @param $condition
     * @param $value
     * @param array $relation
     * @param string $expression
     * @return \Illuminate\Support\Collection
     */
    protected function getBy($att, $value, $condition = '=', $relation = [], $expression = '*')
    {
        return $this->all($expression)
            ->where($att, $condition, $value)
            ->with($relation)
            ->get();
    }

    /**
     * @param $att
     * @param $value
     * @param array $relation
     * @param string $columns
     * @return mixed
     */
    protected function getOne($att, $value = [], $relation = [], $columns = '*')
    {
        return $this->getBy($att, $value, '=', $relation, $columns)->first();
    }

    protected function has($has = null) {
        if ($this->builder) {
            if ($has) {
                if (is_array($has)) {
                    foreach ($has as $key => $closure) {
                        $this->builder->whereHas($key, $closure);
                    }
                } else {
                    $this->builder->has($has);
                }
            }
        }
    }

    protected function with($relation = null) {
        if ($this->builder) {
            if ($relation) {
                $this->builder->with($relation);
            }
        }
    }

    protected function order($order = null, $direction = null) {
        if ($this->builder) {
            if (!is_null($order)) {
                $this->builder->orderBy($order,
                    is_null($direction) ? $this->direction : $direction
                );
            }
        }
    }
    #endregion

    #region Methods Public
    /**
     * @param $id
     * @param array $columns
     * @param string|array $relations
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id, $columns = ['*'], $relations = [])
    {
        return $this->newQuery()->with($relations)->findOrFail($id, $columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @param string|array $relations
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|mixed
     */
    public function find($id, $columns = ['*'], $relations = [])
    {
        return $this->newQuery()->with($relations)->find($id, $columns);
    }

    /**
     * @param string $expression
     * @param $relation
     * @param $has
     * @param $order
     * @param $direction
     * @return \Illuminate\Support\Collection
     */
    public function getAll($expression = '*', $relations = [], $has = false, $order = null, $direction = null)
    {
        $this->builder = $this->all($expression);

        $this->has($has);

        $this->with($relations);

        $this->order($order, $direction);

        return $this->builder->get();
    }

    /**
     * @param string $expression
     * @param string|null $order
     * @param string|null $direction
     * @return \Illuminate\Support\Collection
     */
    public function getAllOrder($expression = '*', $order = null, $direction = null)
    {
        return $this->getAll($expression, false, false,
            is_null($order) ? $this->order : $order,
            $direction);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function softDelete($id)
    {
        /** @var \App\Entities\Entity $model */
        $model = $this->findOrFail($id);
        return $model->softDelete();
    }
    #endregion
}