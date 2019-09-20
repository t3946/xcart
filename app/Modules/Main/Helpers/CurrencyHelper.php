<?php


namespace Modules\Main\Helpers;


use Modules\Sites\Models\CurrencyModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class CurrencyHelper
{
    public static function convertToCurrency(CurrencyModel $from, CurrencyModel $to, $value): float
    {
        $result = $value;

        if ($from->currency_id === $to->currency_id) {
            return $result;
        }

        if (!$from->is_primary) {
            $result /= $from->coefficient;
        }

        if ($to->is_primary) {
            $result /= $from->coefficient;
        } else {
            $result *= $to->coefficient;
        }

        return (float) round($result, 2);
    }

    public static function convert(CurrencyModel $to, $value)
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        return self::convertToCurrency($to, $site->getCurrency(), $value);
    }
}