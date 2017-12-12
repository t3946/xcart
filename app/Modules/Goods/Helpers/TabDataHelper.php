<?php

namespace Modules\Goods\Helpers;


use Cocur\Slugify\Slugify;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\GoodsModule;
use Xcart\App\Orm\SlugFields\SlugField;

class TabDataHelper
{
    private static $_slug = null;

    public static function getSlug()
    {
        if (!self::$_slug) {
            self::$_slug = new Slugify();
        }

        return self::$_slug;
    }

    public static function getTabsFromManufacturer($mid)
    {
        $tabs = [];

        if ($model = DistributorModel::objects()->get(['pk' => $mid])) {
            $tabs = self::parseS3TabTags($model->cart_manufact_text_displayed);
        }

        return $tabs;
    }

    public static function parseS3TabTags($src)
    {
        $tabs = [];

        if ($src) {
            $t_tabs = explode('<s3-tab>', $src);

            if (is_array($t_tabs)) {
                foreach ($t_tabs as $item) {
                    $t = explode('</s3-tab>', $item);
                    $code = '__' . self::getSlug()->slugify($t[0]) . '__';

                    if (count($t) == 2) {
                        $tabs[$code] = [
                            'name' => GoodsModule::t($t[0], [], 'tabs'),
                            'code' => $code,
                            'content' => trim($t[1]),
                        ];
                    }
                }
            }
        }

        return $tabs;
    }
}