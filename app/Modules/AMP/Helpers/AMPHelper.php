<?php


namespace Modules\AMP\Helpers;


use Mindy\QueryBuilder\Expression;
use Modules\Product\Models\CategoryModel;
use Modules\AMP\Models\AmpProductModel;
use Xcart\App\Main\Xcart;


class AMPHelper
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
            $itemCondition = "http://schema.org/InStock";
        }
        else {
            $itemCondition = "http://schema.org/OutOfStock";
        }

        $descript = strip_tags($model->getFrontendDescription());

        $json = [
            "@context" => "http://schema.org/",
            "@type" => "Product",
            "name" => $model->product,
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
                "itemCondition" => $itemCondition,
                "seller" => [
                    "@type" => "Organization",
                    "name" => "S3Stores, Inc."
                ]
            ]
        ];

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
}
