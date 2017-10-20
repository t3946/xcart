<?php

namespace Modules\Product\Helpers;


use Mindy\QueryBuilder\Expression;
use Modules\Amazon\Models\AmazonFbaMissingSkuModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Product\Models\ProductFileModel;
use Modules\Product\Models\ProductModel;

class ProductHelper
{

    public static function cleanProductFullDescription($str)
    {
        $result = '';
        $br_arr = array("<br>", "<BR>", "<br/>", "<Br>", "<bR>", "<Br/>", "<Br />", "<BR/>", "<bR/>", "<bR />", "\n");
        $str = str_replace($br_arr, "<br />", $str);
        $tmp_fulldescr_arr = explode("<br />", $str);
        if (!empty($tmp_fulldescr_arr)) {
            foreach ($tmp_fulldescr_arr as $k_br => $v_br) {
                $v_br = trim($v_br);
                if (!empty($v_br)) {
                    $v_br = "* " . ucfirst($v_br);
                    $tmp_fulldescr_arr[$k_br] = $v_br;
                }
            }
            $result = implode("<br />", $tmp_fulldescr_arr);
        }
        return $result;
    }

    public static function getFileNameFromDownloadLink($imgLink, $allowExtensions = [], $defaultExtension)
    {
        $result = null;
        $path = parse_url(strtolower($imgLink));
        if (!empty($path)) {
            $fileName = basename($path['path']);
            $filename = pathinfo($fileName);
            if (!empty($allowExtensions) && !in_array($filename['extension'], $allowExtensions)) {
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
                $dir = ltrim(dirname(ltrim($path['path'], '/')), '.');
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
    public static function uploadProductFile($fileDesc, $filePath, $product_id)
    {
        global $product_files_dir;

        $fileName = file_get_filename_curl($filePath);
        if (empty($fileName)) {
            $fileName = self::getFileNameFromDownloadLink($filePath, ['pdf', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'tiff', 'png', 'jpeg', 'jfif'], 'pdf');
        }
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
        if ($params['pricing']) {
            foreach ($params['pricing'] as $price) {
                $res[$price->quantity] = [
                    'price' => $price->price
                ];
            }
        }
        if (isset($params['json']) && $params['json']) {
            return json_encode($res);
        }
        return $res;
    }

    /**
     * @param ProductModel[] $oProducts
     * @return mixed
     */
    public static function groupRootProducts($oProducts)
    {
        $res = [];
        if ($oProducts) {
            foreach ($oProducts as $oProduct) {
                if (!$oProduct->isGroupRoot() && !is_null($oProduct->group_root)) {
                    if (!array_key_exists($oProduct->group_root, $res)) {
                        if ($parent = $oProduct->parent) {
                            $res[$parent->productid] = $parent;
                        }
                    }
                } else {
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
    public static function getFirstSame($a)
    {
        function longestCS($a, $b)
        {
            if (empty($a) || empty($b)) {
                return '';
            }

            if ($a === $b) {
                return $a;
            }

            $b = trim(mb_substr($b, 0, mb_strrpos($b, ' '))). ' ';

            for ($i = 0; $i < mb_strlen($a) - 1; $i++) {
                if (mb_substr($a, $i, 1) != mb_substr($b, $i, 1)) {
                    if (!$i) {
                        return '';
                    }
                    if (($ls = mb_strpos($a, ' ', $i)) > $i) {
                        $s = mb_substr($a, 0, $ls);
                    } else {
                        $s = mb_substr($a, 0, $i);
                    }
                    return trim(mb_substr($s, 0, mb_strrpos($s, ' ')));
                }
            }
            return mb_substr($a, 0, ++$i);
        }

        $b = null;

        if ($a) {

            $b = array_shift($a);

            foreach ($a as $s) {
                $b = longestCS($b, $s);
            }
        }
        return trim($b);
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
}