<?php

namespace Modules\Goods\Helpers;


use Mindy\QueryBuilder\Expression;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\ProductFileModel;
use Modules\Goods\Models\ProductModel;

class ProductHelper
{

    public static function cleanProductFullDescription($str): string
    {
        $result = '';
        $br_arr = array('<br>', '<BR>', '<br/>', '<Br>', '<bR>', '<Br/>', '<Br />', '<BR/>', '<bR/>', '<bR />', "\n");
        $str = str_replace($br_arr, '<br />', $str);
        $tmp_fulldescr_arr = explode('<br />', $str);
        if (!empty($tmp_fulldescr_arr)) {
            foreach ($tmp_fulldescr_arr as $k_br => $v_br) {
                $v_br = trim($v_br);
                if (!empty($v_br)) {
                    $v_br = '* ' . ucfirst($v_br);
                    $tmp_fulldescr_arr[$k_br] = $v_br;
                }
            }
            $result = implode("<br />", $tmp_fulldescr_arr);
        }
        return $result;
    }

    public static function getFileNameFromDownloadLink($imgLink, array $allowExtensions = [], $defaultExtension)
    {
        $result = null;
        $path = parse_url(strtolower($imgLink));
        if (!empty($path)) {
            $fileName = basename($path['path']);
            $filename = pathinfo($fileName);
            if (!empty($allowExtensions) && !in_array($filename['extension'], $allowExtensions, true)) {
                parse_str($path['query'], $arrQueryParams);
                if (!empty($arrQueryParams)) {
                    $arrQueryParamsFiltered = array_filter($arrQueryParams, function ($var) use($allowExtensions) {
                        foreach ($allowExtensions as $ext) {
                            if (strpos($var, ".{$ext}") !== false) {
                                return true;
                            }
                        }
                        return false;
                    });
                    if (!empty($arrQueryParamsFiltered)) {
                        $result = implode('_', array_values($arrQueryParamsFiltered));
                    } else {
                        $result = implode('_', array_values($arrQueryParams)) . '.'.$defaultExtension;
                    }
                }
            } else {
                $filePathPre = '';
                $dir = ltrim(\dirname(ltrim($path['path'], '/')), '.');
                if (!empty($dir)) {
                    $aPath = explode('/', $dir);
                    if (!empty($aPath)) {
                        $filePathPre = implode('_', $aPath) . '_';
                    };
                }
                $result = $filePathPre . $fileName;
            }
        }
        return $result;
    }

    /**
     * @param $fileDesc
     * @param $filePath
     * @param $product_id
     * @return ProductFileModel|null
     */
    public static function uploadProductFile($fileDesc, $filePath, $product_id):? ProductFileModel
    {
        global $product_files_dir;

        $productFileModel = null;

        $fileName = file_get_filename_curl($filePath);
        if (empty($fileName)) {
            $fileName = self::getFileNameFromDownloadLink($filePath, ['pdf', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'tiff', 'png', 'jpeg', 'jfif'], 'pdf');
        }

        if (!$fileName) return $productFileModel;

        $param = ['filename' => $fileName, 'productid' => $product_id];
        $productFileModel = ProductFileModel::objects()->filter($param)->limit(1)->get();
        if (!$productFileModel) {
            $fileData = file_get_contents_curl($filePath);
            if (!empty($fileData)) {
                $path = $product_files_dir . '/' . $product_id;
                if (!is_dir($path)) {
                    func_mkdir($path, 0755);
                }
                if ($fileSize = file_put_contents($path . "/" . $fileName, $fileData)) {
                    $productFileModel = new ProductFileModel($param);
                    $productFileModel->setAttributes([
                                                         'description' => $fileDesc,
                                                         'filesize' => $fileSize
                                                     ]);
                }
            }
        }
        return $productFileModel;
    }

    public static function getProductByCode($code)
    {
        $model = ProductModel::objects()->get(['productcode' => (string) $code]);
        if (!$model){
            $modelSKU = AmazonFbaMissingSkuModel::objects()->get(['missing_productcode' => (string) $code]);
            if ($modelSKU) {
                $model = $model->product;
            }
        }
        return $model;
    }

    public static function getPricingArray($params)
    {
        $res = [];

        $map_price = $params['map_price'] ?? 0;

        if ($params['pricing']) {

            foreach ($params['pricing'] as $price) {
                $res[$price->quantity] = [
                    'price' => max($price->price, $map_price)
                ];
            }
        }
        if (!empty($params['json'])) {
            return json_encode($res);
        }
        return $res;
    }

    /**
     * @param ProductModel[] $oProducts
     * @return ProductModel[]
     */
    public static function groupRootProducts($oProducts): array
    {
        $res = [];
        if ($oProducts) {
            foreach ($oProducts as $oProduct) {

                if ($oProduct->isGroupChild()) {
                    if (!array_key_exists($oProduct->group_root, $res)) {
                        if ($parent = $oProduct->parent) {
                            $res[$parent->productid] = $parent;
                        }
                    }
                }
                else {
                    $res[$oProduct->productid] = $oProduct;
                }
            }
        }

        return $res;
    }

    /**
     * @param string[] $a
     * @return string
     */
    public static function getFirstSame($a): string
    {
        function intersect($arr1, $arr2) {
            $res = [];
            foreach ($arr1 as $key => $val) {
                $ar = array_map('strtolower', $arr2);
                if (mb_strtolower($val) === $ar[$key]){
                    $res[] = $val;
                } else {
                    break;
                }
            }
            return $res;
        }

        $arr = null;
        foreach ($a as $w) {
            $as = explode(' ', $w);
            if (\is_null($arr)) {
                $arr = $as;
            } else {
                $arr = intersect($as, $arr);
            }
        }

        return implode(' ', $arr);
    }

    public static function getGroupLevel($option)
    {
        return mb_substr_count($option, ' ');
    }

    public static function getNewGroupSKU($manufacturer_id)
    {
        $new_sku = null;

        $format = '%s-GROUP-%d';

        if ($last = ProductModel::objects()->filter(
            [
                'group_root__isnull' => false,
                'group_root' => new Expression('productid')
            ])
                                ->order([new Expression("-COALESCE(CAST(SUBSTRING_INDEX(productcode, '-', -1) AS UNSIGNED), 1)")])
                                ->limit(1)
                                ->get()
        ) {
            if (preg_match('/-(\d+)$/', $last->productcode, $m)) {
                if ($model = DistributorModel::objects()->get(['manufacturerid' => $manufacturer_id])) {
                    $new_sku = sprintf($format, $model->code, intval($m[1]) + 1);
                }
            }
        }

        return $new_sku;
    }

    public static function calculateUPC($upc_code)
    {
        $upc_code = preg_replace('/[^0-9]/', '', $upc_code);
        switch (\strlen($upc_code)) {
            case 8:
            case 14:
                $cd = self::UPC_calculate_check_digit($upc_code);
                if ($cd != $upc_code[strlen($upc_code) - 1]) {
                    return substr($upc_code, 0, -1) . $cd;
                } else {
                    return $upc_code;
                }
                break;
            case 11:
            case 12:
            case 13:
                $cd = self::UPC_calculate_check_digit($upc_code);
                if ($cd != $upc_code[strlen($upc_code) - 1]) {
                    if (!self::isISBN($upc_code) || (self::isISBN($upc_code) && \strlen($upc_code) == 12)) {
                        $cd = self::UPC_calculate_check_digit($upc_code . '1');
                        return $upc_code . $cd;
                    }
                    else {
                        return '';
                    }
                }
                else {
                    return $upc_code;
                }

                break;
        }
        return '';
    }

    private static function UPC_calculate_check_digit($upc_code)
    {
        $sum = 0;
        $mult = 3;
        for ($i = (\strlen($upc_code) - 2); $i >= 0; $i--) {
            $sum += $mult * $upc_code[$i];
            if ($mult == 3) {
                $mult = 1;
            } else {
                $mult = 3;
            }
        }
        if ($sum % 10 == 0) {
            $sum = ($sum % 10);
        } else {
            $sum = 10 - ($sum % 10);
        }
        return $sum;
    }

    private static function isISBN($sCode)
    {
        $bResult = false;
        if (\in_array(strlen($sCode), [10, 13], true) && \in_array(substr($sCode, 0, 3), [978, 979], true)) {
            $bResult = true;
        }
        return $bResult;
    }

    /**
     * @param ProductModel $model
     *
     * @return string
     */
    public static function getJsonSchema($model)
    {
        $json = [];

        if ($model->isOutOfStock()) {
            $availability = "http://schema.org/OutOfStock";
        }
        else {
            $availability = "http://schema.org/InStock";
        }

        $descript = strip_tags($model->getFrontendDescription());

        if($model->isGroupRoot()){

            $json = [
                "@context" => "http://schema.org/",
                "@type" => "Product",
                "name" => $model->getFrontendName(),
                "image" => self::getJsonImages(1, $model),
                "description" => $descript,
                "mpn" => $model->getMPN(),
                "brand" => [
                    "@type" => "Thing",
                    "name" => $model->brand->getProductFrontendName(),
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
                        "name" => "S3Stores, Inc.",
                        "url" => "http://www.s3stores.com/",
                        "sameAs" => [
                            "https://www.facebook.com/s3stores/",
                            "https://twitter.com/s3stores/",
                            "https://www.youtube.com/channel/UCjE6xR1TriWo-hCDsbpvMKg",
                            "https://www.pinterest.com/s3storesinc/",
                            "https://plus.google.com/118379608603424325840"
                        ],
                    ]
                ]
            ];

        } else {

            $json = [
                "@context" => "http://schema.org/",
                "@type" => "Product",
                "name" => $model->getFrontendName(),
                "image" => self::getJsonImages(1, $model),
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
                        "name" => "S3Stores, Inc.",
                        "url" => "http://www.s3stores.com/",
                        "sameAs" => [
                            "https://www.facebook.com/s3stores/",
                            "https://twitter.com/s3stores/",
                            "https://www.youtube.com/channel/UCjE6xR1TriWo-hCDsbpvMKg",
                            "https://ru.pinterest.com/s3storesinc/",
                            "https://plus.google.com/118379608603424325840"
                        ],
                    ]
                ]
            ];
        }

        return json_encode($json);

    }

    public static function getJsonImages($flag = 0, $model)
    {

        $images = [];
        $image = null;

        /** @var \Modules\Sites\Models\SiteModel $site */
        $site = $model->sites->limit(1)->get();
        $pref = ($site->getConfig()['Enable_CDN'] == "Y") ? 'cdn.' : 'www.';
        $domain = $site->getBaseDomain();
        $domain = "//" . $pref . $domain;

        if ($model->isGroupRoot()) {
            $product_models = $model->getFrontendChilds();
            foreach ($product_models as $p_model) {

                $images_model = $p_model->getImages();

                if ($images_model) {
                    $image_model = reset($images_model);
                }
                else {
                    $image_model = $p_model->getThumbnail();
                }

                if ($image_model) {
                    $for_image = ltrim($image_model->image_path, ".");
                    $images[] = $domain . $for_image;
                }
            }

            if (!$flag) {
                return $images;
            }
            else {
                return json_encode($images);
            }
        }
        else {
                $images_model = $model->images->all();
                foreach ($images_model as $image){
                    if($image)
                    {
                        $for_image = ltrim($image->image_path, ".");
                        $images[] = $domain . $for_image;
                    }
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