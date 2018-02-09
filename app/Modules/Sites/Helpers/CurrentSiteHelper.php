<?php


namespace Modules\Sites\Helpers;

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
        if (function_exists('idn_to_utf8')) {
            return idn_to_utf8($value);
        }
        else if (class_exists('\True\Punycode')) {
            return (new \TrueBV\Punycode(Xcart::app()->locale['charset']))->decode($value);
        }

        Xcart::app()->logger->error("CurrentSiteMiddleware required php intl or \\True\\Punycode packages");
        return $value;
    }
}