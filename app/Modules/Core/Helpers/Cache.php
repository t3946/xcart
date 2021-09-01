<?php

namespace Modules\Core\Helpers;


class Cache
{
    const CACHE_YEAR = 31536000;
    const CACHE_WEEK = 604800;
    const CACHE_DAY = 86400;
    const CACHE_HOUR = 3600;
    const CACHE_MINUTE = 60;

    const CACHE_HALF_WEEK = 302400;
    const CACHE_HALF_DAY = 43200;
    const CACHE_HALF_HOUR = 1800;
    const CACHE_HALF_MINUTE = 30;

    public static function cache_time(string $cache_to):int
    {
        $to_time = strtotime($cache_to);
        $to_time -= time();

        if ($to_time > 0) {
            return $to_time;
        }

        return 0;
    }
}