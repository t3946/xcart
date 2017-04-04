<?php
namespace Modules\Product\Helpers;


use Modules\Product\Models\ImageDModel;
use Modules\Product\Models\ProductModel;

class ImageHelper
{
    protected static $__extensions = ['gif', 'jpeg', 'jfif', 'jpg', 'jpe', 'bmp', 'png'];

    public static function getImageFileNameFromDownloadLink($imgLink)
    {
        $result = null;
        $path = parse_url($imgLink);
        if (!empty($path)) {
            $fileName = basename($path['path']);
            $filename = pathinfo($fileName);
            if (!in_array($filename['extension'], self::$__extensions)) {
                parse_str($path['query'], $arrQueryParams);
                if (!empty($arrQueryParams)) {
                    $arrQueryParamsFiltered = array_filter($arrQueryParams, function ($var) {
                        foreach (self::$__extensions as $ext) {
                            if (strpos($var, ".{$ext}") !== false) {
                                return true;
                            }
                        }
                        return false;
                    });
                    if (!empty($arrQueryParamsFiltered)) {
                        $result = implode('_', array_values($arrQueryParamsFiltered));
                    } else {
                        $result = implode('_', array_values($arrQueryParams)) . '.jpg';
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
     * @param string $image
     * @param string $name
     * @param string $prefix
     * @return ImageDModel|null
     */
    public static function uploadMainImage($image, $name, $prefix)
    {
        /** @var ImageDModel $imageModel */
        $imageModel = null;
        $SET_IMAGE_URL = self::getImageFileNameFromDownloadLink($image);
        if (empty($SET_IMAGE_URL)) {
            $img_path_arr = explode("//", $image);
            $img_path_arr2 = explode("/", $img_path_arr[1]);
            unset($img_path_arr2[0]);
            $img_path_after = implode("_", $img_path_arr2);
            $img_path_after_arr = explode(".", $img_path_after);
            $ext = array_pop($img_path_after_arr);
            $Prod_ID = $prefix . "_" . implode("_", $img_path_after_arr);
            $image_file_name = $Prod_ID . "." . $ext;
        } else {
            $image_file_name = $prefix . "_" . $SET_IMAGE_URL;

        }
        $image_file_name = str_replace(' ', '', rawurldecode($image_file_name));
        $image_file_path =  "./images/D/" . $image_file_name;

        $sDataImage = file_get_contents_curl($image);

        if (!empty($sDataImage)) {
            if (file_put_contents($image_file_path, $sDataImage)) {
                $img_info = getimagesize($image_file_path);
                if ($img_info) {
                    $imageModel = ImageDModel::objects()->filter(['image_path' => $image_file_path]);
                    if (!$imageModel) {
                        $imageModel = new ImageDModel([
                            'date' => time(),
                            'image_path' => $image_file_path,
                            'image_type' => $img_info["mime"],
                            'image_x' => $img_info[0],
                            'image_y' => $img_info[1],
                            'image_size' => filesize($image_file_path),
                            'alt' => $name,
                            'avail' => 'Y'
                        ]);
                    }
                }
            }
        }
        return $imageModel;
    }
}