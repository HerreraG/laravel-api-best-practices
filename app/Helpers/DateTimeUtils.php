<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateTimeUtils
{
    /**
     * return 00:00
     *
     * @param integer $value
     * @return mixed
     */
    public static function intTimeToStringTime($value){
        return sprintf("%'.02d:00", $value);
    }

    /**
     * return hour as int
     *
     * @param $value
     * @return mixed
     */
    public static function getUnixTimeToIntTime($value){
        $time = self::createDate($value);
        return intval($time->format('H'));
    }

    /**
     * return int value in minutes from unix
     *
     * @param $value
     * @return float
     */
    public static function getMinutesFromUnixTime($value){
        return $value / 60; //60 == 1 unix minute
    }

    /**
     * Get a Carbon instance for the current date and time
     *
     * @return Carbon static
     */
    public static function getDateNow(){
        return Carbon::now();
    }

    /**
     * Create and get a Carbon instance for a value with format
     *
     * @param $value
     * @param $format
     * @return Carbon static|null
     */
    public static function create($value, $format) {
        if($value=='' || $value==null)
            return null;
        else
            return Carbon::createFromFormat($format, $value);
    }

    /**
     * Get a Carbon instance for a value and a date or datetime
     *
     * @param $value
     * @param string $outputFormat
     * @param string $fromFormat
     * @return string
     */
    public static function get($value, $outputFormat = '', $fromFormat = '') {
        if($value=='0000-00-00' || $value == '0000-00-00 00:00' || $value == null || $value == '')
            return '';
        else
            return self::getDateFormatted($value, $outputFormat, $fromFormat);
    }

    /**
     * Get time formatted with format defined in constant file
     *
     * @param $value
     * @return string HH:mm from unix
     */
    public static function getTimeFormatted($value){
        $time = self::createDate($value);
        return $time->format(config('constants.TIME_FORMAT'));
    }

    /**
     * Get date formatted with format defined in constant file
     *
     * @param string $value
     * @param string $outputFormat
     * @param string $fromFormat
     * @return string
     */
    private static function getDateFormatted($value, $outputFormat = '', $fromFormat = ''){
        $date = null;
        if ($fromFormat == '') {
            $date = self::createDate($value);
        } else {
            $date = self::createDateFromFormat($value, $fromFormat);
        }

        if($outputFormat == '') {
            $outputFormat = config('constants.DATE_FORMAT');
        }

        return $date->format($outputFormat);
    }

    /**
     * Get a carbon instance from a value and format
     *
     * @param $value
     * @param $fromFormat
     * @return \Carbon\Carbon::class
     */
    public static function getInstanceFrom($value, $fromFormat) {
        return self::createDateFromFormat($value, $fromFormat);
    }

    /**
     * Get datetime from UTC because is used for times
     *
     * @param $value
     * @return Carbon static
     */
    public static function createDate($value) {
        return Carbon::parse($value);
    }

    /**
     * @param $value
     * @param $format
     * @return \Carbon\Carbon
     */
    private static function createDateFromFormat($value, $format) {
        return Carbon::createFromFormat($format, $value);
    }

}