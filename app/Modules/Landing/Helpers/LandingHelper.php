<?php

namespace Modules\Landing\Helpers;

use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;

class LandingHelper
{
    /** @var AmpProductModel $model */
    private $model = null;

    public function __construct(ProductModel $model) {
        $this->model = $model;
    }

    public function getLogoImage(){

        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();

        $image_path = (string)$site->images[0]->image_path;
        $pref = ($site->getConfig()['Enable_CDN'] == "Y") ? 'cdn.': 'www.';
        $domain = $site->getBaseDomain();
        $domain = "//" .$pref . $domain;

        $for_image = substr($image_path, 1);
        $image = $domain . $for_image;

        return $image;
    }

    public function getImage(){

        $image_model = $image = null;

        $images_model = $this->model->getImages();

        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = $this->model->sites->limit(1)->get();
        $pref = ($site->getConfig()['Enable_CDN'] == "Y") ? 'cdn.': 'www.';
        $domain = $site->getBaseDomain();
        $domain = "//" .$pref . $domain;

        if ($images_model)
        {
            $image_model = reset($images_model);
        }

        if($image_model)
        {
            $for_image = ltrim($image_model->image_path, ".");
            $image = $domain . $for_image;
        }


        return $image;
    }
}