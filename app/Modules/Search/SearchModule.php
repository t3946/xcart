<?php

namespace Modules\Search;

use Xcart\App\Module\Module;

class SearchModule extends Module
{
    public const PRODUCTS_ENGINE = '%s-products';

    public static function getEngine(string $code, string $engine = self::PRODUCTS_ENGINE): string
    {
        return strtolower(sprintf($engine, $code));
    }

}