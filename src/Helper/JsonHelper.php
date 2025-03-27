<?php

namespace App\Helper;

class JsonHelper
{

    public static function decode(string $json): mixed
    {
        return json_decode($json, associative: true, flags: JSON_THROW_ON_ERROR);
    }

}
