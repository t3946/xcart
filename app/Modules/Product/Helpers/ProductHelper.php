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

    public static function uploadProductFile($fileDesc, $filePath, $product_id)
    {
        global $product_files_dir;

        $fileName = basename($filePath);
        $param = ['filename' => $fileName, 'productid' => $product_id];
        $productFileModel = ProductFileModel::objects()->filter($param)->limit(1)->get();
        if (!$productFileModel) {
            $sDataFile = file_get_contents_curl($filePath);
            if (!empty($sDataFile)) {
                if ($fileSize = file_put_contents($product_files_dir . "/" . $product_id . $fileName, $sDataFile)) {
                    $productFileModel = new ProductFileModel($param);
                    $productFileModel->setAttributes([
                        'description' => $fileDesc,
                        'filesize' => $fileSize,
                        'avail' => 'Y',

                    ]);
                    $productFileModel->save();
                }
            }
        }
    }

    public static function getHardToResellStatus($hardResellModel)
    {
        $bHardToResell = null;
        if ($hardResellModel) {
            if ($hardResellModel->positive_count >=2 && $hardResellModel->negative_count == 0){
                $bHardToResell = true;
            } elseif ($hardResellModel->positive_count ==0 && $hardResellModel->negative_count >= 2) {
                $bHardToResell = false;
            } elseif ($hardResellModel->positive_count > 0 && $hardResellModel->negative_count > 0) {
                if ($hardResellModel->positive_count / $hardResellModel->negative_count < 0.5) {
                    $bHardToResell = false;
                } elseif ($hardResellModel->negative_count / $hardResellModel->positive_count < 0.5) {
                    $bHardToResell = true;
                }
            }
        }
        return $bHardToResell;
    }
}