<?php

namespace App\Libraries;

class Util
{
    public static function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    public static function trim($value)
    {
        $value = (empty($value)) ? null : trim(preg_replace('/\s+/', ' ', $value));
        return (empty($value)) ? null : $value;
    }

}