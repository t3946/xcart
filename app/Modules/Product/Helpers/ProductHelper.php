<?php

namespace Modules\Product\Helpers;


use Modules\Product\Models\ProductFileModel;

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
        $path = parse_url($imgLink);
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
            $fileName = self::getFileNameFromDownloadLink($filePath, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'tiff', 'png', 'jpeg', 'jfif'], 'docx');
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
}