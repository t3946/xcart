<?php


namespace Modules\Sites\Helpers;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Sites\Models\SiteConfigModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Request\HttpRequest;

class CurrentSiteHelper
{
    public static function check($request)
    {
        /** @var HttpRequest $request */
        /** @var SiteModel $modelClass */
        $modelClass = Xcart::app()->getModule('Sites')->modelClass;
        $model = $modelClass::objects()->filter([ 'domain' => static::decode( $request->getDomain() ) ])->get();

        return $model;
    }

    public static function decode($value)
    {
        if (strpos($value, 'xn--') !== false)
        {
            if (function_exists('idn_to_utf8')) {
                return idn_to_utf8($value);
            }
            else if (class_exists('\TrueBV\Punycode')) {
                return (new \TrueBV\Punycode(Xcart::app()->locale['charset']))->decode($value);
            }
            else {
                Xcart::app()->logger->error("CurrentSiteMiddleware required php idn_to_utf8 or \\True\\Punycode packages");
            }
        }

        return $value;
    }

    public static function getGoogleAnalitycsAccount()
    {
        $modul = Xcart::app()->getModule('Sites');
        $site = $modul->getSite();

        if ($site->storefrontid) {
            return SiteConfigModel::objects()->get(['name' => 'cidev_ga_code_number', 'storefrontid' => $site->storefrontid])->value;
        }
        else {
            return GlobalConfigModel::objects()->get(['name' => 'cidev_ga_code_number'])->value;
        }
    }

    public static function formatCurrency($value): string
    {
        if (($site = Xcart::app()->getModule('Sites')->getSite()) && $site_currency = $site->getCurrency()) {
            $arr = [
                $site_currency->symbol_prefix,
                !$site_currency->after ? $site_currency : '',
                " <span class=\"price\">{$site_currency->getCurrencyFormat($value)}</span>",
                $site_currency->after ? $site_currency : '',
            ];
            return trim(implode('', $arr));
        }
        return '';
    }

}