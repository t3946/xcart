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

    /**
     * @param $fileDesc
     * @param $filePath
     * @param $product_id
     * @return ProductFileModel|null
     */
    public static function uploadProductFile($fileDesc, $filePath, $product_id)
    {
        global $product_files_dir;

        $fileName = ImageHelper::getImageFileNameFromDownloadLink($filePath, 'pdf');
        $param = ['filename' => $fileName, 'productid' => $product_id];
        $productFileModel = ProductFileModel::objects()->filter($param)->limit(1)->get();
        if (!$productFileModel) {
            $sDataFile = file_get_contents_curl($filePath);
            if (!empty($sDataFile)) {
                $path = $product_files_dir . '/' . $product_id;
                if (!is_dir($path)) {
                    func_mkdir($path, 0755);
                }
                if ($fileSize = file_put_contents($path . "/" . $fileName, $sDataFile)) {
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