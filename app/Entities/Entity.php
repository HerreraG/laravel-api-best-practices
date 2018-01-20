<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public static function getClass()
    {
        return get_class(new static);
    }

    /** Properties */
    public function getIsdefaultAttribute($value)
    {
        return (boolean)$value;
    }

    /**
     * @return mixed
     */
    public function softDelete()
    {
        return $this->fill(['active' => false])->save();
    }
}