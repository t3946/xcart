<?php


namespace Modules\Amp\Helpers;


use Mindy\QueryBuilder\Expression;
use Modules\Goods\Models\CategoryModel;
use Modules\Amp\Models\AmpProductModel;
use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Main\Xcart;
use Xcart\Cart;
use Xcart\CartElement;

class AmpHelper
{
    /** @var AmpProductModel $model */
    private $model = null;

    public function __construct(AmpProductModel $model) {
        $this->model = $model;
    }



    public function getDataJsonSchema()
    {
        $model = $this->model;
        $json = [];

        if ($this->model->avail > 0) {
            $availability = "http://schema.org/InStock";
        }
        else {
            $availability = "http://schema.org/OutOfStock";
        }

        $descript = strip_tags($model->getFrontendDescription());

        if($this->model->isGroupRoot()){

            $json = [
                "@context" => "http://schema.org/",
                "@type" => "Product",
                "name" => $model->getFrontendName(),
                "image" => $model->getJsonImages(1),
                "description" => $descript,
                "mpn" => $model->getMPN(),
                "brand" => [
                    "@type" => "Thing",
                    "name" => $model->brand->brand
                ],
                "offers" => [
                    "@type" => "Offer",
                    "PriceSpecification" => [
                        "@type" => "PriceSpecification",
                        "priceCurrency" => "USD",
                        "price" => $model->getFrontendPrice(),
                        "minPrice" => $model->getFrontendPrice(),
                        "maxPrice" => $model->getFrontendPrice(2),
                    ],
                    "itemCondition" => "NewCondition",
                    "seller" => [
                        "@type" => "Organization",
                        "name" => "S3Stores, Inc."
                    ]
                ]
            ];

        } else {

            $json = [
                "@context" => "http://schema.org/",
                "@type" => "Product",
                "name" => $model->getFrontendName(),
                "image" => $model->getJsonImages(1),
                "description" => $descript,
                "mpn" => $model->getMPN(),
                "brand" => [
                    "@type" => "Thing",
                    "name" => $model->brand->brand
                ],
                "offers" => [
                    "@type" => "Offer",
                    "priceCurrency" => "USD",
                    "price" => $model->getFrontendPrice(),
                    'availability' => $availability,
                    "itemCondition" => "NewCondition",
                    "seller" => [
                        "@type" => "Organization",
                        "name" => "S3Stores, Inc."
                    ]
                ]
            ];
        }

        $json = json_encode($json);

        return $json;

    }

    public function getDataJsonBread($categories = null)
    {
        if (!$categories) {
            $category = $this->model->getMainCategory();
            $cids = explode('/',$category->categoryid_path);
            $categories = CategoryModel::objects()
                                       ->filter(['categoryid__in' =>$cids])
                                       ->order([new Expression('FIELD(categoryid, '.implode(',', $cids).')')])
                                       ->all();
        }

        $json = [];

        if($categories){

            $itemListElement = [];
            $i = 0;
            foreach ($categories as $cat)
            {
                $i++;
                $element = [];

                $element = [
                    "@type" => "ListItem",
                    "position" => $i,
                    "item" => [
                        "@id" => $cat->getAbsoluteUrl(true),
                        "name" => $cat->category
                    ]
                ];

                $itemListElement[] = $element;
            }

            $json = [
                "@context" => "http://schema.org",
                "@type" => "BreadcrumbList",
                "itemListElement" => $itemListElement
            ];
        }

        $json = json_encode($json);

        return $json;

    }

    public function getLastChildCategoryUrl($category = null){

        $last_category_url = null;

        if (!$category) {
            $category = $this->model->getMainCategory();
            $cids = explode('/',$category->categoryid_path);
            $last_cid = end($cids);
            $category = CategoryModel::objects()->get(['categoryid' =>$last_cid]);
        }

        if($category){
            $last_category_url = $category->getAbsoluteUrl(true);
        }

        if(empty($last_category_url)){
            $last_category_url = $this->model->getAbsoluteUrl();
        }

        return $last_category_url;
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

    public function addToCart(){
        $action = "cart.php";
        $productid = $this->model->productid;
        $amount = 1;
        if (include "ajax_add_to_cart.php")
        Xcart::app()->request->redirect('//dev07.artist/cart.php');
    }

}
