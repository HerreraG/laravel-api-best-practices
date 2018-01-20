<?php

namespace App\Helpers;


class ArrayUtils
{
    /**
     * Find and object into an array
     * @param $array
     * @param $index
     * @param $value
     * @return null
     */
    public static function objArraySearch($array, $index, $value)
    {
        foreach($array as $arrayInf) {
            if($arrayInf->{$index} == $value) {
                return $arrayInf;
            }
        }
        return null;
    }

    /**
     * check if the var is an array, if not, then, create one with the element and return
     * @param $possible_array
     * @return array
     */
    public static function checkArrayOrForceToOne($possible_array)
    {
        if ($possible_array != null) {
            if (!is_array($possible_array))
                $possible_array = array($possible_array);
        }
        return $possible_array;
    }




}