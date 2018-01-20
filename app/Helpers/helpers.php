<?php

use App\Helpers\DateTimeUtils;

/**
 * Return the user that is authenticated
 * @return \App\Entities\User | Illuminate\Contracts\Auth\Authenticatable
 */
if (! function_exists('currentUser')) {
    function currentUser()
    {
        return auth()->user();
    }
}

/**
 * Get the host name
 * @return mixed
 */
if (! function_exists('getServerHostName')) {
    function getServerHostName()
    {
        return request()->server("HTTP_HOST");
    }
}

/**
 * Get the date from a passed value and gives format
 * @param string $value
 * @param $outputFormat
 * @param $fromFormat
 * @return string
 */
if (! function_exists('getDateFrom')) {
    function getDateFrom($value, $outputFormat = '', $fromFormat = '')
    {
        return DateTimeUtils::get($value, $outputFormat, $fromFormat);
    }
}

/**
 * Create a date formatted from a specified format
 * @param $value
 * @return Carbon\Carbon
 */
if (! function_exists('createDate')) {
    function createDate($value)
    {
        return DateTimeUtils::createDate($value);
    }
}

/**
 * Get the time from a passed value
 * @param string $value
 * @return string
 */
if (! function_exists('getTimeFrom')) {
    function getTimeFrom($value)
    {
        return DateTimeUtils::getTimeFormatted($value);
    }
}

/**
 * Get actual Carbon date
 * @return \Carbon\Carbon
 */
if (! function_exists('getDateNow')) {
    function getDateNow()
    {
        return DateTimeUtils::getDateNow();
    }
}

/**
 * Get actual date formatted
 * @param string $format
 * @return string
 */
if (! function_exists('getDateNowFormatted')) {
    function getDateNowFormatted($format)
    {
        return DateTimeUtils::getDateNow()->format($format);
    }
}

/**
 * Create date from value
 * @param $value
 * @return \Carbon\Carbon
 */
if (! function_exists('getDateForSet')) {
    function getDateForSet($value)
    {
        return DateTimeUtils::create($value, config('constants.DATE_FORMAT'));
    }
}

/**
 * Get date from value
 * @param $value
 * @return string
 */
if (! function_exists('getDateForGet')) {
    function getDateForGet($value)
    {
        return DateTimeUtils::get($value, config('constants.DATE_FORMAT'));
    }
}

/**
 * Create datetime from value
 * @param $value
 * @return \Carbon\Carbon
 */
if (! function_exists('getDateTimeForSet')) {
    function getDateTimeForSet($value)
    {
        return DateTimeUtils::create($value, config('constants.DATETIME_FORMAT'));
    }
}

/**
 * Get datetime from value
 * @param $value
 * @return string
 */
if (! function_exists('getDateTimeForGet')) {
    function getDateTimeForGet($value)
    {
        return DateTimeUtils::get($value, config('constants.DATETIME_FORMAT'));
    }
}

/**
 * Convert string to upper case
 * @param $string
 * @return string
 */
if (! function_exists('upperCase')) {
    function upperCase($string)
    {
        return mb_strtoupper($string, 'UTF-8');
    }
}

/**
 * Convert string to lower case
 * @param $string
 * @return string
 */
if (! function_exists('lowerCase')) {
    function lowerCase($string)
    {
        return mb_strtolower($string, 'UTF-8');
    }
}

/**
 * Convert string first letter of words to uppercase
 * @param $string
 * @return string
 */
if (! function_exists('upperWords')) {
    function upperWords($string)
    {
        return ucwords(lowerCase($string));
    }
}

/**
 * Check price of condition (0, -1, or Other)
 * @param string $symbol
 * @param int $price
 * @return string
 */
if (! function_exists('checkPrice')) {
    function checkPrice($price, $symbol)
    {
        if ($price <= 0) {
            return config("prices.tags.{$price}.value");
        }

        return "{$symbol}{$price}";
    }
}

/**
 * Filter rare characters from an email
 * @param string $email
 * @return string
 */
if (! function_exists('filterEmailRareChars')) {
    function filterEmailRareChars($email)
    {
        return preg_replace('/[^A-Za-z0-9\@\.\_\-]/', '', lowerCase($email));
    }
}

/**
 * Return a password from a valid email
 * @param string $value
 * @return string
 */
if (! function_exists('getPasswordFrom')) {
    function getPasswordFrom($value)
    {
        return filterEmailRareChars($value);
    }
}

/**
 * Converts an array to an object recursively
 * @param array $array
 * @return stdClass
 */
if (! function_exists('toObject')) {
    function toObject(Array $array)
    {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = toObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
}

/**
 *
 * Add an object in an array if it is not already in array
/**
 * @param $array
 * @param $object
 * @return array
 */
if (! function_exists('addObjectToArray')) {
    function addObjectToArray($array, $object)
    {
        if (!in_array($object, $array)) {
            $array[] = $object;
        }
        return $array;
    }
}
