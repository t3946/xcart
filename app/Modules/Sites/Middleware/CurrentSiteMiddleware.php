<?php
namespace Modules\Sites\Middleware;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;
use Xcart\App\Request\HttpRequest;

class CurrentSiteMiddleware extends Middleware
{
    public function processRequest($request)
    {
        if (!Cli::isCli()) {
            /** @var HttpRequest $request */
            /** @var SiteModel $modelClass */
            $modelClass = Xcart::app()->getModule('Sites')->modelClass;
            $model = $modelClass::objects()->filter([
                'domain' => $this->decode($request->getHost())
            ])->get();

            if ($model !== null) {
                Xcart::app()->getModule('Sites')->setSite($model);
            }
        }
    }

    public function decode($value)
    {
        if (function_exists('idn_to_utf8')) {
            return idn_to_utf8($value);
        }
        else if (class_exists('\True\Punycode')) {
            return (new \TrueBV\Punycode(Xcart::app()->locale['charset']))->decode($value);
        }
        else {
//            Xcart::app()->logger->error("CurrentSiteMiddleware required php intl or \\True\\Punycode packages");
            return $value;
        }
    }
}
