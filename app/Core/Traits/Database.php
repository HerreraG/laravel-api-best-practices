<?php

namespace App\Core\Traits;

use Illuminate\Support\Facades\DB;

trait Database
{
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollback();
    }

    /**
     * @param string $value
     * @return \Illuminate\Database\Query\Expression
     */
    public function query($value)
    {
        return DB::raw($value);
    }

    /**
     * @param \Illuminate\Database\Query\Expression $query
     * @param array $bindings
     * @return array|mixed
     */
    public function select($query, $bindings = [])
    {
        return DB::select($query, $bindings);
    }

    /**
     * Begin a fluent query against a database table.
     *
     * @param  string  $table
     * @return \Illuminate\Database\Query\Builder
     */
    public function table($table = null)
    {
        if(is_null($table)) {
            return DB::table($this->table);
        } else {
            return DB::table($table);
        }
    }

    public function getQuery() {
        $bindings = $this->builder->getBindings();
        $query = $this->builder->toSql();

        foreach($bindings as $binding)
        {
            $query = preg_replace('/\?/', $binding , $query, 1);
        }

        return $query;
    }

    public function disableQueryLog() {
        DB::disableQueryLog();
    }
}