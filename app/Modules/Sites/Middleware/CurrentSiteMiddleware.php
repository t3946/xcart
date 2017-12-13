<?php
namespace Modules\Sites\Middleware;

use Modules\Sites\Helpers\CurrentSiteHelper;
use Modules\Sites\Models\SiteModel;
use Modules\Sites\SitesModule;
use Xcart\App\Cli\Cli;
use Xcart\App\Main\Xcart;
use Xcart\App\Middleware\Middleware;

class CurrentSiteMiddleware extends Middleware
{
    public $isProcessRequest = true;

    public function processRequest($request)
    {
        /** @var SitesModule $sitesModule */
        $sitesModule = Xcart::app()->getModule('Sites');

        if (defined('APP_LOCAL')) {
            defined('LOCAL_SF_ID') ?: define('LOCAL_SF_ID', $sitesModule->getSite()->storefrontid);
            defined('LOCAL_SF_DOMAIN') ?: define('LOCAL_SF_DOMAIN', $_SERVER['SERVER_NAME']);

            $GLOBALS['xcart_http_host'] = $GLOBALS['xcart_https_host'] = $_SERVER['HTTP_HOST'];
        }
        elseif (!Cli::isCli() && $model = CurrentSiteHelper::check($request)) {
            $sitesModule->setSite($model);
        }

        $domain = SiteModel::objects()->cache(50000)->filter(['code' => 'AR'])->valuesList(['domain'], true);
        $domain = $domain[0];

        defined('DEFAULT_SF_DOMAIN') ?: define('DEFAULT_SF_DOMAIN', $domain);

        defined('MAIN_SF_DOMAIN') ?:
            defined('LOCAL_SF_DOMAIN') ?
                define('MAIN_SF_DOMAIN', LOCAL_SF_DOMAIN) :
                define('MAIN_SF_DOMAIN', $domain);
    }
}
