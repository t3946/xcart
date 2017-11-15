<?php

namespace Modules\Amp\Models;

use Modules\Amp\Helpers\AmpHelper;
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

//    public function getFavicon(){
//        $modul = Xcart::app()->getModule('Sites');
//        $site = $modul->getSite();
//
//        /** @var \Modules\Sites\Models\ImageFModel $favoicons */
//        $favoicons = $site->favicons->limit(1)->get();
//
//        return $favoicons->getURL();
//
//    }

    public function getAbsoluteUrl($full = false, $amp = false)
    {
        if ($this->productid && $amp) {
            return $this->clean_url->urlFromCode('amp:product', $full, ($full ? $this->sites->limit(1)->get() : null));
        }

        return parent::getAbsoluteUrl($full);
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
        $pref = ($site->getConfig()['Enable_CDN'] == "Y") ? 'cdn.': 'www.';
        $domain = $site->getBaseDomain();
        $domain = "//" .$pref . $domain;

        if($this->isGroupRoot()){
            $product_models = $this->getFrontendChilds();
            foreach ($product_models as $p_model){

                $images_model = $p_model->getImages();

                if ($images_model)
                {
                    $image_model = reset($images_model);
                }
                else
                {
                    $image_model = $p_model->getThumbnail();
                }

                if($image_model)
                {
                    $for_image = ltrim($image_model->image_path, ".");
                    $images[] = $domain . $for_image;
                }
            }

            if(!$flag){
                return $images;
            }
            else {
                return json_encode($images);
            }
        }

        else {
            $images_model = $this->getImages();

            if ($images_model)
            {
                $image_model = reset($images_model);
            }
            else
            {
                $image_model = $this->getThumbnail();
            }

            if($image_model)
            {
                $for_image = ltrim($image_model->image_path, ".");
                $images[] = $domain . $for_image;
            }

            if (!$flag) {
                return $images;
            }
            else {
                return json_encode($images);
            }
        }
    }
}