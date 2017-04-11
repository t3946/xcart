<?php
namespace Modules\Product\Helpers;


use Modules\Product\Models\ImageDModel;
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

    public static function uploadProductFile($fileName, $filePath)
    {
        $sDataFile = file_get_contents_curl($filePath);
        if (!empty($sDataImage)) {
            if (file_put_contents($xcart_dir . $image_file_path, $sDataImage)) {

            }
        }
    }
}