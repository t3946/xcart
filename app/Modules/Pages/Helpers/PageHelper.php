<?php

namespace Modules\Pages\Helpers;

use Modules\Main\Models\EmployeesModel;
use Modules\Pages\Models\Page;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class PageHelper
{

    public static function getPage($url): ?Page
    {
        /** @var SiteModel $site_model */
        $site_model = Xcart::app()->getModule('Sites')->getSite();

        $filter = [];

        if (empty($url)){
            $filter['is_index'] = true;
        }
        else {
            $filter['url'] = ltrim($url, '/');
        }

        $filter['lang_id'] = $site_model->lang_id;

        $filter['sites__through__storefront_id'] = $site_model->storefrontid;

        /** @var Page $model */
        if (!$model = Page::objects()->published()->get($filter) ){

            unset($filter['sites__through__storefront_id']);
            $filter['sites__through__storefront_id__isnull'] = true;
            /** @var Page $model */
            $model = Page::objects()
                ->published()
                ->get($filter);
        }

        return $model ?? null;
    }

}