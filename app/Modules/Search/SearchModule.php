<?php

namespace Modules\Search;

use Xcart\App\Module\Module;

class SearchModule extends Module
{
    public const PRODUCTS_ENGINE = '%s-products';

    public static function getEngine(string $code): string
    {
        return strtolower(sprintf(self::PRODUCTS_ENGINE, $code));
    }

}