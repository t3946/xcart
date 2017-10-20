<?php

namespace Modules\AMP\Models;

use Modules\AMP\Helpers\AMPHelper;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductModel;
use Modules\Sites\Models\SiteConfigModel;
use Xcart\App\Main\Xcart;

class AmpProductModel extends ProductModel
{
    /**
     * @var \Modules\Sites\SitesModule $modul
     *
     * @return mixed (unique id for each storefront)
     * @throws \Exception
     * @throws \Xcart\App\Exceptions\UnknownPropertyException
     */
    public function getUniqueIdSf()
    {
        $modul = Xcart::app()->getModule('Sites');
        $site = $modul->getSite();
//        $site = $this->sites->limit(1)->get();

        if ($site->storefrontid) {
            return SiteConfigModel::objects()->get(['name' => 'cidev_ga_code_number', 'storefrontid' => $site->storefrontid])->value;
        }
        else {
            return GlobalConfigModel::objects()->get(['name' => 'cidev_ga_code_number'])->value;
        }
    }

    /**
     * @param int $flag
     * @var \Modules\Sites\Models\SiteModel $site
     * @var string $pref
     * var ImagePModel[] $images_model
     *
     * @return array|string
     */
    public function getJsonImages($flag = 0){

        $images = [];
        $image = null;

        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = $this->sites->limit(1)->get();
        $images_model = $this->getImages();
        $pref = ($site->getConfig()['Enable_CDN'] == "Y") ? 'cdn.': 'www.';
        $domain = $site->getBaseDomain();
        $domain = "//" .$pref . $domain;

        foreach ($images_model as $image_model){
            $for_image = substr($image_model->image_path, 1);
            $images[] = $domain . $for_image;
        }
        if(!$flag){
            return $images;
        } else {
            return json_encode($images);
        }
    }

}