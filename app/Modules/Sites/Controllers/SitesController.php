<?php

namespace Modules\Sites\Controllers;

use Modules\Admin\Controllers\BackendController;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class SitesController extends BackendController
{
    /**
     * переключает сайт и возвращает ссылку на логотип нового сайта
    */
    public function setSite(int $site_id)
    {
        Xcart::app()->request->session->add('current_storefront', $site_id);
        //site logo
        $site_code = strtolower(Xcart::app()->getModule('Sites')->getSelectedSite()->code);
        $this->jsonResponse(["logoUrl" => Paths::get('dist') . "/images/logos/sites/$site_code/logo.svg"]);
    }
}
