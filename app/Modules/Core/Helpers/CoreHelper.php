<?php

namespace Modules\Core\Helpers;


class CoreHelper
{
    public static function stripTags($content)
    {
        $content =  preg_replace('/<[^>]*>/', ' ', $content)."\n";
        $content = preg_replace('/\s+/', ' ', $content)."\n";
        return trim($content);
    }
}