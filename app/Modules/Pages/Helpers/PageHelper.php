<?php

namespace Modules\Pages\Helpers;

use Modules\Main\Models\EmployeesModel;
use Modules\Pages\Models\Page;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class PageHelper
{

    static public function getTeamMember($name = ''):? object
    {

        return EmployeesModel::objects()->get(['id' => $name]);
    }

    static public function getClearContent($content = ''): array
    {

        $s = strripos($content, "{");

        $names = substr($content, $s-1);

        $names = strip_tags($names);

        $content = substr($content, 0, $s-1);

        $names = str_replace("{{break}}\r\n", '', $names);

        $names = explode("\r\n", $names);

        $members = [];

        $regexp = '/.*?\((.*?)\)/s';

        $regexp_2 = '/(.*?)\(/s';
        foreach ($names as $key => $name){
            preg_match_all($regexp, $name, $matches);

            if (!$matches[1][1]){
                continue;
            }
            /** @var EmployeesModel $model */
            if ($model = self::getTeamMember($matches[1][1])) {

                preg_match($regexp_2, $name, $matches_2);

                $members[$key]['post'] = trim($matches_2[1]);
                $members[$key]['photo'] = $model->getField('photo')->getUrl();
                $members[$key]['name'] = trim($matches[1][0]);
            }
        }

        $members[4]['post'] = "Connoisseur of <br>beauty and aesthetics";

        return ['content' => $content, 'members' => $members];
    }

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

        $filter['language__lang_code'] = $site_model->getConfig()['Preferred_language'];

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