<?php

namespace App\Core;

use App\Core\Contracts\IBaseRepository;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository implements IBaseRepository {

    /**
     * @var \App\Entities\Entity
     */
    protected $entity;

    /**
     * Retrieve data array for populate field select
     *
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function lists($column, $key = null) {
        return $this->entity->lists($column, $key);
    }


    /**
     * Retrieve data array for populate field select
     * Compatible with Laravel 5.3
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function pluck($column, $key = null) {
        return $this->entity->pluck($column, $key);
    }

    /**
     * Sync relations
     *
     * @param $id
     * @param $relation
     * @param $attributes
     * @param bool $detaching
     * @return mixed
     */
    public function sync($id, $relation, $attributes, $detaching = true) {
        return $this->find($id)->{$relation}()->sync($attributes, $detaching);
    }

    /**
     * SyncWithoutDetaching
     *
     * @param $id
     * @param $relation
     * @param $attributes
     * @return mixed
     */
    public function syncWithoutDetaching($id, $relation, $attributes) {
        return $this->sync($id, $relation, $attributes, false);
    }

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*']) {
        if ($this->entity instanceof Builder) {
            $results = $this->entity->get($columns);
        } else {
            $results = $this->entity->all($columns);
        }
        return $results;
    }

    /**
     * Alias of All method
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function get($columns = ['*']) {
        return $this->all($columns);
    }

    /**
     * Retrieve first data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function first($columns = ['*']) {
        return $this->entity->first($columns);
    }

    /**
     * Retrieve first data of repository, or return new Entity
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function firstOrNew(array $attributes = []) {
        return $this->entity->firstOrNew($attributes);
    }

    /**
     * Retrieve first data of repository, or create new Entity
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function firstOrCreate(array $attributes = []) {
        return $this->entity->firstOrCreate($attributes);
    }


    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate") {
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        return $this->entity->{$method}($limit, $columns);
        //$results->appends(app('request')->query());
    }

    /**
     * Retrieve all data of repository, simple paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*']) {
        return $this->paginate($limit, $columns, "simplePaginate");
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*']) {
        return $this->entity->find($id, $columns);
    }


    /**
     * Find data by field and value
     *
     * @param       $field
     * @param       $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*']) {
        return $this->entity->where($field, '=', $value)->get($columns);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*']) {
        $this->applyConditions($where);
        return $this->entity->get($columns);
    }

    /**
     * Find data by multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*']) {
        return $this->entity->whereIn($field, $values)->get($columns);
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param       $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*']) {
        return $this->entity->whereNotIn($field, $values)->get($columns);
    }

    /**
     * Save a new entity in repository
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     *
     * @return \App\Entities\Entity|null
     */
    public function create(array $attributes) {
        $entity = $this->entity->newInstance($attributes);
        if ($entity->save()) {
            return $entity;
        }
        return null;
    }

    /**
     * Update a entity in repository by id
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     * @param       $id
     *
     * @return \App\Entities\Entity|null
     */
    public function update($id, array $attributes) {
        $entity = $this->entity->findOrFail($id);
        $entity->fill($attributes);

        if ($entity->save()) {
            return $entity;
        }

        return null;
    }

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return \App\Entities\Entity|null
     */
    public function delete($id) {
        $entity = $this->find($id);

        if ($entity->delete()) {
            return $entity;
        }

        return null;
    }

    /**
     * Check if entity has relation
     *
     * @param string $relation
     *
     * @return $this
     */
    public function has($relation) {
        $this->entity = $this->entity->has($relation);
        return $this;
    }

    /**
     * Load relations
     *
     * @param array|string $relations
     *
     * @return $this
     */
    public function with($relations) {
        $this->entity = $this->entity->with($relations);
        return $this;
    }

    /**
     * Add subselect queries to count the relations.
     *
     * @param  mixed $relations
     * @return $this
     */
    public function withCount($relations) {
        $this->entity = $this->entity->withCount($relations);
        return $this;
    }

    /**
     * Load relation with closure
     *
     * @param string $relation
     * @param closure $closure
     *
     * @return $this
     */
    public function whereHas($relation, $closure) {
        $this->entity = $this->entity->whereHas($relation, $closure);
        return $this;
    }

    /**
     * Set hidden fields
     *
     * @param array $fields
     *
     * @return $this
     */
    public function hidden(array $fields) {
        $this->entity->setHidden($fields);
        return $this;
    }

    /**
     * Order collection by a given column
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($column, $direction = 'asc') {
        $this->entity = $this->entity->orderBy($column, $direction);
        return $this;
    }

    /**
     * Set visible fields
     *
     * @param array $fields
     *
     * @return $this
     */
    public function visible(array $fields) {
        $this->entity->setVisible($fields);
        return $this;
    }

    /**
     * Applies the given where conditions to the entity.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where) {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->entity = $this->entity->where($field, $condition, $val);
            } else {
                $this->entity = $this->entity->where($field, '=', $value);
            }
        }
    }

}