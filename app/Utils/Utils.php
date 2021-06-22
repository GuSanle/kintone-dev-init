<?php

namespace App\Utils;

class Utils
{
    public static function removeRevision(array $array)
    {
        if (array_key_exists("revision", $array)) {
            unset($array["revision"]);
        }
        return $array;
    }

    public static function explodeDomain($host)
    {
        $hostArray = explode('.', $host);
        return  [
            "domain" => implode('.', array_slice($hostArray, -2)),
            "subdomain" => implode('.', array_slice($hostArray, 0, count($hostArray) - 2))
        ];
    }
}
